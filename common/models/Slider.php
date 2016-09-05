<?php

namespace slider\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Slider active record
 */
class Slider extends ActiveRecord
{

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'Slider';
	}

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->active = true;
		$this->height = 300;
		$this->imageCount = 0;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'title' => Yii::t('slider', 'Title'),
		];
	}

	/**
	 * Find slider by alias
	 * @param sring $alias Slider alias or id.
	 * @return Slider
	 */
	public static function findByAlias($alias) {
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	/**
	 * Images relation
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getImages()
	{
		return $this->hasMany(SliderImage::className(), ['slider_id' => 'id'])->inverseOf('slider');
	}

	public function updateImageCount()
	{
		$this->imageCount = $this->getImages()->count();
		$this->update(false, ['imageCount']);
	}

}
