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

    public function beforeAction( $action )
    {
        if( Yii::$app->user->identity->isAdmin === false )
            throw new ForbiddenHttpException("Permission denied");

        return true;
    }

    /**
     * Lists all Products models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel  = new ProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

    private function saveModel($model)
    {
        $model->date = date('Y-m-d');

        $imageName = date('Y-m-d h:m:s'); //��������� ���� ��� ���������� ��� ��������
        strval($imageName);
        $imageName = str_replace(' ', '-', $imageName);
        $imageName = str_replace(':', '-', $imageName);

        $model->photo = UploadedFile::getInstance($model, 'photo');
        $model->photo->saveAs('photos/' . $imageName . '.' . $model->photo->extension);

        $fullName = Yii::getAlias('@webroot') . '/photos/' . $imageName . '.' . $model->photo->extension;
        $img      = Image::getImagine()->open($fullName);

        $size  = $img->getSize();
        $ratio = $size->getWidth() / $size->getHeight();

        $width  = 300; //����� �� ���������� ��������
        $height = round($width / $ratio);

        $box = new Box($width, $height);
        $img->resize($box)->save(Yii::getAlias('@webroot') . '/thumbnails/' . $imageName . '.' . $model->photo->extension);


        $model->thumbnail = '/thumbnails/' . $imageName . '.' . $model->photo->extension;
        $model->photo     = '/photos/' . $imageName . '.' . $model->photo->extension;
        $model->save();
        return true;
    }

    public function actionCreate()
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
    }

    /**
     * Updates an existing Products model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id )
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
    }

    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete( $id )
    {

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
