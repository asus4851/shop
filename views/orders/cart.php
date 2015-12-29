<?php
use yii\helpers\Html;

$totalPrice = 0;
$count      = 0;
if( count($orders) >= 1 )
{
    foreach( $orders as $order )
    {
        if( $order->confirm == 'no' )
        {
            echo '<img src="' . $order->product->photo . '" width="100" height="100"">';
            echo "<br>";
            echo 'Заказал пользователь ' . $order->user_id;
            echo "<br>";
            echo 'id заказанного товара ' . $order->product_id;
            echo "<br>";
            echo "Количество " . $order->quantity;
            echo "<br>";
            echo "Стоимость " . ($order->price) . " грн";
            echo "<br>";

            $totalPrice += ($order->price);
            $count++;
            ?>
            <p style="margin:0 auto;">
            <?= Html::a('Удалить', ['orders/del', 'id' => $order->id], [
            'data-method' => 'post',
            'class'       => 'btn btn-info',
        ]) ?>
            </p><?php
            echo "<hr>";
        }
    }
    if( $totalPrice !== 0 )
    {
        echo "Вы выбрали " . $count . " товаров";
        echo "<br>";
        echo "Итого: " . $totalPrice . " грн";

        echo '<p style="margin:0 auto;">';
        echo Html::a('Подтвердить заказ', ['orders/confirm', [
            'data-method' => 'post',
            'class'       => 'btn btn-info',
        ]]);
        echo '</p>';
    } else
    {
        echo 'Вы подтвердили все свои предыдущие покупки, новых заказов не сделано';
    }
} else
{
    echo 'Вы не выбрали товар, перейдите в <a href="/products/shop">магазин</a> и наслаждайтесь покупками';
    echo '<br>';

}
?>