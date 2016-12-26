<?php

namespace cms\slider\backend\assets;

use yii\web\AssetBundle;

class SliderFormAsset extends AssetBundle
{

	public $js = [
		'slider-form.js',
	];
	
	public $depends = [
		'yii\bootstrap\BootstrapAsset',
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/slider-form';
	}

}
