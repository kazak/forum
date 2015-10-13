<?php

namespace App\CoreBundle\Model\Handler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface EntityCrudHandlerInterface.
 */
interface EntityCrudHandlerInterface extends EntityHandlerInterface
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function processIndexAction(Request $request);

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function processCreateAction(Request $request);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function processShowAction(Request $request, $id);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function processUpdateAction(Request $request, $id);

    /**
     * @param Request $request
     * @param $id
     *
     * @return mixed
     */
    public function processDeleteAction(Request $request, $id);
}
