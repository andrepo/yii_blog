<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
* This is the model class for table "user".
*
* @property string $id
* @property string $first_name
* @property string $last_name
* @property string $email
* @property string $active
* @property string $password
* @property string $auth_key
*
* @property Article[] $articles
*/

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    
    public static function tableName()
    {
        return 'user';
    }
    
    public function rules()
    {
        return [
            [['active'], 'integer'],
            [['first_name', 'last_name', 'password', 'auth_key'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 150],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['email'], 'required'],
       ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'active' => 'Email validation complete',
            'password' => 'Password'
        ];
    }
    
    /**
    * @inheritdoc
    * @return \yii\db\ActiveQuery
    */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['user_id' => 'id']);
    }
    
    public function getFullname()
    {
        $fullname = $this->first_name . ' ' . $this->last_name;
        if ( trim($fullname) == '' ) {
            $fullname = $this->email;
        }
        return $fullname;
    }
    
    public function getProfileurl()
    {
        return Url::to(['user/view/']) . '/' . $this->id;
    }
    
    /**
    * 
    * Generates a unique URL that verifies the user owns the email provided upon registration
    * 
    * @inheritdoc
    * @return string
    */
    public function getValidationUrl()
    {
        return Url::to(['/'], true).'user/activate/'.$this->auth_key;
    }
    
    /**
    * 
    * Generates a unique URL that verifies the user owns the email and therefore can change his/her account's password
    * 
    * @inheritdoc
    * @return string
    */
    public function getResetPasswordUrl()
    {
        return Url::to(['/'], true).'user/reset-password/'.$this->auth_key;
    }
    
    
    #####  Security stuff #####
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    
    public static function findByUsername($username)
    {
        // Only active (user that has validated his/her email) can login
        return static::findOne(['email' => $username, 'active' => '1']);
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($auth_key)
    {
        return $this->auth_key === $auth_key;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        // Using hashed password for improved security:
        // http://www.yiiframework.com/doc-2.0/guide-security-passwords.
        
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
