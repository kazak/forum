<?php

namespace App\CoreBundle\Model\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface EntityControllerInterface.
 */
interface EntityControllerInterface
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request);

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createAction(Request $request);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function showAction(Request $request, $id);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function updateAction(Request $request, $id);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function deleteAction(Request $request, $id);

    public function getHandler();

    public function checkUser();

    public function redirectToLogin();
}
