<?php
namespace frontend\models;

use Yii;
use yii\redis\Connection;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property string $filename
 * @property string $description
 * @property int $created_at
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Likes current post by given user
     * @param User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * Unlikes current post by given user
     * @param User $user
     */
    public function unlike(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->scard("post:{$this->getId()}:likes");
    }

    /**
     * Checks is currentUser liked this post
     * @param User $user
     * @return mixed
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }

    /**
     * @param $id
     * @return Post|null
     * @throws NotFoundHttpException
     */
    public static function getPostById($id)
    {
        if ($post = parent::findOne($id)) {
            return $post;
        }

        throw new NotFoundHttpException();
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets author of the post
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
