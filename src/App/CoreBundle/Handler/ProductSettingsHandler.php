<?php
/**
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 03 10 2015
 */

namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\ProductVariantSettings;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use Doctrine\ORM;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductSettingsHandler
 * @package App\CoreBundle\Handler
 */
class ProductSettingsHandler extends EntityCrudHandler
{
    /**
     * @param Request $request
     * @param integer $id
     * @return array
     */
    public function processDeleteAction(Request $request, $id)
    {
        $setting = $this->objectManager
            ->getRepository('App\CoreBundle\Entity\ProductVariantSettings')
            ->find($id);
        if ($setting) {
            $this->objectManager->remove($setting);
            $this->objectManager->flush();
            return $this->getResponse(null, null, 204);
        }
        return $this->getResponse(null, 'Settings', 404);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function processEditAction(Request $request, $id)
    {
        $setting = $this->objectManager
            ->getRepository('App\CoreBundle\Entity\ProductVariantSettings')
            ->find($id);
        if ($setting) {
            $post = $request->request->all();
            if ($post != null && isset($post['data'])) {
                $vars = $post['data'];
                $this->normalizatorValues($vars['settings']);
                $setting->setName($vars['name']);
                $setting->setStorage($vars['settings']);
                $this->objectManager->persist($setting);
                $this->objectManager->flush();
                return $this->getResponse($setting);
            }
            return $this->getResponse($setting);
        }
        return $this->getResponse(null, 'Setting');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function processCreateAction(Request $request)
    {
        $post = $request->request->all();

        if ($post != null && isset($post['data'])) {
            $setting = new ProductVariantSettings();
            $vars = $post['data'];

            $this->normalizatorValues($vars['settings']);
            $setting->setName($vars['name']);
            $setting->setStorage($vars['settings']);
            $this->objectManager->persist($setting);
            $this->objectManager->flush();

            return $this->getResponse($setting);
        }
        return $this->getResponse(null, 'Setting');
    }

    /**
     * Change values to bool
     * @param $vars
     */
    private function normalizatorValues(&$vars)
    {
        foreach ($vars as &$val) {
            if (is_array($val)) {
                $this->normalizatorValues($val);
            }
            if ($val === 'true') {
                $val = true;
            }
            if ($val === 'false') {
                $val = false;
            }
        }
    }
}
