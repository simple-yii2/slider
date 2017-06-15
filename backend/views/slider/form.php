<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use uploadimage\widgets\UploadImages;

?>
<?php $f = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'slider-form'],
]); ?>

	<?= $f->field($form, 'active')->checkbox() ?>

	<?= $f->field($form, 'title') ?>

	<?= $f->field($form, 'alias') ?>

	<?php if ($form->getModel()->children()->count()) {
		echo $f->field($form, 'height')->staticControl();
	} else {
		echo $f->field($form, 'height');	
	} ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('slider', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('slider', 'Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
