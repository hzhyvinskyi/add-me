<?php
namespace frontend\models;

use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'text' => 'Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @param $comment
     * @return mixed
     */
    public function deleteComment($comment)
    {
        return $comment->delete();
    }

    public static function getCommentsByPostId($id)
    {
        if ($comments = parent::find()->where(['post_id' => $id])
            ->orderBy(['id' => SORT_DESC])->all()) {
            return $comments;
        }

        return;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function getCommentById($id)
    {
        if ($comment = parent::findOne($id)) {
            return $comment;
        }

        throw new NotFoundHttpException();
    }

    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
