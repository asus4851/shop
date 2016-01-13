<?php

use app\models\Products;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProductsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-search">

    <?php $form = ActiveForm::begin([
        'action' => ['shop'],
        'method' => 'get',
    ]); ?>

    <?php //$form->field($model, 'id') ?>

    <?= $form->field($model, 'name')->dropDownList(
        ArrayHelper::map(Products::find()->all(), 'name', 'name'), $params = [
        'prompt' => 'select value',
    ]); ?>

    <?php // $form->field($model, 'description') ?>

    <?= $form->field($model, 'type')->dropDownList(
        ArrayHelper::map(Products::find()->all(), 'type', 'type'), $params = [
        'prompt' => 'select value',
    ]); ?>

    <?= $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'photo') ?>

    <?php // echo $form->field($model, 'thumbnail') ?>

    <?php // echo $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
