<?php

namespace cms\slider\common\models;

use Yii;

/**
 * Slider active record
 */
class Slider extends BaseSlider
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->active === null)
			$this->active = true;

		if ($this->height === null)
			$this->height = 300;
	}

	/**
	 * Find slider by alias
	 * @param sring $alias Slider alias or id.
	 * @return Slider
	 */
	public static function findByAlias($alias)
	{
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	/**
	 * Images
	 * @return SliderImage[]
	 */
	public function getImages()
	{
		return $this->children()->all();
	}

}
