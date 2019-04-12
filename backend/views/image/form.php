<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use dkhlystov\uploadimage\widgets\UploadImage;
use cms\slider\backend\assets\ImageFormAsset;

ImageFormAsset::register($this);

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

    <?= $f->field($form, 'file')->widget(UploadImage::className(), [
        'id' => 'slider-image',
        'thumbAttribute' => 'thumb',
        'maxImageWidth' => 2000,
        'maxImageHeight' => 2000,
        'options' => ['data-url-color' => Url::to(['color']), 'class' => ''],
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
