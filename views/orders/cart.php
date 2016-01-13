<?php
/**
* @var \app\models\Products[] $products
* @var \app\models\Orders $order
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if( count($products) == 0 )
{
    echo 'Вы не выбрали товар, перейдите в <a href="/products/shop">магазин</a> и наслаждайтесь покупками';
    echo '<br>';
}

$productsCount = 0;
foreach( $products as $product )
{
    $productQuantity = $order->getQuantity($product->id);
    $productsCount += $productQuantity;

    echo '<img src="' . $product->photo . '" width="100" height="100"">';
    echo "<br>";
    echo 'Заказал пользователь ' . $order->user_id;
    echo "<br>";
    echo 'id заказанного товара ' . $product->id;
    echo "<br>";
    echo "Количество " .$productQuantity;
    echo "<br>";
    echo "Стоимость " . $product->getFullPrice($productQuantity) . " грн";
    echo "<br>";

    ?>
    <div style="margin:0 auto;">

        <?php $form = ActiveForm::begin(['action' => ['orders/remove-products'], 'method' => 'post']); ?>

        <?= Html::hiddenInput('order_id', $order->id) ?>
        <?= Html::hiddenInput('product_id', $product->id) ?>

        <div class="form-group">
            <?= Html::submitButton('Удалить', ['class' => 'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <hr>
    <?php
}

if( empty($order) === false && $order->price !== 0 )
{
    echo "Вы выбрали " . $productsCount . " товаров";
    echo "<br>";
    echo "Итого: " . $order->price . " грн";

    echo '<p style="margin:0 auto;">';
    echo Html::a('Подтвердить заказ', ['orders/confirm', [
        'data-method' => 'post',
        'class'       => 'btn btn-info',
    ]]);
    echo '</p>';
}

?>