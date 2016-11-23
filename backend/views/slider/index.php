<?php

use yii\grid\GridView;
use yii\helpers\Html;

$title = Yii::t('slider', 'Slider');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('slider', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $model,
	'summary' => '',
	'tableOptions' => ['class' => 'table table-condensed'],
	'rowOptions' => function ($model, $key, $index, $grid) {
		return !$model->active ? ['class' => 'warning'] : [];
	},
	'columns' => [
		[
			'attribute' => 'title',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$title = Html::encode($model->title);
				$alias = Html::tag('span', Html::encode($model->alias), ['class' => 'label label-primary']);
				$count = Html::tag('span', $model->imageCount, ['class' => 'badge']);

				return $title . '&nbsp;' . $alias . '&nbsp;' . $count;
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 75px;'],
			'template' => '{images} {update} {delete}',
			'buttons' => [
				'images' => function($url, $model, $key) {
					$title = Yii::t('slider', 'Images');

					return Html::a('<span class="glyphicon glyphicon-picture"></span>', [
						'image/index', 'slider_id' => $model->id,
					], [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
		],
	],
]) ?>
