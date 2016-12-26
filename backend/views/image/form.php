<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use uploadimage\widgets\UploadImage;

$thumbHeight = $slider->height;
$height = $thumbHeight / 1200 * 282;
if ($height < 20)
	$height = 20;

?>
<?php $form = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'slider-image-form'],
]); ?>

	<?= $form->field($model, 'title') ?>

	<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

	<?= $form->field($model, 'url') ?>

	<?= $form->field($model, 'file')->widget(UploadImage::className(), [
		'id' => 'slider-image',
		'thumbAttribute' => 'thumb',
		'thumbWidth' => 1200,
		'thumbHeight' => $thumbHeight,
		'width' => 282,
		'height' => $height,
		'maxImageWidth' => 1200,
		'maxImageHeight' => 1200,
	]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('slider', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('slider', 'Cancel'), ['index', 'slider_id' => $slider->id], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>