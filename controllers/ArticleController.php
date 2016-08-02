<?php

namespace app\controllers;

use Yii;
use app\models\Article;
use app\models\ArticleSearch;
use app\models\User;
/* Photo upload - Start **/
use app\models\Photo;
use app\models\UploadForm;
use yii\web\UploadedFile;
/* Photo upload - End **/
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use mPDF;
use yii\helpers\StringHelper;
// RSS feed
use yii\feed\FeedDriver;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['create','update'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],  
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    /*
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */
    
    /**
     * Lists logged in user Article models.
     * @return mixed
     */
    public function actionUserarticles($user_id)
    {
        if (($user = User::findOne($user_id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(['ArticleSearch' => ['user_id' => $user_id]]);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customTitle' => 'Articles by ' . $user->fullname,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $links = [
            'export' => Url::to(['article/export']).'/'.$id,
        ];
        return $this->render('view', [
            'model' => $this->findModel($id),
            'links' => $links,
        ]);
    }
    
    /**
     * Displays a single Article in PDF format.
     * @param string $id
     * @return boolean
     */
    public function actionExport($id)
    {
        $mpdf = new mPDF();
        $mpdf->WriteHTML(
            $this->render('view', [
                'model' => $this->findModel($id),
                'links' => $links,
                'noBreadCrumbs' => true, 
            ])
        );
        $mpdf->Output();
        return true;
    }

    public function actionRss()
    {
        // Only latest 10 posts
        $dataProvider = new ActiveDataProvider([
            'query' => Article::find()->orderBy('datetime_published DESC')->limit(10),
            'pagination' => false,
        ]);
        
        echo \Zelenin\yii\extensions\Rss\RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'title' => 'Crossover News',
                'link' => Url::toRoute('/', true),
                'description' => 'Articles',
                'language' => function ($widget, \Zelenin\Feed $feed) {
                    return Yii::$app->language;
                },
                'image'=> function ($widget, \Zelenin\Feed $feed) {
                    $feed->addChannelImage(Url::toRoute('/', true).'img/xo-logo-white.png', Url::toRoute('/', true), 183, 50, 'Crossover News');
                },
            ],
            'items' => [
                'title' => function ($model, $widget) {
                        return $model->title;
                    },
                'description' => function ($model, $widget) {
                        return StringHelper::truncateWords($model->excerpt, 50);
                    },
                'link' => function ($model, $widget) {
                        return Url::toRoute([$model->url], true);
                    },
                /*
                'author' => function ($model, $widget) {
                        return $model->user->email . ' (' . $model->user->fullname . ')';
                    },
                'guid' => function ($model, $widget) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->datetime_published);
                        return Url::toRoute([$model->url], true) . ' ' . $date->format(DATE_RSS);
                    },
                */
                'pubDate' => function ($model, $widget) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->datetime_published);
                        return $date->format(DATE_RSS);
                    }
            ]
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // Make sure user is logged in
        if ( Yii::$app->user->isGuest ) {
            return $this->redirect(['site/login']);
        }
        
        $model = new Article();
        // Make photo upload mandatory
        $upload_model = new UploadForm(['scenario' => 'create']);

        if ( $post_data = Yii::$app->request->post() )
        {
            // Loads post data onto parent model
            $model->load($post_data);
            
            // A photo is mandatory when creating a new article
            $upload_model->imageFile = UploadedFile::getInstance($upload_model, 'imageFile');
            if ( $upload_model->upload() )
            {
                // Save uploaded photo url
                $photo = new Photo();
                $photo->url = '/' . $upload_model->image_file_name;
                if ( $photo->save() == false )
                {
                    throw new \yii\web\HttpException(500, 'Error saving uploaded image:' . implode(' | ', $photo->getErrors('url')) );
                }
                $model->photo_id = $photo->id;
            
                $model->user_id = Yii::$app->user->identity->id;
                $model->datetime_published = date("Y-m-d H:i:s");
            
                // Save parent data
                if ( $model->save() ) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
            
            // Something went wrong ...
            return $this->render('create', [
                'model' => $model,
                'upload_model' => $upload_model
            ]);
        
        } else {
            return $this->render('create', [
                'model' => $model,
                'upload_model' => $upload_model
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    
    public function actionUpdate($id)
    {
        // Make sure user is logged in
        if ( Yii::$app->user->isGuest ) {
            return $this->redirect(['site/login']);
        }
        
        $model = $this->findModel($id);
        $upload_model = new UploadForm();

        if ( $post_data = Yii::$app->request->post() )
        {
            // Loads post data onto parent model
            $model->load($post_data);
            
            // Checks if a photo has been uploaded
            $upload_model->imageFile = UploadedFile::getInstance($upload_model, 'imageFile');
            if ( $upload_model->upload() )
            {
                // Save uploaded photo url
                $photo = new Photo();
                // If a photo was previously uploaded, load it up so we can overwrite it
                if ( $model->photo !== null && ($photo_model = Photo::findOne($model->photo->id)) !== null )
                {
                    // Delete previously save image files to save up disk
                    $photo_model->deleteImgFiles();
                    $photo = $photo_model;
                }
                $photo->url = '/' . $upload_model->image_file_name;
                if ( $photo->save() == false )
                {
                    throw new \yii\web\HttpException(500, 'Error saving uploaded image:' . implode(' | ', $photo->getErrors('url')) );
                }
                
                // Save photo_id on the article record
                $model->photo_id = $photo->id;
            }
            
             // Save new publishing datetime
            $model->datetime_published = date("Y-m-d H:i:s");
            
            // Save parent data
            if ( $model->save() ) {   
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // Something went wrong ...
                return $this->render('update', [
                    'model' => $model,
                    'upload_model' => $upload_model,
                ]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
                'upload_model' => $upload_model,
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        // Make sure user is logged in
        if ( Yii::$app->user->isGuest ) {
            return $this->redirect(['site/login']);
        }
        
        $parent = $this->findModel($id);

        if ( $parent->user_id !== Yii::$app->user->identity->id ) {
            throw HttpException(403, 'This article doesn\'t belong to you!');
        }

        $photo = $parent->photo;
        $parent->delete();
        if ( $photo )
        {
            $photo->delete();    
        }

        return $this->redirect(['/articles/user/'.Yii::$app->user->identity->id]);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
