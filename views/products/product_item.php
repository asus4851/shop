<?php
/* @var $searchModel ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Html;

?>
<div class="col-md-12">
    <div class="col-md-4">
        <img src="<?= $model->thumbnail; ?>" width="100%" height="200px">
    </div>

    <div class="col-md-8">
        <p>Название:
            <?= $model->name; ?>
        </p>

        <?php $type = ($model->type == 'usual') ? 'hide' : 'show'; ?>
        <p class="text-center <?= $type ?>" style="color:red"> Тип:
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

        <p style="margin:0 auto;">
            <?= Html::a('Добавить в корзину', ['orders/order', 'user_id' => Yii::$app->user->identity->id,
                                               'product_id'              => $model->id], [
                'data-method' => 'post',
                'class'       => 'btn btn-info',
            ]) ?>
        </p>

    </div>
    <div class="col-md-12" style="padding-top:20px;">
        <p class="text-center">
            <?= $model->description; ?>
        </p>
    </div>


</div>
</div>