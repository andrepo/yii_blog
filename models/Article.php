<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "article".
 *
 * @property string $id
 * @property string $title
 * @property string $photo_id
 * @property string $news
 * @property string $datetime_published
 * @property string $user_id
 *
 * @property Photo $photo
 * @property User $user
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['photo_id'], 'integer'],
            [['title','news', 'photo_id'], 'required'],
            [['news'], 'string'],
            [['title'], 'string', 'max' => 200],
            // Sanitizes user input
            [['news','title'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'photo_id' => 'Photo',
            'news' => 'News',
            'datetime_published' => 'Published On',
            'user_id' => 'User ID',
        ];
    }
    
    public function getUrl()
    {
        //return Url::to(['article/view/']) . '/' . $this->id . '/' . $this->title;
        $params = [
            'article/view',
            'id' => $this->id,
            'title' => strtolower(preg_replace('(\s+([\-]\s)?)', '-', $this->title))
        ];
        return \Yii::$app->getUrlManager()->createUrl($params);
    }
    
    public function getExcerpt()
    {
        return StringHelper::truncateWords(strip_tags($this->news), 70) . ' ...';
    }
    
    public function getPrettydatetimepublished()
    {
        return date_format(date_create($this->datetime_published), 'd/m/Y H:i:s');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhoto()
    {
        return $this->hasOne(Photo::className(), ['id' => 'photo_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
