<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use slider\backend\assets\SliderFormAsset;
use uploadimage\widgets\UploadImages;

SliderFormAsset::register($this);

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'slider-form'],
]); ?>

	<?= $form->field($model, 'active')->checkbox() ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'alias') ?>

	<?= $form->field($model, 'images')->widget(UploadImages::className(), [
		'id' => 'slider-images',
		'fileKey' => 'file',
		'thumbKey' => 'thumb',
		'thumbWidth' => 1200,
		'thumbHeight' => 400,
		'width' => 282,
		'height' => 94,
		'data' => function($item) {
			return [
				'id' => $item['id'],
				'title' => $item['title'],
				'description' => $item['description'],
				'url' => $item['url'],
			];
		},
		'buttons' => [
			'settings' => [
				'label' => '<i class="fa fa-bars"></i>',
				'title' => Yii::t('slider', 'Settings'),
			],
		],
		'options' => [
			'data-text-modal' => Yii::t('slider', 'Image settings'),
			'data-text-title' => Yii::t('slider', 'Title'),
			'data-text-description' => Yii::t('slider', 'Description'),
			'data-text-url' => Yii::t('slider', 'Url'),
			'data-text-cancel' => Yii::t('slider', 'Cancel'),
			'data-text-save' => Yii::t('slider', 'Save'),
		],
	]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('slider', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('slider', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
