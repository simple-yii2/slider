<?php

use yii\helpers\Html;
use yii\web\JsExpression;

use dkhlystov\widgets\NestedTreeGrid;
use cms\slider\common\models\Slider;

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

<?= NestedTreeGrid::widget([
	'dataProvider' => $dataProvider,
	'showRoots' => true,
	'initialNode' => $initial,
	'moveAction' => ['move'],
	'tableOptions' => ['class' => 'table table-condensed'],
	'pluginOptions' => [
		'onMoveOver' => new JsExpression('function (item, helper, target, position) {
			if (item.data("depth") == 0)
				return false;
			
			if (target.data("depth") == 0)
				return position == 1;

			return position != 1;
		}'),
	],
	'rowOptions' => function ($model, $key, $index, $grid) {
		$options = ['data-depth' => $model->depth];

		if (!$model->active)
			Html::addCssClass($options, 'warning');

		return $options;
	},
	'columns' => [
		[
			'attribute' => 'title',
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				if ($model instanceof Slider) {
					$title = Html::encode($model->title);
					$alias = Html::tag('span', Html::encode($model->alias), ['class' => 'label label-primary']);
					$count = Html::tag('span', $model->children()->count(), ['class' => 'badge']);

					return $title . ' ' . $alias . ' ' . $count;
				} else {
					$image = Html::img($model->thumb, ['height' => 20]);
					$title = Html::encode($model->title);
					$url = Html::tag('span', Html::encode($model->url), ['class' => 'text-info']);

					return $image . ' ' . $title . ' ' . $url;
				}
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 75px;'],
			'template' => '{update} {delete} {create}',
			'buttons' => [
				'create' => function ($url, $model, $key) {
					if (!$model->isRoot())
						return '';

					$title = Yii::t('slider', 'Create image');

					return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
			'urlCreator' => function ($action, $model, $key, $index) {
				if ($action == 'create' || !$model->isRoot())
					$action = 'image/' . $action;

				return [$action, 'id' => $model->id];
			},
		],
	],
]) ?>
