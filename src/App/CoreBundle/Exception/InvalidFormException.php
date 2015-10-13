<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 18 06 2015
 */
namespace App\CoreBundle\Exception;

/**
 * Class InvalidFormException.
 */
class InvalidFormException extends \RuntimeException
{
    protected $form;

    /**
     * @param string $message
     * @param null   $form
     */
    public function __construct($message, $form = null)
    {
        parent::__construct($message);
        $this->form = $form;
    }

    /**
     * @return array|null
     */
    public function getForm()
    {
        return $this->form;
    }
}
