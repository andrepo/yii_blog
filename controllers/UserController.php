<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\UserSearch;
use app\models\PasswordForm;
use app\models\ForgotPasswordForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the user will receive an account activation email.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();
        $account_created = false;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Send a link for the user to verify he owns the email account
            $account_created = $this->sendAccountValidationEmail($model);
        }
        
        return $this->render('create', [
            'model' => $model,
            'account_created' => $account_created,
        ]);
    }
    
    /**
     * Resends the account validation email
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionResend()
    {
        $model = new User();
        
        if ( ($user_post = Yii::$app->request->post('User')) && ($model = User::findOne(['email' => $user_post['email']])) ) {
            // Send a link for the user to verify he owns the email account
            $this->sendAccountValidationEmail($model);
            return $this->render('activate_account', [
                'model' => $model,
            ]);
        } else {
            return $this->render('send_activation_email', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Sends an email to the user with an email validation link.
     * @return mixed
     */
    protected function sendAccountValidationEmail($model)
    {
        return
            Yii::$app->mailer
                ->compose(
                    'user/email_validation', [ 'model'=>$model ]
                )
                // adminEmail is set under config/params.php
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($model->email)
                ->setSubject('Please validate your email address')
                ->send()
            ;
    }
    
    /**
     * Sends an email to the user with an link to set a new password.
     * @return mixed
     */
    protected function sendForgotPasswordEmail($model)
    {
        return
            Yii::$app->mailer
                ->compose(
                    'user/email_forgot_password', [ 'model'=>$model ]
                )
                // adminEmail is set under config/params.php
                ->setFrom(Yii::$app->params['adminEmail'])
                ->setTo($model->email)
                ->setSubject('Password reset request')
                ->send()
            ;
    }
    
    /**
     * Activates a newly created account via a validation link
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionActivate($token)
    {
        if ($user = User::findIdentityByAccessToken($token)) {
            \Yii::$app->getSession()->setFlash('validatedUserId', $user->id);
            return $this->redirect(['user/setpassword']);
        } else {
            throw new \yii\web\HttpException(403, 'The validation token is old or invalid.');
        }
        
    }
    
    /**
     * Get the user to set his account password after activation
     * If creation is successful, the browser will be login him/her in and send him/her to the home page.
     * @return mixed
     */
    public function actionSetpassword()
    {
        $model = new PasswordForm();
        $password_set = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $password_set = true;
        }
            
        return $this->render('set_password', [
            'model' => $model,
            'password_set' => $password_set,
        ]);
        
    }
    
    /**
     * Sends a validation email with a link to reset account password
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionForgotpassword()
    {
        $model = new ForgotPasswordForm();
        
        if ( $model->load(Yii::$app->request->post()) && ($user = $model->findUserByEmail()) !== false ) {
            // For security reasons, regenarates authorization token
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->save();
            // Send a link for the user to reset his password
            $this->sendForgotPasswordEmail($user);
        }
        
        return $this->render('forgot_password', [
            'model' => $model,
        ]);
    }
    
    /**
     * Validates reset password link and sends the user to the set password form
     * @return mixed
     */
    public function actionResetpassword($token)
    {
        if ($user = User::findIdentityByAccessToken($token)) {
            \Yii::$app->getSession()->setFlash('validatedUserId', $user->id);
            return $this->redirect(['user/setpassword']);
        } else {
            //return $this->redirect(['site/login']);
            throw new \yii\web\HttpException(403, 'The validation token is old or invalid.');
        }
        
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
