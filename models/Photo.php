<?php

namespace app\models;

use Yii;
use yii\imagine\Image;
use Imagine\Gd;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;

/**
 * This is the model class for table "photo".
 *
 * @property integer $id
 * @property string $url
 *
 * @property Article[] $articles
 */
class Photo extends \yii\db\ActiveRecord
{
    public $thumbnail_path = 'thumbnail/';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'photo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string', 'max' => 255],
            // Cheating on the validation as we want relative path image URLs
            //[['url'], 'url'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
        ];
    }
    
    /**
     * Remove image files from filesystem
     * Useful for cleaning up the disk upon update / delete
     */
    public function deleteImgFiles()
    {
        $original_img_file = $this->url;
        // thumbnail
        $thumbnail_img_file = $this->_getThumbnailUrl($original_img_file);
        if ( file_exists(Yii::getAlias('@webroot') . $thumbnail_img_file) ) {
            unlink(Yii::getAlias('@webroot') . $thumbnail_img_file);
        }
        // poster
        $poster_img_file = $this->_getPosterUrl($original_img_file);
        if ( file_exists(Yii::getAlias('@webroot') . $poster_img_file) ) {
            unlink(Yii::getAlias('@webroot') . $poster_img_file);
        }
        // original
        if ( file_exists(Yii::getAlias('@webroot') . $original_img_file) ) {
            unlink(Yii::getAlias('@webroot') . $original_img_file);
        }
    }
    
    /**
     * Make sure we clean up files before we delete records from the DB
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete())
        {
            $this->deleteImgFiles();
            return true;
        } else {
            return false;
        }
        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['photo_id' => 'id']);
    }
    
    private function _getThumbnailUrl($original_img_file)
    {
        $img_extension = pathinfo($original_img_file, PATHINFO_EXTENSION);
        return str_replace('.'.$img_extension, '-thumbnail.'.$img_extension, $original_img_file);
    }
    
    private function _getPosterUrl($original_img_file)
    {
        $img_extension = pathinfo($original_img_file, PATHINFO_EXTENSION);
        //die($img_extension);
        return str_replace('.'.$img_extension, '-poster.'.$img_extension, $original_img_file);
    }
    
    public function getThumbnail()
    {
        $original_img_file = $this->url;
        $thumbnail_img_file = $this->_getThumbnailUrl($original_img_file);
        if ( file_exists(Yii::getAlias('@webroot') . $thumbnail_img_file) )
        {
            return Yii::$app->request->BaseUrl . $thumbnail_img_file;
        }
        
        Image::thumbnail(Yii::getAlias('@webroot') . $original_img_file, 255, 174)
            ->save(Yii::getAlias('@webroot') . $thumbnail_img_file, ['quality' => 90]);
            
        return Yii::$app->request->BaseUrl . $thumbnail_img_file;
    }
    
    public function getPoster()
    {
        $original_img_file = $this->url;
        $poster_img_file = $this->_getPosterUrl($original_img_file);
        if ( file_exists(Yii::getAlias('@webroot') . $poster_img_file) )
        {
            return Yii::$app->request->BaseUrl . $poster_img_file;
        }
        
        Image::thumbnail(Yii::getAlias('@webroot') . $original_img_file, 800, 600)
            ->save(Yii::getAlias('@webroot') . $poster_img_file, ['quality' => 90]);
        
        return Yii::$app->request->BaseUrl . $poster_img_file;
    }
}
