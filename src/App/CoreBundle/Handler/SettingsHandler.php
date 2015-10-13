<?php

/**
 * @author:     dss <dss@nxc.no>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 29 05 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Setting;
use App\CoreBundle\Model\Handler\EntityHandler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsHandler.
 */
class SettingsHandler extends EntityHandler
{
    const EXTENSION_PATH_TO_FILES = 'footer/';

    /**
     * @return Setting
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param $code
     *
     * @return null|resource
     */
    public function getParamsByCode($code)
    {
        $entity = $this->findEntity($code);

        return ($entity != null) ? $entity->getData() : null;
    }

    /**
     * @param $code
     *
     * @return array|null|object
     */
    protected function findEntity($code)
    {
        /*
         * @var Setting|Array<Setting> $entity
         */
        $entity = $this->findOne([
            'code' => $code,
        ]);

        if (!$entity || empty($entity)) {
            return [];
        }

        if (is_array($entity)) {
            $entity = $entity[0];
        }

        return $entity;
    }

    /**
     * @param $code
     * @param $param
     * @param $createIfNotExists
     */
    public function setParamsByCode($code, $param, $createIfNotExists = false)
    {
        $entity = $this->findEntity($code);

        if ($entity == null) {
            if ($createIfNotExists === true) {
                $entity = $this->createEntity()->setCode($code);
                $this->objectManager->persist($entity);
            } else {
                return;
            }
        }

        $entity->setData($param);
        $this->objectManager->merge($entity);
        $this->objectManager->flush();
    }

    /**
     * @param $code
     *
     * @return array|null|object
     */
    public function getByCode($code)
    {
        $entity = $this->getParamsByCode($code);
        return ($entity != null) ? json_decode(stream_get_contents($entity, -1, 0), true) : null;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function getStructuredFooterData(Request $request)
    {
        $post = $request->request->all();
        $files = $request->files->all();

        if (array_key_exists('footer_content', $files)) {
            $config = $this->container->getParameter('app_core.settings');
            $directory = $this->container->get('kernel')->getRootDir() . '/../web'
                . $config['upload_dir'] . self::EXTENSION_PATH_TO_FILES;
            foreach ($files['footer_content'] as $key => $uploadedFiles) {
                foreach ($uploadedFiles['links'] as $keyChild => $uploadedFile) {
                    $tempFile = $uploadedFile['href'];
                    $tempFileType = $tempFile->getClientMimeType();
                    $typeIsValid = in_array($tempFileType, $config['allowed_file_types']);
                    $tempFileSize = $tempFile->getClientSize();
                    $maxSize = UploadedFile::getMaxFilesize();
                    if ($tempFileSize <= $maxSize && $tempFileSize != 0 && $typeIsValid) {
                        $fileName = str_replace(' ', '_', $tempFile->getClientOriginalName());
                        $tempFile->move($directory, $fileName);
                        $post['footer_content'][$key]['links'][$keyChild]['href'] =
                            $config['upload_dir']
                            . self::EXTENSION_PATH_TO_FILES
                            . $fileName;
                    } else {
                        unset($post['footer_content'][$key]['links'][$keyChild]);
                    }
                }
            }
        }

        return $post;
    }

    /**
     * @param $fileName
     * @return bool
     */
    public function removeUploadFile($fileName)
    {
        $config = $this->container->getParameter('app_core.settings');
        $dir = $this->container->get('kernel')->getRootDir() . '/../web'
            . $config['upload_dir'];
        $fileSystem = new Filesystem();
        if ($fileSystem->exists($dir . $fileName)) {
            $fileSystem->remove($dir . $fileName);

            return true;
        } else {
            return false;
        }
    }

    /**
     * geting settings of restaurant out of stock
     * @param $id
     * @return array|null|object
     */
    public function getSettingsOOSMandatory($id)
    {
        $code = 'restaurant_'.$id.'_OOFM';
        $settings =  $this->getByCode($code);
        $mandProd = $this->getByCode('mandatory_out_of_stock_products');

        foreach($mandProd as $key=>$product){
            if(!isset($settings[$key])){
                $settings[$key] = ['status' => 1, 'datetime' => 'none'];
            }
            $settings[$key]['title'] = $product;
        }
        return $settings;
    }

    /**
     * @param $id
     * @return array|null|object
     */
    public function getSettingsOOSOptional($id)
    {
        $code = 'restaurant_'.$id.'_OOFO';
        $settings =  $this->getByCode($code);
        if(is_null($settings)){
            $settings['title'] = '';
            $settings['message'] = '';
        }
        return $settings;
    }

    /**
     * @param $request
     * @param $id
     * @return JsonResponse
     */
    public function setSettingsOOSMandatory($request, $id)
    {
        $post = $request->request->all();

        foreach($post['id'] as $idProduct){
            $data[$idProduct] = ['status' => $post['status_'.$idProduct], 'datetime' => $post['datetime_'.$idProduct]];
        }

        $code = 'restaurant_'.$id.'_OOFM';
        $data = json_encode($data);
        $this->setParamsByCode($code, $data, true);

        return new Response();
    }

    /**
     * @param $request
     * @param $id
     * @return Response
     */
    public function setSettingsOOSOptional($request, $id)
    {
        $post = $request->request->all();

        $code = 'restaurant_'.$id.'_OOFO';
        $data = json_encode($post);
        $this->setParamsByCode($code, $data, true);

        return new Response();
    }

    /**
     * @param $id
     * @return Response
     */
    public function setSettingsRemoveOptional($id)
    {
        $code = 'restaurant_'.$id.'_OOFO';
        $entity = $this->findEntity($code);

        $this->objectManager->remove($entity);
        $this->objectManager->flush();

        return new Response();
    }
}
