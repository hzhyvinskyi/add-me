<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;

class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGTH = 1000;

    public $picture;
    public $description;
    private $user;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'skipOnEmpty' => false,
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
            [['description'], 'string', 'max' => self::MAX_DESCRIPTION_LENGTH],
        ];
    }

    /**
     * PostForm constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $post = new Post();

            $post->user_id = $this->user->getId();
            $post->filename = Yii::$app->storage->saveUploadedFile($this->picture);
            $post->description = $this->description;
            $post->created_at = time();

            return $post->save(false);
        }
    }

    /**
     * Gets max size of the uploaded file
     * @return mixed
     */
    private function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}
