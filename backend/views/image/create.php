<?php

use yii\helpers\Html;

$title = Yii::t('slider', 'Create image');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('slider', 'Slider'), 'url' => ['slider/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
	'model' => $model,
	'id' => $id,
	'parent' => $parent,
]) ?>
