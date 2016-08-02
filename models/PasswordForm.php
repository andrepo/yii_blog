<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * PasswordForm is the model behind the password form.
 */
class PasswordForm extends Model
{
    public $password;
    public $password_confirm;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['password', 'password_confirm'], 'required'],
            [['password', 'password_confirm'], 'string', 'min' => 6],
            [['password', 'password_confirm'], 'string', 'max' => 100],
            ['password_confirm', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Password',
            'password_confirm' => 'Confirm Password',
        ];
    }

    /**
     * Saves the chosen password on the Database.
     * @return boolean whether the model passes validation
     */
    public function save()
    {
        $user_id = Yii::$app->session->getFlash('validatedUserId');
        if ($this->validate() && ( $user = User::findOne($user_id)) !== null ) {
            // Hashing user password before saving it to the DB
            // http://www.yiiframework.com/doc-2.0/guide-security-passwords.html
            $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            $user->active = '1';
             // Generates a new auth key to avoid the previous one from being used
            $user->auth_key = Yii::$app->security->generateRandomString();
            // Save DB record
            return $user->save();
        }
        return false;
    }
}
