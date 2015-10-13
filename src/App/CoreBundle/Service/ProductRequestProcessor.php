<?php
/**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 09 09 2015
 */

namespace App\CoreBundle\Service;

use App\CoreBundle\Entity\ProductVariant;
use App\CoreBundle\Entity\ProductVariantSettings;
use App\CoreBundle\Handler\ProductHandler;
use App\CoreBundle\Handler\ShopHandler;
use App\CoreBundle\Model\Handler\ProductHandlerInterface;
use App\CoreBundle\Model\Service\ProductRequestProcessorInterface;
use Doctrine\ORM;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\Request;

class ProductRequestProcessor implements ProductRequestProcessorInterface
{
    use ContainerAwareTrait;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container);
    }

    /**
     * @return ShopHandler
     */
    protected function getHandler()
    {
        return $this->container->get('app_core.shop.handler');
    }

    /**
     * {@inheritdoc}
     */
    protected function getResponseBuilder()
    {
        return $this->container->get('app_api.product_response_builder.service');
    }

    /**
     * {@inheritdoc}
     */
    public function processGetProductsAction(Request $request)
    {

        $parameters = $request->request->all();
        $responseBuilder = $this->getResponseBuilder();

        $response = $responseBuilder->buildGetProductsResponse($parameters);

        return $response;
    }

    /**
     * @param Request $request
     * @param $variantId
     * @return array
     */
    public function processSplitProduct($variantId)
    {
        $variant = $this->getHandler()->getProductVariant($variantId);

        $halfSplitAllowed = $variant->getSetting('half_split_allowed');
        if(!$halfSplitAllowed){
            return [
                'status' => 400,
                'error' => true,
                'errorCode' => 400,
                'errorMessage' => 'can\'t split pizza '
            ];
        }
        $variantsProducts = $this->getHandler()->getProductVariants([
            'defaultSettings' => $variant->getDefaultSettings(),
            'enabled' => true ], [], null);

        $data = [];

        foreach($variantsProducts as $variantProduct) {
            $data[] = [
                "os_product_id" => $variantProduct->getOSProduct()->getId(),
                "description" =>  $variantProduct->getSetting('description'),
                "name"=> $variantProduct->getName(),
                "image"=> $variantProduct->getProduct()->getImage()->getPath(),
                "price"=> $variantProduct->getPrice()

            ];
        }

        return [
            'status' => 200,
            'error' => false,
            'errorCode' => null,
            'data' => $data
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function processPostProductsAction(Request $request)
    {
        $post = $request->request->all();

        if (!isset($post['name'])) {
            throw new \InvalidArgumentException('Missing product name input');
        }

        $product = $this->getHandler()->generateProduct($post);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function processGetProductAction(Request $request, $productId)
    {

        $productId = (int)$productId;
        $responseBuilder = $this->getResponseBuilder();

        try {
            $response = $responseBuilder->buildGetProductResponse($productId);
        } catch (\Exception $e) {
            return [
                'status' => 404,
                'error' => true,
                'errorCode' => 404,
                'errorMessage' => $e->getMessage()
            ];
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function processPostProductAction(Request $request, $productId)
    {
        $product = $this->getHandler()->getProduct($productId);

        if (!$product) {
            throw new ORM\EntityNotFoundException('Product #' . $productId . ' not found');
        }

        // TODO: handle request data and actually update product

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function processGetProductVariantsAction(Request $request, $productId)
    {

        $responseBuilder = $this->getResponseBuilder();

        $response = $responseBuilder->buildGetProductVariantsResponse($productId);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function processPostProductVariantsAction(Request $request, $productId)
    {
        $responseBuilder = $this->getResponseBuilder();

        $response = $responseBuilder->getResponseBase();

        $product = $this->getHandler()->getProduct($productId);

        if (!$product) {
            $response['status'] = 404;
            $response['error']['message'] = 'Product #' . $productId . ' not found';
            return $response;
        }

        $payload = $request->request->all();

        $variant = $this->getHandler()->generateProductVariant($product, $payload);

        if (!$variant) {
            $response['status'] = 500;
            $response['error']['message'] = 'Can not create variant for product #' . $productId;
            return $response;
        }

        $response = $responseBuilder->buildGetProductVariantResponse($variant->getId());

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function processGetProductVariantAction(Request $request, $variantId)
    {

        $responseBuilder = $this->getResponseBuilder();

        $response = $responseBuilder->buildGetProductVariantResponse($variantId);

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function processPostProductVariantAction(Request $request, $productId, $variantId)
    {
        $responseBuilder = $this->getResponseBuilder();

        $response = $responseBuilder->getResponseBase();

        $variant = $this->getHandler()->getProductVariant($variantId);

        try {
            $this->saveOSProductToVariant($request, $variant);
            $this->saveDefaultSettingsToVariant($request, $variant);
            $this->saveProductVariantSettings($request, $variant);
        } catch (\Exception $e) {
            $response['status'] = 404;
            $response['error']['message'] = $e->getMessage();
            $response['error']['code'] = 404;
            return $response;
        }

        $this->getHandler()->saveProductVariant($variant);

        if (!$variant) {
            $response['status'] = 500;
            $response['error']['message'] = 'Can not create variant for product #' . $productId;
            return $response;
        }

        $response = $responseBuilder->buildGetProductVariantResponse($variantId);

        return $response;
    }

    /**
     * @inheritDoc
     */
    public function processDeleteProductVariantAction($variantId, $productId)
    {
        $product = $this->getHandler()->getProduct($productId);

        if (!$product) {
            $error = 'Product #' . $productId . ' not found';
            throw new \Exception($error, 404);
        }

        $variant = $this->getHandler()->getProductVariant(
            [
                'object' => $product,
                'id' => $variantId
            ]
        );

        if (!$variant) {
            $error = 'Variant #'.$variantId.' does not belong to product #'.$productId;
            throw new \Exception($error, 404);
        }

        $this->getHandler()->removeProductVariant($variant);
    }

    /**
     * @param Request $request
     * @param ProductVariant $variant
     */
    private function saveProductVariantSettings(Request $request, $variant)
    {
        $requestSettings = $request->get('settings');
        if($requestSettings === null){
            $variant->setSettings(null);
            return;
        }
        foreach(array_keys($requestSettings) as $newSettingKey) {
            $variant->setSetting($newSettingKey, $requestSettings[$newSettingKey]);
        }
    }

    /**
     * @param Request $request
     * @param ProductVariant $variant
     * @throws \Exception
     */
    private function saveOSProductToVariant(Request $request, ProductVariant $variant)
    {
        $id = $request->get('os_product_id');
        $osProduct = $this->container->get('doctrine')
                          ->getRepository('AppOpenSolutionBundle:OSProduct')
                          ->find($id);

        if (!$osProduct) {
            throw new \Exception('OSProduct #'.$id.' is not found', 404);
        }

        $variant->setOSProduct($osProduct);
    }

    /**
     * @param Request $request
     * @param ProductVariant $variant
     * @throws \Exception
     */
    private function saveDefaultSettingsToVariant(Request $request, ProductVariant $variant)
    {
        $id = $request->get('settings_template')['id'];
        $setting = $this->container->get('doctrine')
                        ->getRepository('AppCoreBundle:ProductVariantSettings')
                        ->find($id);

        if (!$setting) {
            throw new \Exception('Setting #'.$id.' is not found', 404);
        }

        $variant->setDefaultSettings($setting);
    }
} 