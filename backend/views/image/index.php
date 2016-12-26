<?php

use yii\grid\GridView;
use yii\helpers\Html;

$title = $slider->title;

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('slider', 'Slider'), 'url' => ['slider/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('slider', 'Create image'), ['create', 'slider_id' => $slider->id], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'columns' => [
		[
			'attribute' => 'file',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				return Html::img($model->thumb, ['width' => 200]);
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 50px;'],
			'template' => '{update} {delete}',
		],
	],
]) ?>
