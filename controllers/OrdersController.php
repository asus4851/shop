<?php

namespace app\controllers;

use app\models\Products;
use Yii;
use app\models\Orders;
use app\models\OrdersSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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

    public function beforeAction( $action )
    {
        if( in_array($action->id, $this->adminActions) && Yii::$app->user->identity->isAdmin === false )
            throw new ForbiddenHttpException("Permission denied");

        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        $searchModel  = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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
    }

    public function actionStat()
    {
        $orders = Orders::find()->all();

        return $this->render('stat', [
            'orders' => $orders,
        ]);
    }

    public function actionAddProducts()
    {
        if(isset($_POST['product_id']))  // проверка на наличие id
        {
            $product = Products::findOne(['id' => (int)$_POST['product_id']]); // забираем продукт по id
            if(empty($product)) // получили ли мы продукт
                die('error on finding product'); // $this->redirect('/products/shop'); //error
        }

        if(isset($_POST['quantity']) && $_POST['quantity'] > 0) // проверка
            $quantity = $_POST['quantity'];
        else
            die('error with quantity'); // $this->redirect('/products/shop'); //error

        if(empty(Yii::$app->user->identity->id))
            die('error with user'); // $this->redirect('/products/shop'); //error

        $order = Orders::findOne(['user_id' => Yii::$app->user->identity->id, 'confirm' => 0]); //найти не подтвержденный заказ
        if(empty($order)) //если не нашли заказ то создаем новый
        {
            $order          = new Orders();
            $order->user_id = Yii::$app->user->identity->id;
            $order->status  = 0;
            $order->confirm = 0;
            $order->price   = 0;
            $save = $order->save();
            if($save === false)
                die('error on creating order'); //$this->redirect('/products/shop'); //error
        }

        $orderProducts = $order->getProducts();  //получить список продуктов этого заказа
        $productIdList = []; //заносим в массив id
        foreach($orderProducts as $orderProduct)
        {
            $productIdList[] = $orderProduct->id;
        }

        if(in_array($product->id, $productIdList))  //если продукт в массиве то обновляем данные, иначе добавляем
            $add = $order->updateProducts($product->id, $quantity);
        else
            $add = $order->addProducts($product->id, $quantity);

        if($add === false)
            die('error on adding products to order'); //$this->redirect('/products/shop'); //error

        $order->price = round($order->calculateGrandTotal()); //считаем полную стоимость заказа
        if( $order->save() )
            $this->redirect('cart'); //success
        else
            die('error on saving order'); //$this->redirect('/products/shop'); //error
    }

    public function actionRemoveProducts()
    {
        if(isset($_POST['order_id']))
        {
            $order = Orders::findOne(['id' => (int)$_POST['order_id']]);
            if(empty($order)) //проверка на пустой заказ
                die('error on finding order'); // $this->redirect('/products/shop'); //error
        }

        if(empty(Yii::$app->user->identity->id) || $order->user_id !== Yii::$app->user->identity->id) // проверка на
        // пользователя и соответствия что пользователь хочет удалить именно свой заказ
            die('error with order user'); // $this->redirect('/products/shop'); //error

        if(isset($_POST['product_id']))  // проверка на продукт
        {
            $product = Products::findOne(['id' => (int)$_POST['product_id']]); //находим продукт с таблицы
            if(empty($product))
                die('error on finding product'); // $this->redirect('/products/shop'); //error
        }

        if($order->removeProducts($product->id)) // после проверок удаляем нужный продукт в заказе (функция в моделе заказа)
            $this->redirect('cart'); //success
        else
            die('error on removing product from order'); //$this->redirect('/products/shop'); //error
    }

    public function actionConfirm()
    {
        $user_id = Yii::$app->user->identity->id;

        Yii::$app->db->createCommand()
            ->update(Orders::tableName(), ['confirm' => 1], ['user_id' => $user_id, 'confirm' => 0])
            ->execute(); //подтверждаем заказ в корзине
        Yii::$app->controller->redirect('wait');
    }

    public function actionWait() // после нажатия подтверждения
    {
        echo "Ваш заказ принят, ожидайте звонка менеджера";
        echo "<br>";
        echo "<a href='/' class='btn btn-success'>Вернуться на главную страницу</a>";
    }

    public function actionCart()
    {
        $user_id = Yii::$app->user->identity->id;

        /**
         * @var Orders $order
         */
        $order = Orders::findOne([   //забираем 1 товар по user_id
            'user_id' => $user_id,
            'confirm' => 0, // не подтвержденный пользователем
        ]);

        $orderProducts = empty($order) ? [] : $order->getProducts(); // забираем все продукты
        // пользователя по id Orders => order_id,

        return $this->render('cart', [
            'products' => $orderProducts,
            'order'    => $order,
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
    }

    /**
     * Deletes an existing Orders model.
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
