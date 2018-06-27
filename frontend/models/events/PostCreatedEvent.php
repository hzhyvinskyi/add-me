<?php
namespace frontend\models\events;

use frontend\models\Post;
use frontend\models\User;
use yii\base\Event;

class PostCreatedEvent extends Event
{
    /* @var User */
    public $user;

    /* @var Post */
    public $post;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost()
    {
        return $this->post;
    }
}
