<?php
/**
 * @author: Stas <ssp@nxc.no>
 * @copyright Copyright (C) 2015 NXC AS.
 *
 * @date: 15.09.15
 */

namespace App\ApiBundle\Controller;

use App\ApiBundle\Model\Controller\EntityRESTController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations;

/**
 * Class OsProductController
 * @package App\ApiBundle\Controller
 */
class OsProductController extends EntityRESTController
{
    /**
     * @ApiDoc(
     *   section="Product",
     *   resource = true,
     *   description = "List of os products.",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @param ParamFetcher $paramFetcher
     * @param Request $request
     * @Annotations\QueryParam(name="query", default=null, strict=true, nullable=false, description="query", requirements=".{0,40}")
     *
     * @return mixed
     */
    public function getOsproductsAction(ParamFetcher $paramFetcher, Request $request)
    {
        return $this->process(
            ['query'], 'buildGetProductsResponse', 'getOsProductsByQuery'
        );
    }

    /**
     * @inheritDoc
     */
    public function getResponseBuilderServiceName()
    {
        return 'app_api.osproduct_response_builder.service';
    }

    /**
     * @inheritDoc
     */
    public function getHandlerServiceName()
    {
        return 'app_open_solution.product.handler';
    }
}