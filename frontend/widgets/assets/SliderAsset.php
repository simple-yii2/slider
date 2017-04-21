<?php

namespace cms\slider\frontend\widgets\assets;

use yii\web\AssetBundle;

class SliderAsset extends AssetBundle
{

	public $css = [
		'slider.css',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/slider';
	}

}
