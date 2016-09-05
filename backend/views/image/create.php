<?php

use yii\helpers\Html;

$title = Yii::t('slider', 'Create image');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('slider', 'Slider'), 'url' => ['slider/index']],
	['label' => $slider->title, 'url' => ['index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form', [
	'model' => $model,
	'slider' => $slider,
]) ?>
