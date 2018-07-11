<?php
namespace backend\models;

use Yii;

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
        return '{{%post}}';
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
            'complaints' => 'Complaints',
        ];
    }

    /**
     * Returns sorted records where complaints column is larger than zero
     * @return \yii\db\ActiveQuery
     */
    public static function findComplaints()
    {
        return parent::find()->where('complaints > 0')->orderBy('complaints DESC');
    }

    /**
     * Gets full image path
     * @return mixed
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Rejects a complaint
     * @return bool
     */
    public function approve()
    {
        /* @var $redis \yii\redis\Connection */
        $redis = Yii::$app->redis;

        $key = "post:{$this->id}:complaints";

        $redis->del($key);

        $this->complaints = 0;

        return $this->save(false, ['complaints']);
    }

    /**
     * Deletes complaints and comments from deleted post
     * @return bool
     */
    public function deletePostKeys()
    {
        /* @var $redis \yii\redis\Connection */
        $redis = Yii::$app->redis;

        $key = "post:{$this->id}";

        $redis->del($key . ':complaints');
        $redis->del($key . ':comments');

        return true;
    }

    /**
     * Deletes deleted post's likes
     * @return bool
     */
    public function deletePostLikes()
    {
        /* @var $redis \yii\redis\Connection */
        $redis = Yii::$app->redis;

        $key = "post:{$this->id}:likes";

        if ($redis->scard($key)) {
            $redis->del($key);

            $this->deleteUsersLikes();
        }

        return true;
    }

    /**
     * Deletes users likes from deleted post
     * @return bool
     */
    private function deleteUsersLikes()
    {
        /* @var $redis \yii\redis\Connection */
        $redis = Yii::$app->redis;

        $keys = $redis->keys("user:*:likes");

        foreach ($keys as $value) {
            if ($redis->sismember("{$value}", $this->id)) {
                $redis->srem("{$value}", $this->id);
            }
        }

        return true;
    }
}
