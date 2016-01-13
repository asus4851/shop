<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user_id',
            'user.username',
            'user.email',
            ////           [  // не успеваю нормально реализоват, по идее есть сырой вариант длявывода имени снизу
            //            'attribute' => 'test',
            //            'value' => function ($dataProvider) {
            //                return $dataProvider->getProducts();
            //            }
            //        ],
            'quantity',
            'status',
            'confirm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php // $model = $dataProvider->getModels();  // сырой вариант для вывода списка имен продуктов для каждого заказа в view

//    $length = count($model);
//    $order = new \app\models\Orders();
//    $product = new \app\models\Products();
//    for($i=0;$i<$length;$i++){
//        $id = $model[$i]['id'];
//        $array_products_id = $order -> getProductsIdByManager($id);
//        $array_products_name = $product->getProductsNameByManager($array_products_id);
//        echo "<pre>";
//        echo "id заказа ".$id." и в нем храняться такие продукты с именем";
//        print_r($array_products_name);
//        echo "</pre>";

   // }

   // die; ?>
</div>
