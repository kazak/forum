<?php
namespace ApiBundle\Controller;

use \AppBundle\Component\Model;
use \AppBundle\Component\Account;
use CoreBundle\Entity\Region;
use \RingCentral\Component\Entity\PhoneUser;
use RingCentral\Component\Soap\Jedi\Exception\CustomErrorException;
use \RingCentral\Component\Soap\Jedi\Exception\TrialUnavailableException;
use \RingCentral\Component\Validator\PhoneValidator;
use \RingCentral\Exception\HandleableException;
use \RingCentral\Component\Soap\Jedi\Jedi;
use \RingCentral\Exception\UserDisplayableException;
use \Symfony\Bundle\FrameworkBundle\Controller\Controller;
use \Symfony\Component\HttpFoundation\Cookie;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\Session\Session;
use \FOS\RestBundle\Controller\Annotations\RouteResource;
use \FOS\RestBundle\Controller\Annotations;
use \Nelmio\ApiDocBundle\Annotation\ApiDoc;
use \RingCentral\Component\Adapter\FA;
use \RingCentral\Component\Soap\ServiceResponse;
use \RingCentral\Component\Entity\Plan;
use \RingCentral\Component\Manager\PlanManager;
use \RingCentral\Component\Manager\StepManager;
use \RingCentral\Exception\BrokenFlowException;

/**
 * Class IndexController
 * @package ApiBundle\Controller
 * @RouteResource("Index")
 */
class IndexController extends Controller
{
    /**
     * Get cities.
     *
     * @ApiDoc(
     *  resource=true,
     *  section="City",
     *  description="get Cities.",
     *  statusCodes={
     *      200 = "Ok",
     *      400 = "Bad format",
     *      403 = "Forbidden"
     *  }
     *)
     *
     * @Annotations\Get("/cities/{regionId}", defaults={"regionId" = 1})
     * @param Request   $request    Request
     *
     * @return Response
     * @throws \Exception
     */
    public function getCitiesAction(Request $request, $regionId)
    {
        /** @var Region $region */
        $region = $this->container->get('region.handler')->getEntity($regionId);

        return $region->getCityes();
    }

}

