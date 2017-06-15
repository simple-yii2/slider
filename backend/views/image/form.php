<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use dkhlystov\uploadimage\widgets\UploadImage;
use cms\slider\backend\assets\ImageFormAsset;

ImageFormAsset::register($this);

$thumbHeight = $parent->height;
$height = $thumbHeight / 1200 * 282;
if ($height < 20)
	$height = 20;

$imageSize = '<br><span class="label label-default">1200&times' . $thumbHeight . '</span>';

?>
<?php $f = ActiveForm::begin([
	'layout' => 'horizontal',
	'enableClientValidation' => false,
	'options' => ['class' => 'slider-image-form'],
]); ?>

	<?= $f->field($form, 'active')->checkbox() ?>

	<?= $f->field($form, 'title') ?>

	<?= $f->field($form, 'description')->textarea(['rows' => 3]) ?>

	<?= $f->field($form, 'url') ?>

	<?= $f->field($form, 'file')->label($form->getAttributeLabel('file') . $imageSize)->widget(UploadImage::className(), [
		'id' => 'slider-image',
		'thumbAttribute' => 'thumb',
		'thumbWidth' => 1200,
		'thumbHeight' => $thumbHeight,
		'width' => 282,
		'height' => $height,
		'maxImageWidth' => 1200,
		'maxImageHeight' => 1200,
		'options' => ['data-url-color' => Url::to(['color'])],
	]) ?>

	<?= $f->field($form, 'background')->widget('dkhlystov\widgets\Colorpicker', [
		'clientOptions' => ['format' => 'hex'],
	]) ?>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?= Html::submitButton(Yii::t('slider', 'Save'), ['class' => 'btn btn-primary']) ?>
			<?= Html::a(Yii::t('slider', 'Cancel'), ['slider/index', 'id' => $id], ['class' => 'btn btn-default']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>
