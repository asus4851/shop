<?php

namespace app\controllers;

use Imagine\Image\Box;
use Yii;
use app\models\Products;
use app\models\ProductsSearch;
use yii\filters\AccessControl;
use yii\imagine\Image;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\widgets\Pjax;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'create', 'update', 'delete', 'shop', 'item'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'create', 'update', 'delete', 'shop', 'item'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    //    public function beforeAction( $action )
    //    {
    //        if( $action === 'products/create' )
    //        {
    //            if( Yii::$app->user->identity->isAdmin === false )
    //                throw new ForbiddenHttpException("Permission denied");
    //        }
    //
    //        return true;
    //    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        if( Yii::$app->user->identity->isAdmin === true )
        {
            $searchModel  = new ProductsSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else
        {
            throw new ForbiddenHttpException("Permission denied");
        }
    }

    public function actionShop()
    {

        $searchModel  = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('shop', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionItem( $id )
    {
        return $this->render('product_item', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays a single Products model.
     * @param integer $id
     * @return mixed
     */
    public function actionView( $id )
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    const IMAGE_WIDTH = 300;

    private function saveModel( $model )
    {
        $model->date = date('Y-m-d');

        $imageName = date('Y-m-d h:m:s'); //использую дату как уникальное имя картинки
        strval($imageName);
        $imageName    = str_replace([' ', ':'], '-', $imageName) . rand(0, 10000000);
        $model->photo = UploadedFile::getInstance($model, 'photo');

        $fullName = Yii::getAlias('@webroot') . '/photos/' . $imageName . '.' . $model->photo->extension;
        $model->photo->saveAs($fullName);

        $img = Image::getImagine()->open($fullName);

        $size  = $img->getSize();
        $ratio = $size->getWidth() / $size->getHeight();

        $height = round(self::IMAGE_WIDTH / $ratio);

        $box = new Box(self::IMAGE_WIDTH, $height);
        $img->resize($box)->save(Yii::getAlias('@webroot') . '/thumbnails/' . $imageName . '.' . $model->photo->extension);


        $model->thumbnail = '/thumbnails/' . $imageName . '.' . $model->photo->extension;
        $model->photo     = '/photos/' . $imageName . '.' . $model->photo->extension;
        $model->save();

        return true;
    }

    public function actionCreate()
    {
        if( Yii::$app->user->identity->isAdmin === true )
        {
            $model = new Products();

            if( $model->load(Yii::$app->request->post()) && $this->saveModel($model) )
            {
                return $this->redirect(['view', 'id' => $model->id]);
            } else
            {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else
        {
            throw new ForbiddenHttpException("Permission denied");
        }
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id )
    {
        if( Yii::$app->user->identity->isAdmin === true )
        {
            $model = $this->findModel($id);

            if( $model->load(Yii::$app->request->post()) && $this->saveModel($model) )
            {
                return $this->redirect(['view', 'id' => $model->id]);
            } else
            {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else
        {
            throw new ForbiddenHttpException("Permission denied");
        }
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete( $id )
    {
        if( Yii::$app->user->identity->isAdmin === true )
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else
        {
            throw new ForbiddenHttpException("Permission denied");
        }
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if( ($model = Products::findOne($id)) !== null )
        {
            return $model;
        } else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
