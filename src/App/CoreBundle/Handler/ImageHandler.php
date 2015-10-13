<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 10 2015
 */
namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Image;
use Symfony\Component\Filesystem\Filesystem;
use App\CoreBundle\Model\Handler\EntityHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SeoHandler.
 */
class ImageHandler extends EntityHandler
{
    private $extensionPath = '';

    /**
     * @param $id
     *
     * @return null|Image
     */
    public function getEntity($id)
    {
        return parent::getEntity($id);
    }

    /**
     * @return Image
     */
    public function createEntity()
    {
        return parent::createEntity();
    }

    /**
     * @param Request $request
     * @param $handler
     * @param bool|false $withTranslation
     * @return array|null
     */
    public function getImagesFromRequest(Request $request, $handler, $withTranslation = false)
    {
        $post = $request->request->all()[$handler::FORM_TABLE_NAME];
        $files = $request->files->all()[$handler::FORM_TABLE_NAME];
        if (null !== $handler::EXTENSION_PATH_TO_FILES) {
            $this->extensionPath = $handler::EXTENSION_PATH_TO_FILES;
        }
        if ($files === null) {
            return null;
        }
        if ($withTranslation) {
            $images = [];
            foreach ($files['translations'] as $locale => $file) {
                if (isset($file)) {
                    $images[$locale] = $this->checkFilesAndCreateEntity($handler, $file, $post['translations'][$locale]);
                }
            }
        } else {
            $images = $this->checkFilesAndCreateEntity($handler, $files, $post);
        }
        return empty($images) ? null : $images;
    }

    /**
     * @param $handler
     * @param $files
     * @param $post
     * @return array
     */
    private function checkFilesAndCreateEntity($handler, $files, $post)
    {
        $existFile = false;
        $imagesById = [];
        $images = [];
        foreach ($handler::FORM_FIELD_IMG_NAMES as $ident) {
            if (isset($post{$ident}['id']) && strlen($post{$ident}['id']) > 0) {
                $imagesById[$ident] = $this->getEntity($post{$ident}['id']);

            }
            if (isset($files{$ident}) && !empty($files{$ident})) {
                $existFile = true;
            }
        }
        if ($files !== null && $existFile) {
            $images = $this->saveFilesAndCreateEntity($files);
        }
        if ($existFile || count($imagesById) > 0) {
            return array_merge($imagesById, $images);
        }
        return null;
    }

    /**
     * @param array $files
     * @return array
     */
    private function saveFilesAndCreateEntity($files)
    {
        $config = $this->container->getParameter('app_core.settings');
        $webDirectory = $this->container->get('kernel')->getRootDir() . '/../web';
        $entities = [];
        foreach ($files as $key => $img) {
            /**
             * @var UploadedFile $img
             */
            if ($img['file']) {
                $img = $img['file'];
                $imgFileSize = $img->getClientSize();
                $imgFileType = $img->getClientMimeType();
                $maxSize = UploadedFile::getMaxFilesize();
                $originalName = str_replace(' ', '_', $img->getClientOriginalName());
                $useName = time() . '-' . $originalName;
                $typeIsValid = in_array($imgFileType, $config['allowed_file_types_image']);
                if ($imgFileSize <= $maxSize && $imgFileSize != 0 && $typeIsValid) {
                    $pathToFile = $config['upload_dir'] . $this->extensionPath;
                    $img->move($webDirectory . $pathToFile, $useName);
                    $entities[$key] = $this->createEntityWithNamePath($originalName, $pathToFile . $useName);
                }
            }
        }
        return $entities;
    }

    /**
     * @param $name
     * @param $path
     * @return Image
     */
    private function createEntityWithNamePath($name, $path)
    {
        $entity = $this->findOne([
            'path' => $path
        ]);
        if ($entity !== null) {
            return $entity;
        }
        return $this->createEntity()->setPath($path)->setName($name);
    }

    /**
     * @param null|string $folder
     * @param null|int $limit
     * @param null|int $offset
     * @return mixed
     */
    public function getImagesByFolder($folder = null, $limit = null, $offset = null)
    {
        $uploadDir = $this->container->getParameter('app_core.settings')['upload_dir'];
        if ($folder === 'other') {
            return $this->repository->imageNotInFolder(
                $this->getImageFolders(),
                $uploadDir,
                $limit,
                $offset
            );
        }
        return $this->repository
            ->imagesByFolder(
                $uploadDir . $folder,
                $limit,
                $offset
            );
    }

    /**
     * @param null|string $folder
     * @return int
     */
    public function getImageCount($folder = null)
    {
        $uploadDir = $this->container->getParameter('app_core.settings')['upload_dir'];
        if ($folder === 'other') {
            return $this->repository->imageCountNotInFolder($this->getImageFolders(), $uploadDir);
        }
        $folder = $uploadDir . $folder;
        return $this->repository->imageCount($folder);
    }

    /**
     * @return array
     */
    public function getImageFolders()
    {
        return $this->repository->imageFolders();
    }

    /**
     * @param Request $request
     * @param int $id
     * @param bool $checkOnUsed
     * @return array
     */
    public function processDeleteAction(Request $request, $id, $checkOnUsed = true)
    {
        $shemaName = $this->container->getParameter('database_name');
        $image = $this->repository->find($id);
        if ($image) {
            if ($checkOnUsed) {
                $usedTables = $this->repository->getRelatedTableRows($shemaName);
                $usedCount = $this->repository->getUsedCount($usedTables, $id);
                if ($usedCount > 0) {
                    return $this->getResponse(null, 'Image', 403);
                }
            }
            $dir = $this->container->get('kernel')->getRootDir() . '/../web';
            $fileSystem = new Filesystem();
            if ($fileSystem->exists($dir . $image->getPath())) {
                $fileSystem->remove($dir . $image->getPath());
            }
            $this->objectManager->remove($image);
            $this->objectManager->flush();
            return $this->getResponse(null, null, 204);
        }
        return $this->getResponse(null, 'Image', 404);
    }
}
