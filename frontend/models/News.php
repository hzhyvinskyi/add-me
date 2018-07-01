<?php
namespace frontend\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $title
 * @property string $short_content
 * @property string $content
 * @property string $picture
 * @property string $author
 * @property int $created_at
 * @property int $status
 */
class News extends \yii\db\ActiveRecord
{
    const DEFAULT_IMAGE = '/img/No-Photo-Available-For-News-Item.jpg';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['short_content', 'content'], 'string'],
            [['picture', 'created_at'], 'required'],
            [['created_at', 'status'], 'integer'],
            [['title', 'picture'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'short_content' => 'Short Content',
            'content' => 'Content',
            'picture' => 'Picture',
            'author' => 'Author',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets list of latest news
     * @param null $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getNewsList($limit = null)
    {
        if ($limit) {
            $limit = (int)$limit;
        }

        return parent::find()->orderBy(['created_at' => SORT_DESC])->limit($limit)->all();
    }

    /**
     * Finds news item by id
     * @param $id
     * @return null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public static function findNewsItem($id)
    {
        $item = parent::findOne($id);

        if ($item) {
            return $item;
        }

        throw new NotFoundHttpException();
    }

    /**
     * Gets news item picture
     * @return string
     */
    public function getPicture()
    {
        if ($this->picture) {
            return Yii::$app->storage->getFile($this->picture);
        }

        return self::DEFAULT_IMAGE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
