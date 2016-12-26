<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use uploadimage\widgets\UploadImages;

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'slider-form'],
]); ?>

	<?= $form->field($model, 'active')->checkbox() ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'alias') ?>

	<?php if ($model->imageCount) {
		echo $form->field($model, 'height')->staticControl();
	} else {
		echo $form->field($model, 'height');	
	} ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('slider', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('slider', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
