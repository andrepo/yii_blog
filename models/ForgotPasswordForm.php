<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * ContactForm is the model behind the contact form.
 */
class ForgotPasswordForm extends Model
{
    public $email;
    public $verifyCode;
    private $foundUser = null;
    public $emailSent = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // flag to tell user the password reset email was sent
            ['emailSent', 'safe'],
            // email is required, of course
            ['email', 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // A user must exist on the database for this email
            ['email', 'validateEmailExists'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }
    
    /**
     *
     * Searches the DB after a user matching the input email
     *
    * @param string $attribute the attribute currently being validated
    * @param mixed $params the value of the "params" given in the rule
    */
    public function validateEmailExists($attribute, $params)
    {
        $no_user_found_msg = "We haven't found anyone with this email. Perhaps you <a href=\"".Url::to(['user/create'])."\">haven't registered yet</a>?";
        $this->foundUser = User::findOne( ['email' => $this->email] );
        if ( $this->foundUser === null ) {
            $this->addError('email', $no_user_found_msg);
        }
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Finds a user that matches provided email address.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function findUserByEmail()
    {
        if ($this->validate()) {
            $this->emailSent = true;
            return $this->foundUser;
        }
        return false;
    }
}