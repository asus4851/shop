<?php

use app\models\Orders;
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
            [
                'label' => 'Products',
                'value' => function ( Orders $order ) // указывем что мы принимаем обьект класса Orders
                {
                    $products = $order->getProducts(); // получаем все продукты этого заказа
                    $productList = [];
                    foreach( $products as $product )
                        $productList[]   = Html::a($product->name, ['/products/item/' . $product->id]) . ' x' . $order->getQuantity($product->id);

                    return implode(', ', $productList); // возвраем строку вместо массива
                },
                'format' => 'html',
            ],
            'quantity',
            'status',
            'confirm',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
