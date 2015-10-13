<?php

/**
 * @author:     pm <pm@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 15 06 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\RestaurantsPage;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Symfony\Component\Form\FormInterface;

/**
 * Class RestaurantsPageHandler.
 */
class RestaurantsPageHandler extends EntityCrudHandler
{
    /**
     * @param $id
     *
     * @return null|RestaurantsPage
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return RestaurantsPage
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param $entity
     * @return FormInterface
     */
    public function getUpdateForm($entity)
    {
        $form = $this->createForm($this->formName, $entity)
            ->add('title')
            ->add('body')
            ->add('mapCenterLatitude')
            ->add('mapCenterLongitude')
            ->add('searchFieldPlaceholder')
            ->add('searchButtonLabel')
            ->add('submit', 'submit', ['label' => 'Save']);

        return $form;
    }
}
