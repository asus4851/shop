<?php
/* @var $searchModel ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Html;

?>
<div class="col-md-4">
    <img src="<?= $model->thumbnail; ?>" width="100%" height="200px">

    <div class="" style="padding: 5px;margin-bottom: 5px; background: #413E4A; color:white;">
        <p class="text-center">Название:
            <?= $model->name; ?></p>

        <p class="text-center">Описание:
            <?= $model->description; ?>
        </p>

        <?php $type = ($model->type == 'usual') ? 'hide' : 'show'; ?>
        <p class="text-center <?= $type ?>" style="color:red"> Тип:
            <?= $model->type; ?>
        </p>

        <p class="text-center">Цена:
            <?= $model->price; ?> грн
        </p>

        <p class="text-center">Количество:
            <?= $model->quantity; ?> шт.
        </p>

        <p class="text-center" style="margin:0 auto;">
            <?= Html::a('Добавить в корзину', ['orders/order', 'user_id' => Yii::$app->user->identity->id,
                                               'product_id'              => $model->id], [
                'data-method' => 'post',
                'class'       => 'btn btn-info',
            ]) ?>
        </p>
    </div>
</div>