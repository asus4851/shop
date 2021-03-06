<?php
/**
 * @var Products $model
 */
use app\models\Products;
use yii\helpers\Html;

?>
<div class="col-md-4 item-img" style="margin-bottom: 40px;">
    <img src="<?= $model->thumbnail; ?>" width="100%" height="200px">

    <div class="" style="padding: 5px;margin-bottom: 5px; background: #413E4A; color:white;">
        <p class="text-center">Название:
            <?= $model->name; ?></p>

        <p class="text-center">Описание:
            <?= $model->description; ?>
        </p>

        <?php $type = ($model->type == 'usual') ? 'white' : 'red'; ?>
        <p class="text-center" style="color:<?= $type; ?>"> Тип:
            <?= $model->type; ?>
        </p>

        <p class="text-center">Цена:
            <?= $model->price; ?> грн
        </p>

        <p class="text-center">Наличие:
            <?php
            $quantity = $model->quantity;
            if( $quantity > 10 ) //поправить
            {
                echo "Есть в наличии";
            } elseif( $quantity > 0 && $quantity <= 10 )
            {
                echo "Товар заканчивается";
            } else
            {
                echo "Нет в наличии";
            } ?>
        </p>


        <p class="text-center" style="margin:0 auto;">
            <?= Html::a('Подробнее', ['products/item', 'id' => $model->id], [
                'data-method' => 'post',
                'class'       => 'btn btn-info',
            ]) ?>
        </p>
    </div>


</div>
