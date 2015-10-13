<?php
/**
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 27 08 2015
 */

namespace App\CoreBundle\Handler;

use App\CoreBundle\Entity\Product;
use App\CoreBundle\Form\ProductType;
use App\CoreBundle\Model\Entity\ProductInterface;
use App\CoreBundle\Model\Handler\EntityCrudHandler;
use App\CoreBundle\Model\Handler\ProductHandlerInterface;
use App\CoreBundle\Service\ProductRequestProcessor;
use Doctrine\ORM;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductHandler
 * @package App\CoreBundle\Handler
 */
class ProductHandler extends EntityCrudHandler implements ProductHandlerInterface
{
    const FORM_TABLE_NAME = 'app_product_type';
    const FORM_FIELD_IMG_NAMES = ['image'];
    const ROUTE_PREFIX_BO = 'app_back_office_product_';
    const EXTENSION_PATH_TO_FILES = 'product/';


    /**
     * @inheritDoc
     */
    public function processCreateAction(Request $request)
    {
        $locale = $this->container->getParameter('locale');
        $product = new Product();
        $product->setFallbackLocale($locale)
            ->setCurrentLocale($locale);
        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];
        $images = $imageHandler->getImagesFromRequest($request, $this, true);
        $form = $this->getCreateUpdateForm($product);
        if ($post !== null && $images !== null) {
            $form->submit($post);
            if ($form->isValid()) {
                $this->setImages($product, $images);

                $shopHandler = $this->container->get('app_core.shop.handler');
                $shopHandler->generateProductMasterVariant($product);

                $this->objectManager->persist($product);
                $this->objectManager->flush();
                return $this->redirectToRoute($this->getBORoute('index'));
            }
        }
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @param Product $entity
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    public function getCreateUpdateForm($entity)
    {
        $form = $this->container->get('form.factory')
            ->create(new ProductType($entity, $this->getCKEditorToolbarConfig()), $entity)
            ->add('submit', 'submit', ['label' => 'Save']);

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function processUpdateAction(Request $request, $id)
    {
        $product = $this->getEntity($id);
        if (!$product) {
            return $this->redirectToRoute($this->getBORoute('index'));
        }
        $form = $this->getCreateUpdateForm($product);
        $imageHandler = $this->container->get('app_core.image.handler');
        $post = $request->request->all()[self::FORM_TABLE_NAME];
        if ($post != null) {
            $image = $imageHandler->getImagesFromRequest($request, $this, true);
            $form->submit($post);
            if ($form->isValid()) {
                $this->setImages($product, $image);
                $this->objectManager->merge($product);
                $this->objectManager->flush();
                return $this->redirectToRoute($this->getBORoute('index'));
            }
        }

        return [
            'entity' => $product,
            'form' => $form->createView(),
            'os_products' => $this->getOSProducts(),
            'product_variant_settings' => $this->getProductVariantSettingsList()
        ];

    }

    /**
     * @param Product $product
     * @param array $images
     */
    protected function setImages(Product &$product, array $images = null)
    {
        foreach($images as $lokale => $image){
            $product->setImage(
                isset($images{$lokale}['image']) ? $images{$lokale}['image'] : null,
                $lokale
            );
        }
    }

    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function processOptionsDeleteAction(Request $request, $id)
    {
        $option = $this->getProductOption($id);

        if ($option) {
            $this->removeProductOption($option);
            return $this->getResponse(null, null, 204);
        }
        return $this->getResponse(null, 'Option', 404);
    }


    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function processOptionsEditAction(Request $request, $id)
    {
        $option = $this->getProductOption($id);
        if ($option) {
            $post = $request->request->all();
            if ($post != null && isset($post['data'])) {
                $option->setName($post['data']['name']);
                $this->objectManager->persist($option);
                $this->objectManager->flush();
                return $this->getResponse($option);
            }
            return $this->getResponse($option);
        }
        return $this->getResponse(null, 'Setting');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function processOptionsCreateAction(Request $request)
    {
        $post = $request->request->all();
        if ($post != null && isset($post['data'])) {
            $setting = new ProductVariantSettings();
            $setting->setName($post['data']['name']);
            $this->objectManager->persist($setting);
            $this->objectManager->flush();
            return $this->getResponse($setting);
        }
        return $this->getResponse(null, 'Setting');
    }

    /**
     * @return string
     */
    public function getBORoutePrefix()
    {
        return self::ROUTE_PREFIX_BO;
    }

    /**
     * @param string $routeIdentifier
     *
     * @return string
     */
    public function getBORoute($routeIdentifier)
    {
        return $this->getBORoutePrefix() . $routeIdentifier;
    }


    /**
     * @param $type
     * @return mixed
     */
    protected function getDistinctManager($type)
    {
        switch ($type) {
            case 'product_variant_settings':
                return null;
                break;
        }

        return $this->getSyliusManager($type);
    }

    /**
     * @param $type
     * @return mixed
     */
    protected function getDistinctRepository($type)
    {
        switch ($type) {
            case 'product_variant_settings':
                return null;
                break;
        }

        return $this->getSyliusRepository($type);
    }

    /**
     * @param $type
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getSyliusManager($type)
    {
        $syliusType = 'sylius.manager.' . $type;

        if (!$this->container->has($syliusType)) {
            throw new \InvalidArgumentException('Unrecognizable sylius manager type: ' . $syliusType);
        }

        return $this->container->get($syliusType);
    }

    /**
     * @param $type
     * @return mixed
     * @throws \InvalidArgumentException
     */
    protected function getSyliusRepository($type)
    {
        $syliusType = 'sylius.repository.' . $type;

        if (!$this->container->has($syliusType)) {
            throw new \InvalidArgumentException('Unrecognizable sylius manager type: ' . $syliusType);
        }
        return $this->container->get($syliusType);
    }



    /**
     * @return mixed
     */
    protected function getCKEditorToolbarConfig()
    {
        $boConfig = $this->container->getParameter('app_back_office_config');

        return $boConfig['appearance']['ckeditor']['toolbar'];
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct($filters)
    {
        if (is_int($filters) || is_string($filters)) {

            return $this->getDistinctRepository('product')->find((int)$filters);

        } elseif (is_array($filters)) {

            return $this->getDistinctRepository('product')->findOneBy($filters);

        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts(array $filters = [], array $sorting = [], $limit = 5, $offset = 0)
    {
        return $this->getDistinctRepository('product')->findBy($filters, $sorting, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function createProduct()
    {
        return $this->getDistinctRepository('product')->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function saveProduct($product)
    {
        $this->getDistinctManager('product')->persist($product);
        $this->getDistinctManager('product')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct($product)
    {
        $this->getDistinctManager('product')->remove($product);
        $this->getDistinctManager('product')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOption($filters)
    {
        if (is_int($filters) || is_string($filters)) {

            return $this->getDistinctRepository('product_option')->find((int)$filters);

        } elseif (is_array($filters)) {

            return $this->getDistinctRepository('product_option')->findOneBy($filters);

        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptions(array $filters = [], array $sorting = [], $limit = 10, $offset = 0)
    {
        return $this->getDistinctRepository('product_option')->findBy($filters, $sorting, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function createProductOption()
    {
        return $this->getDistinctRepository('product_option')->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function saveProductOption($option)
    {
        $this->getDistinctManager('product_option')->persist($option);
        $this->getDistinctManager('product_option')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductOption($option)
    {
        $this->getDistinctManager('product_option')->remove($option);
        $this->getDistinctManager('product_option')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptionValue($filters)
    {
        if (is_int($filters) || is_string($filters)) {

            return $this->getDistinctRepository('product_option_value')->find((int)$filters);

        } elseif (is_array($filters)) {

            return $this->getDistinctRepository('product_option_value')->findOneBy($filters);

        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductOptionValues(array $filters = [], array $sorting = [], $limit = 5, $offset = 0)
    {
        return $this->getDistinctRepository('product_option_value')->findBy($filters, $sorting, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function createProductOptionValue()
    {
        return $this->getDistinctRepository('product_option_value')->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function saveProductOptionValue($value)
    {
        $this->getDistinctManager('product_option_value')->persist($value);
        $this->getDistinctManager('product_option_value')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductOptionValue($value)
    {
        $this->getDistinctManager('product_option_value')->remove($value);
        $this->getDistinctManager('product_option_value')->flush();
    }

    /**
     * @return ProductVariant
     */
    public function getProductVariant($filters)
    {
        if (is_int($filters) || is_string($filters)) {

            return $this->getDistinctRepository('product_variant')->find((int)$filters);

        } elseif (is_array($filters)) {

            return $this->getDistinctRepository('product_variant')->findOneBy($filters);

        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductVariants(array $filters = [], array $sorting = [], $limit = 5, $offset = 0)
    {
        return $this->getDistinctRepository('product_variant')->findBy($filters, $sorting, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     */
    public function createProductVariant()
    {
        return $this->getDistinctRepository('product_variant')->createNew();
    }

    /**
     * {@inheritdoc}
     */
    public function saveProductVariant($variant)
    {
        $this->getDistinctManager('product_variant')->persist($variant);
        $this->getDistinctManager('product_variant')->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function removeProductVariant($variant)
    {
        $this->getDistinctManager('product_variant')->remove($variant);
        $this->getDistinctManager('product_variant')->flush();
    }

    /**
     * @return ProductRequestProcessor
     */
    public function getRequestProcessor()
    {

    }

    /**
     * @return array
     */
    private function getOSProducts()
    {
        return $this->getOSProductHandler()->getEntities();
    }

    /**
     * @return \App\CoreBundle\Entity\ProductVariantSettings[]|array
     */
    public function getProductVariantSettingsList()
    {
        return $this->objectManager
            ->getRepository('App\CoreBundle\Entity\ProductVariantSettings')
            ->findAll();
    }
}
