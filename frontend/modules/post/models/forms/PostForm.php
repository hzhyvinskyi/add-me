<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;

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
                'minSize' => $this->getMinFileSize(),
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

        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'resizePicture']);
    }

    /**
     * Resizes picture if necessary
     */
    public function resizePicture()
    {
        if ($this->picture->error) {
            return;
        }

        $width = Yii::$app->params['postPicture']['maxWidth'];
        $height = Yii::$app->params['postPicture']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $img = $manager->make($this->picture->tempName);

        $img->resize($width, $height, function($constraint) {
            // Preserve proportions
            $constraint->aspectRatio();

            // Prevent possible upsizing
            $constraint->upsize();
        })->save();
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

    /**
     * Gets min size of the uploaded file
     * @return mixed
     */
    private function getMinFileSize()
    {
        return Yii::$app->params['minFileSize'];
    }
}
