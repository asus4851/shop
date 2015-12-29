<?php

namespace app\controllers;

use Yii;
use app\models\Orders;
use app\models\OrdersSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
                'only'  => ['index', 'create', 'update', 'delete', 'order', 'cart'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'create', 'update', 'delete', 'order', 'cart'],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        if( Yii::$app->user->identity->isAdmin == true )
        {
            $searchModel  = new OrdersSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else
        {
            echo "У Вас не хватает прав для просмотра этой страницы";
        }
    }

    /**
     * Displays a single Orders model.
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
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if( Yii::$app->user->identity->isAdmin == true )
        {
            $model = new Orders();

            if( $model->load(Yii::$app->request->post()) && $model->save() )
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
            echo "У Вас не хватает прав для просмотра этой страницы";
        }
    }

    public function actionOrder()
    {
        if( isset($_GET['user_id']) && isset($_GET['product_id']) )
        {
            $price    = (int)$_GET['price'];
            $quantity = (int)$_GET['quantity'];
            $summ     = $price * $quantity;
            if( $_GET['type'] == 'hot' )
            {
                if( $quantity >= 1 && $quantity <= 5 )
                {

                    $summ = $summ - ($price * $quantity * 0.03);
                } elseif( $quantity >= 6 && $quantity <= 10 )
                {
                    $summ = $summ - ($price * $quantity * 0.07);
                } elseif( $quantity > 10 )
                {
                    $summ = $summ - ($price * $quantity * 0.1);
                }
            }

            $model             = new Orders();
            $model->user_id    = (int)$_GET['user_id'];
            $model->product_id = (int)$_GET['product_id'];
            $model->quantity   = $quantity; // Доработать момент передачи выбранного количества
            $model->confirm    = "no";
            $model->type       = $_GET['type'];
            $model->price      = $summ;
            $model->status     = 'not confirmed';
            $model->save();

            $this->redirect('cart');
        } else
        {
            echo "Cannot find user_id or/and product_id in GET request";
        }
    }

    public function actionConfirm()
    {
        $user_id = Yii::$app->user->identity->id;

        Yii::$app->db->createCommand()
            ->update(Orders::tableName(), ['confirm' => 'yes'], ['user_id' => $user_id, 'confirm' => 'no'])
            ->execute();
        Yii::$app->controller->redirect('wait');
    }

    public function actionWait()
    {
        echo "Ваш заказ принят, ожидайте звонка менеджера";
        echo "<br>";
        echo "<a href='/' class='btn btn-success'>Вернуться на главную страницу</a>";
    }

    public function actionCart()
    {
        $user_id = Yii::$app->user->identity->id;

        $orders = Orders::find()
            ->where(['user_id' => $user_id])
            ->all();

        return $this->render('cart', [
            'orders' => $orders,
        ]);

    }

    /**
     * Updates an existing Orders model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate( $id )
    {
        if( Yii::$app->user->identity->isAdmin == true )
        {
            $model = $this->findModel($id);

            if( $model->load(Yii::$app->request->post()) && $model->save() )
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
            echo "У Вас не хватает прав для просмотра этой страницы";
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete( $id )
    {
        if( Yii::$app->user->identity->isAdmin == true )
        {
            $this->findModel($id)->delete();

            return $this->redirect(['index']);
        } else
        {
            echo "У Вас не хватает прав для просмотра этой страницы";
        }
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel( $id )
    {
        if( ($model = Orders::findOne($id)) !== null )
        {
            return $model;
        } else
        {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
