<?php

namespace cms\slider\common\models;

use Yii;
use yii\db\ActiveRecord;

use dkhlystov\storage\components\StoredInterface;

/**
 * Slider image active record
 */
class SliderImage extends ActiveRecord implements StoredInterface
{

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'SliderImage';
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'file' => Yii::t('slider', 'Image'),
		];
	}

	/**
	 * Slider relation
	 * @return \yii\db\ActiveQueryInterface
	 */
	public function getSlider()
	{
		return $this->hasOne(Slider::className(), ['id' => 'slider_id']);
	}

	/**
	 * Return files from attributes
	 * @param array $attributes 
	 * @return array
	 */
	private function getFilesFromAttributes($attributes)
	{
		$files = [];

		if (!empty($attributes['file']))
			$files[] = $attributes['file'];

		if (!empty($attributes['thumb']))
			$files[] = $attributes['thumb'];

		return $files;
	}

	/**
	 * @inheritdoc
	 */
	public function getOldFiles()
	{
		return $this->getFilesFromAttributes($this->getOldAttributes());
	}

	/**
	 * @inheritdoc
	 */
	public function getFiles()
	{
		return $this->getFilesFromAttributes($this->getAttributes());
	}

	/**
	 * @inheritdoc
	 */
	public function setFiles($files)
	{
		if (array_key_exists($this->file, $files))
			$this->file = $files[$this->file];

		if (array_key_exists($this->thumb, $files))
			$this->thumb = $files[$this->thumb];
	}

}
