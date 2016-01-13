<?php
/* @var Products $model  */
/* @var yii\data\ActiveDataProvider $dataProvider  */
use app\models\Products;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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

        <?php $form = ActiveForm::begin([
            'action' => [Yii::getAlias('@web')."/orders/add-products"],
            'method' => 'post',
        ]); ?>

            <?= Html::hiddenInput('product_id', $model->id ) ?>

            <?= Html::textInput('quantity', 1, ['type' => 'number', 'max' => 100, 'min' => 1] ) ?>

            <div class="form-group">
                <?= Html::submitButton('Add to cart', ['class' => 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>

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