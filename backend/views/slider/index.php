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
				$alias = Html::tag('span', $model->alias, ['class' => 'label label-primary']);
				$count = Html::tag('span', $model->imageCount, ['class' => 'badge']);

				return $title . '&nbsp;' . $alias . '&nbsp;' . $count;
			}
		],
		[
			'class'=>'yii\grid\ActionColumn',
			'options'=>['style'=>'width: 50px;'],
			'template'=>'{update} {delete}',
		],
	],
]) ?>
