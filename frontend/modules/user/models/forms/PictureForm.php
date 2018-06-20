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
                'extensions' => ['jpg'],
                'checkExtensionByMimeType' => true,
                'maxSize' => $this->getMaxFileSize(),
            ],
        ];
    }

    public function __construct()
    {
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'pictureResize']);
    }

    /**
     * Resizes picture if necessary
     */
    public function pictureResize()
    {
        if ($this->picture->error) {
            return;
        }

        $width = Yii::$app->params['profilePictureParams']['maxWidth'];
        $height = Yii::$app->params['profilePictureParams']['maxHeight'];

        $manager = new ImageManager(['driver' => 'imagick']);

        $img = $manager->make($this->picture->tempName);

        $img->resize($width, $height, function ($constraint) {
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
