<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 08 2015
 */

namespace App\ApiBundle\Model\Controller;

use App\ApiBundle\Controller\BusinessController;
use App\CoreBundle\Model\Handler\ShopHandlerInterface;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EntityRESTController
 * @package App\ApiBundle\Model\Controller
 */
abstract class EntityRESTController extends FOSRestController
{
    const STATUS_CODE_BUSINESS_NOT_FOUND = 404;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_SUCCESS = 200;

    protected $responseFormat = 'json';

    /**
     * @return ShopHandlerInterface
     */
    protected function getShopHandler()
    {
        return $this->container->get('app_core.shop.handler');
    }

    /**
     * @param $response
     * @return View
     */
    protected function generateView($response)
    {
        $status = isset($response['status']) ? $response['status'] : 500;
        $data = isset($response['data']) ? $response['data'] : [];
        $error = false;
        $errorCode = 200;
        $errorMessage = '';

        if (isset($response['error'])) {
            $errorCode = isset($response['error']['code']) ? $response['error']['code'] : null;
            $errorMessage = isset($response['error']['message']) ? $response['error']['message'] : '';
            $error = ($errorCode || $errorMessage);
        }
        $responseArray = [
            'status_code' => $status,
            'data' => $data,
            'error' => $error,
            'errorCode' => $errorCode,
            'errorMessage' => $errorMessage
        ];
        if (isset($response['messages'])) {
            $responseArray['messages'] = $response['messages'];
        }


        $view = $this->view($responseArray, $status)->setFormat($this->responseFormat);

        $view->getSerializationContext()->setSerializeNull(true);

        return $view;
    }

    /**
     * @param \Exception $exception
     * @return \FOS\RestBundle\View\View
     */
    protected function getBadRequest($exception)
    {
        $data = [
            'status' => EntityRESTController::STATUS_CODE_BAD_REQUEST,
            'error' => [
                'code' => EntityRESTController::STATUS_CODE_BAD_REQUEST,
                'message' => $exception->getMessage()
            ]
        ];
        return $this->handleView($this->generateView($data));
    }

    /**
     * @param array $queryParams
     * @param array $builderResponseMethodName
     * @param $handlerResponseMethodName
     * @param bool $checkParams
     * @return mixed
     * @internal param ParamFetcher $paramFetcher
     * @internal param Request $request
     * @internal param array $builderResponseClassName
     * @internal param $handlerResponseClassName
     */
    protected function process(array $queryParams, $builderResponseMethodName, $handlerResponseMethodName, $checkParams = true)
    {
        $request = $this->container->get('request');
        $paramFetcher = $this->container->get('fos_rest.request.param_fetcher');
        $fetchedParams = [];

        if ($checkParams) {
            try {
                foreach ($queryParams as $param) {
                    $fetchedParams[] = $paramFetcher->get($param);
                }
            } catch (\Exception $e) {
                return $this->getBadRequest($e);
            }
        } else {
            foreach ($queryParams as $param) {
                $fetchedParams[] = $request->get($param);
            }
        }

        try {
            return
                $this->handleView(
                    $this->generateView(
                        call_user_func(
                            [$this->container->get($this->getResponseBuilderServiceName()), $builderResponseMethodName],
                            call_user_func_array([$this->container->get($this->getHandlerServiceName()), $handlerResponseMethodName], $fetchedParams)
                        )
                    )
                );
        } catch (\Exception $e) {
            return $this->handleView(
                $this->generateView(
                    [
                        'status' => $this->getCorrectHttpStatusCode($e),
                        'error' => [
                            'code' => $e->getCode(),
                            'message' => $e->getMessage()
                        ]
                    ]
                )
            );
        }
    }

    /**
     * @return array|bool
     */
    public function getOpen()
    {
        $switch = $this->container->get('app_core.web_state.service');
        $paramSettings = $switch->getParams();
        if(!$paramSettings->state) {
            $response = [
                "error" => false,
                "errorCode" => 203,
                "errorMessage" => null,
                "data" => $paramSettings->message
            ];
            return $response;
        }
        return false;
    }

    /**
     * @param \Exception $exception
     * @return mixed
     */
    private function getCorrectHttpStatusCode($exception)
    {
        if ($exception->getCode() < 200 || $exception->getCode() > 599) {
            return 500;
        }

        return $exception->getCode();
    }
}
