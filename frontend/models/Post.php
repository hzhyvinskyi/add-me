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
 * @property int $complaints
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
     * @return null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function getPostById($id)
    {
        $post = parent::findOne($id);

        if ($post) {
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
     * Finds current user's posts if they exists
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findCurrentUserPosts($id)
    {
        return parent::find()->where(['user_id' => $id])->orderBy(['id' => SORT_DESC])->all();
    }

    /**
     * Increases comment count in current post
     * @param $post_id
     * @return mixed
     */
    public static function incrPostCommentCount($post_id)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->incr("post:{$post_id}:comments");
    }

    /**
     * Decreases comment count in current post
     * @param $post_id
     * @return mixed
     */
    public static function decrPostCommentCount($post_id)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        return $redis->decr("post:{$post_id}:comments");
    }

    /**
     * Adds complaint to post given user
     * @param User $user
     * @return bool
     */
    public function complain(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        $key = "post:{$this->getId()}:complaints";

        if (!$redis->sismember($key, $user->getId())) {
            $redis->sadd($key, $user->getId());

            $this->complaints++;

            return $this->save(false, ['complaints']);
        }
    }

    /**
     * Checks is post already reported by given user
     * @param User $user
     * @return mixed
     */
    public function isReported(User $user)
    {
        /* @var $redis \yii\redis\Connection */
        $redis = Yii::$app->redis;

        return $redis->sismember("post:{$this->getId()}:complaints", $user->getId());
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
