<?php
namespace frontend\components;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\base\Component;

/**
 * File storage component
 *
 * @package frontend\components
 */
class Storage extends Component implements StorageInterface
{
    private $fileName;

    /**
     * Saves given UploadedFile instance to disk
     * @param UploadedFile $file
     * @return mixed
     */
    public function saveUploadedFile(UploadedFile $file)
    {
        $path = $this->prepareFile($file);

        if ($path && $file->saveAs($path)) {
            return $this->fileName;
        }
    }

    /**
     * Prepares path to save uploaded file
     * @param UploadedFile $file
     * @return string
     * @throws \yii\base\Exception
     */
    protected function prepareFile(UploadedFile $file)
    {
        $this->fileName = $this->getFileName($file);

        $path = $this->getStoragePath() . $this->fileName;

        $path = FileHelper::normalizePath($path);

        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }

    /**
     * Generates file name and upload directories via checksum
     * @param UploadedFile $file
     * @return string
     */
    protected function getFileName(UploadedFile $file)
    {
        $hash = sha1_file($file->tempName);

        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0);

        return $name . '.' . $file->extension;
    }

    /**
     * Gets full path to file
     * @return bool|string
     */
    protected function getStoragePath()
    {
        return Yii::getAlias(Yii::$app->params['storagePath']);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getFile(string $filename)
    {
        return Yii::$app->params['storageUri'] . $filename;
    }
}
