<?php

namespace cms\slider\backend\assets;

use yii\web\AssetBundle;

class ImageFormAsset extends AssetBundle
{

	public $js = [
		'image-form.js',
	];
	
	public $depends = [
		'yii\bootstrap\BootstrapAsset',
		'yii\web\JqueryAsset',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/image-form';
	}

}
