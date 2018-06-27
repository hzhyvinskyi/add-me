<?php
namespace frontend\modules\post\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\Post;
use frontend\models\User;
use Intervention\Image\ImageManager;
use frontend\models\events\PostCreatedEvent;

class PostForm extends Model
{
    const MAX_DESCRIPTION_LENGTH = 1000;
    const EVENT_POST_CREATED = 'post_created';

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
        $this->on(self::EVENT_POST_CREATED, [Yii::$app->feedService, 'addToFeeds']);
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

            if ($post->save(false)) {
                $event = new PostCreatedEvent();

                $event->user = $this->user;
                $event->post = $post;

                $this->trigger(self::EVENT_POST_CREATED, $event);

                return true;
            }

            return false;
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
