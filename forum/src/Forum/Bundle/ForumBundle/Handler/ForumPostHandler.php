<?php
/**
 * Created by PhpStorm.
 * User: dss
 * Date: 31.03.16
 * Time: 15:10
 */

namespace Forum\Bundle\ForumBundle\Handler;

use AppBundle\Model\Handler\EntityHandler;
use Forum\Bundle\ForumBundle\Entity\ForumPost;

/**
 * Class ForumPostHandler
 * @package Forum\Bundle\ForumBundle\Handler
 */
class ForumPostHandler extends EntityHandler
{
    /**
     * @return ForumPost
     */
    public function createEntity()
    {
        /** @var ForumPost $forumPost */
        $forumPost  = parent::createEntity();
        $user       = $this->container->get('security.token_storage')->getToken()->getUser();

        $forumPost->setOwner($user);

        return $forumPost;
    }
}