<?php
use yii\helpers\Html;
$totalPrice = 0;

foreach( $orders as $order )
{
    echo '<img src="'.$order->product->photo.'" width="100" height="100"">';
    echo "<br>";
    echo 'Заказал пользователь ' . $order->user_id;
    echo "<br>";
    echo 'id заказанного товара ' . $order->product_id;
    echo "<br>";
    echo "Количество " . $order->quantity;
    echo "<br>";
    echo "Стоимость " . ($order->product->price * $order->quantity) . " грн";
    echo "<br>";
    echo "<hr>";
    $totalPrice +=($order->product->price * $order->quantity);
}
echo "Итого: ".$totalPrice. " грн";
?>
<p style="margin:0 auto;">
    <?= Html::a('Подтвердить заказ', ['orders/confirm',[
        'data-method' => 'post',
        'class'       => 'btn btn-info',
    ]]); ?>
</p>