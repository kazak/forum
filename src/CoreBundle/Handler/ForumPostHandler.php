<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 08.06.16
 * Time: 23:35
 */

namespace CoreBundle\Handler;

use CoreBundle\Entity\ForumPost;
use CoreBundle\Model\Handler\EntityHandler;

/**
 * Class ForumPostHandler
 * @package CoreBundle\Handler
 */
class ForumPostHandler extends EntityHandler
{
    /**
     * @return ForumPost
     */
    public function createEntity()
    {
        /** @var ForumPost $forumPost */
        $forumPost = parent::createEntity();

        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        $forumPost->setOwner($user);

        return $forumPost;
    }
}