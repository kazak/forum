<?php
/**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 09 2015
 */

namespace App\ApiBundle\Model\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface CartResponseBuilderInterface.
 */
interface CartResponseBuilderInterface
{
    /**
     * @return array
     */
    public function buildGetCartResponse();

    /**
     * @return array
     */
    public function buildGetCartitemsResponse();

    /**
     * @param int $cartItemId
     * @return array
     * @internal param Request $request
     */
    public function buildGetCartitemResponse($cartItemId);

    /**
     * @return mixed
     */
    public function buildPostCartitemsAction();
}