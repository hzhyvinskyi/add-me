<?php
namespace frontend\modules\user\models\forms;

use Yii;
use yii\base\Model;
use Intervention\Image\ImageManager;

class PictureForm extends Model
{
    public $picture;

    public function rules()
    {
        return [
            [['picture'], 'file',
                'extensions' => ['jpg', 'png'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }

    public function __construct()
    {
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

        $width = Yii::$app->params['profilePicture']['maxWidth'];
        $height = Yii::$app->params['profilePicture']['maxHeight'];

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
     * @return integer
     */
    public function getMaxFileSize()
    {
        return Yii::$app->params['maxFileSize'];
    }
}
