<?php
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;
    public $image_file_name;
    public $path = 'uploads/';
    // It's not recommended to keep original file's name as file names may be the same and files will be overwriten
    public $keep_filename = false;

    public function rules()
    {
        return [
            ['imageFile', 'required', 'on' => 'create'],
            ['imageFile', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }
    
    public function upload()
    {
        if ($this->validate() && $this->imageFile)
        {
            $filename = ( $this->keep_filename ) ? $this->imageFile->baseName : md5(uniqid(""));
            $this->image_file_name = $this->path . $filename . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($this->image_file_name);
            return true;
        } else {
            return false;
        }
    }
    
}