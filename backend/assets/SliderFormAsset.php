<?php

namespace slider\backend\assets;

use yii\web\AssetBundle;

class SliderFormAsset extends AssetBundle
{

	public $sourcePath = __DIR__ . '/slider-form';

	public $js = [
		'slider-form.js',
	];
	
	public $depends = [
		'yii\bootstrap\BootstrapAsset',
		'yii\web\JqueryAsset',
	];

}
