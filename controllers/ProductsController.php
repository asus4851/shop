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
    private $adminActions = [
        'index',
        'create',
        'update',
        'delete',
    ];

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
        if( in_array($action->id, $this->adminActions) && Yii::$app->user->identity->isAdmin === false )
            throw new ForbiddenHttpException("Permission denied");

        return parent::beforeAction($action);
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
     * @param Products $model
     * @return mixed
     */
    private function saveModel( $model )
    {
      return Products::saveImage($model);
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
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
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
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete( $id )
    {
            $photo           = $this->findModel($id)->photo; //получаю относительный путь картинки удаляемого продукта
            $thumbnail       = $this->findModel($id)->thumbnail;
            $deletePhoto     = unlink(Yii::getAlias('@webroot') . $photo);
            $deleteThumbnail = unlink(Yii::getAlias('@webroot') . $thumbnail);

            if( $deletePhoto === true && $deleteThumbnail === true ) // проверяю на успешность удаления
            {
                $this->findModel($id)->delete();

                return $this->redirect(['index']);
            } else
            {
                die('Не удалось подчистить картинки при удалении продукта');
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
