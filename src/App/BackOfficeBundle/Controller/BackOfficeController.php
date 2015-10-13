<?php

namespace App\BackOfficeBundle\Controller;

use App\CoreBundle\Model\Controller\EntityController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BackOfficeController
 * @package App\BackOfficeBundle\Controller
 */
class BackOfficeController extends EntityController
{
    /**
     * @var string
     */
    private $role = 'ROLE_FROM_SUPER_USER';

    /**
     * @return RedirectResponse|Response
     */
    public function startScreenSettingAction()
    {
        if (!$this->permission('ROLE_FROM_ADMIN')) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getUser();
        $service = $this->get('app_core.web_state.service');
        $paramSettings = $service->getParams();


        return $this->render('AppBackOfficeBundle:BackOffice:start_screen_settings.html.twig',
            ['user' => $user, 'params' => $paramSettings]);

    }

    /**
     * @return RedirectResponse|Response
     */
    public function startScreenAction()
    {
        if (!$this->permission('ROLE_FROM_ALL_ADMIN')) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getUser();

        return $this->render('AppBackOfficeBundle:BackOffice:start_screen.html.twig',
            ['user' => $user]);

    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function updateWebStatusAction(Request $request)
    {
        if (!$this->permission('ROLE_FROM_ADMIN')) {
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getUser();

        $service = $this->get('app_core.web_state.service');
        $paramSettings = $service->updateStatus($request);

        return $this->render('AppBackOfficeBundle:BackOffice:start_screen_settings.html.twig',
            ['user' => $user, 'params' => $paramSettings]);


    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function footerAction(Request $request)
    {
        if (!$this->permission('ROLE_FROM_MARKET')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $settingsHandler = $this->get('app_core.settings.handler');
        $data = $settingsHandler->getByCode('footer_content');

        if ($this->processFooterPost($request, $settingsHandler)) {
            return $this->redirectToRoute('app_back_office_footer');
        }

        return ['data' => $data];
    }

    /**
     * @param Request $request
     * @param mixed $settingsHandler
     *
     * @return bool
     */
    public function processFooterPost(Request $request, $settingsHandler)
    {

        $post = $settingsHandler->getStructuredFooterData($request);

        if (!$post || !isset($post['footer_content'])) {
            return false;
        }

        $data = $post['footer_content'];

        $settingsHandler->setParamsByCode('footer_content', json_encode($data));

        return true;

    }

    /**
     * @param $fileName
     *
     * @return bool
     */
    public function footerRemoveFileAction($fileName)
    {
        if (!$this->permission('ROLE_FROM_MARKET')) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        $settingsHandler = $this->get('app_core.settings.handler');
        $response = new JsonResponse([
            'deleted' => $settingsHandler->removeUploadFile($fileName),
            'data' => $fileName,]);

        return $response;
    }

    /**
     * @param Request $request
     * @return array|mixed|RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::indexAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     *
     * @return array|mixed|RedirectResponse
     */
    public function createAction(Request $request)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::createAction($request);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function showAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::showAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function updateAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::updateAction($request, $id);
    }

    /**
     * @Template()
     *
     * @param Request $request
     * @param $id
     *
     * @return array|mixed|RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        return parent::deleteAction($request, $id);

    }

    /**
     * @param Request $request
     * @param int $page
     *
     * @return RedirectResponse|Response
     */
    public function imagesAction(Request $request, $page)
    {
        $countPerPage = 20;

        $post = $request->request->all();
        if (isset($post['folder']) && strlen($post['folder']) > 1) {
            $folder = $post['folder'];
        } else {
            $folder = null;
        }

        $imageHandler = $this->get('app_core.image.handler');
        $offset = ($page - 1) * $countPerPage;
        $folders = $imageHandler->getImageFolders();
        if (!empty($folders) && $folder === null) {
            $folder = $folders[0];
        }
        $imageCount = $imageHandler->getImageCount($folder);
        $images = $imageHandler->getImagesByFolder($folder, $countPerPage, $offset);
        $pageCount = ceil($imageCount / $countPerPage);

        return $this->render('AppBackOfficeBundle:BackOffice:image_list.html.twig',
            [
                'images' => $images,
                'pageCount' => $pageCount,
                'page' => $page,
                'currentFolder' => $folder,
                'folders' => $folders
            ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse
     */
    public function deleteImageAction(Request $request, $id)
    {
        if (!$this->permission($this->role)) {
            return $this->redirectToLogin();
        }
        $post = $request->request->all();
        if (isset($post['checkOnUsed'])) {
            $checkOnUsed = $post['checkOnUsed'] == 'false' ? false : true;
        } else {
            $checkOnUsed = true;
        }
        $imageHandler = $this->get('app_core.image.handler');
        $data = $imageHandler->processDeleteAction($request, $id, $checkOnUsed);
        return new JsonResponse($data);
    }

}
