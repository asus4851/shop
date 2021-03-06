<?php
/* @var $searchModel ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>
<h1><?=Html::encode($this->title)?></h1>
   <?php Pjax::begin(); ?>
<?php  echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="archive-index">
<?= ListView::widget( [
    'dataProvider' => $dataProvider,
    'itemView' => 'shop_item',
] ); ?>
<?php Pjax::end();?>

