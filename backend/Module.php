<?php

namespace cms\slider\backend;

use Yii;
use cms\components\BackendModule;

/**
 * Slider backend module
 */
class Module extends BackendModule
{

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'slider';
	}

	/**
	 * @inheritdoc
	 */
	protected static function cmsSecurity()
	{
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Slider') === null) {
			//role
			$role = $auth->createRole('Slider');
			$auth->add($role);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function cmsMenu()
	{
		if (!Yii::$app->user->can('Slider')) {
			return [];
		}

		return [
			'label' => Yii::t('slider', 'Slider'),
			'url' => ['/slider/slider/index'],
		];
	}

}
