<?php
/* @var $searchModel ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Html;

?>
<div class="col-md-12">
    <div class="col-md-4">
        <img src="<?= $model->photo; ?>" width="100%" height="200px">
    </div>

    <div class="col-md-8">
        <p>Название:
            <?= $model->name; ?>
        </p>

        <?php $type = ($model->type == 'usual') ? 'hide' : 'show'; ?>
        <p class=" <?= $type ?>" style="color:red"> Тип:
            <?= $model->type; ?>
        </p>

        <p>Цена:
            <?= $model->price; ?> грн
        </p>

        <p>Наличие:
            <?php if( $model->quantity > 10 )
            {
                echo "Есть в наличии";
            } elseif( $model->quantity > 0 && $model->quantity <= 10 )
            {
                echo "Товар заканчивается";
            } else
            {
                echo "Нет в наличии";
            } ?>
        </p>
        Вы можете заказать от 1 до 100 товаров, если Вы введете больше чем 100 то автоматов будет присовено 100, если меньше одного - то 1

        <form method="get" action="<?= Yii::getAlias('@web') ?>/orders/order">
            <input class="hide" type="text" name="user_id" value="<?= Yii::$app->user->identity->id; ?>">
            <input class="hide" type="text" name="product_id" value="<?= $model->id; ?>">
            <input class="hide" type="text" name="type" value="<?= $model->type; ?>">
            <input class="hide" type="text" name="price" value="<?= $model->price; ?>">
            <input class="" type="text" name="quantity" value="1">
            <button type="submit" class="btn btn-success">Добавить в корзину</button>
        </form>
        <?php
        if($model->type == 'hot')
        {
            echo '<p>В нашем магазине на этот товар действует оптовая скидка!</p>';

            echo '<p>При покупке от 1 до 5 товаров (включительно) - 3%</p>';

            echo '<p>При покупке от 6 до 10 товаров (включительно) - 7%</p>';

            echo '<p>При покупке от 10 и более - 10%</p>';
        }
        ?>
    </div>
    <div class="col-md-12" style="padding-top:20px;">
        <p class="text-center">
            <?= $model->description; ?>
        </p>
    </div>


</div>
</div>