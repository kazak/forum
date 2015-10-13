<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 28.08.15
 */

namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use App\ApiBundle\Model\Controller\ResponseBuilderHandlerInterface;
use App\CoreBundle\Handler\CustomerHandler;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BusinessController
 * @package App\ApiBundle\Controller
 */
class BusinessController extends EntityRESTController implements ResponseBuilderHandlerInterface
{
    /**
     * @ApiDoc(
     *   section="Customer",
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Annotations\View()
     * @Annotations\QueryParam(name="query", default=null, strict=true, nullable=false, description="query", requirements="\d+")
     *
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @return array
     */
    public function getAction(ParamFetcher $paramFetcher, Request $request)
    {
        return $this->process(
            ['query'], 'buildGetBusinessResponse', 'searchCorporateCustomer'
        );
    }

    /**
     * @inheritDoc
     */
    public function getResponseBuilderServiceName()
    {
        return 'app_api.business_response_builder.service';
    }

    /**
     * @inheritDoc
     */
    public function getHandlerServiceName()
    {
        return 'app_core.customer.handler';
    }
}