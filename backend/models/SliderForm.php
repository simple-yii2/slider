<?php

namespace slider\backend\models;

use Yii;
use yii\base\Model;

use slider\common\models\SliderImage;

/**
 * Slider editting form
 */
class SliderForm extends Model {

	/**
	 * @var boolean Active.
	 */
	public $active;

	/**
	 * @var string Slider title.
	 */
	public $title;

	/**
	 * @var string Slider alias.
	 */
	public $alias;

	/**
	 * @var array Slider images.
	 */
	public $images = [];

	/**
	 * @var slider\common\models\Slider Slider model
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param slider\common\models\Slider $object 
	 */
	public function __construct($object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->title = $object->title;
		$this->alias = $object->alias;

		//images
		$this->images = array_map(function($v) {
			return $v->getAttributes();
		}, $object->images);

		//file caching
		foreach ($object->images as $image) {
			Yii::$app->storage->cacheObject($image);
		}

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'active' => Yii::t('slider', 'Active'),
			'title' => Yii::t('slider', 'Title'),
			'alias' => Yii::t('slider', 'Alias'),
			'images' => Yii::t('slider', 'Images'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['active', 'boolean'],
			[['title', 'alias'], 'string', 'max' => 100],
			['alias', 'required'],
			['images', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		parent::setAttributes($values, $safeOnly);

		if (empty($this->images))
			$this->images = [];
	}

	/**
	 * Object saving
	 * @return boolean
	 */
	public function save()
	{
		//validation
		if (!$this->validate())
			return false;

		$object = $this->_object;

		//attributes
		$object->active = $this->active == 1;
		$object->title = $this->title;
		$object->alias = $this->alias;
		$object->imageCount = sizeof($this->images);

		//saving object
		if (!$object->save(false))
			return false;

		//saving images
		$this->saveImages($object);

		return true;
	}

	/**
	 * Images saving
	 * @param slider\common\models\Slider $object 
	 * @return void
	 */
	public function saveImages($object)
	{
		//old images
		$old = [];
		foreach ($object->images as $image) {
			$old[$image->id] = $image;
		}

		//insert new and update existing
		foreach ($this->images as $data) {
			$id = null;
			if (!empty($data['id']))
				$id = $data['id'];

			if (array_key_exists($id, $old)) {
				$image = $old[$id];
				unset($old[$id]);
			} else {
				$image = new SliderImage();
				$image->slider_id = $object->id;
			}
			$image->setAttributes($data);

			Yii::$app->storage->storeObject($image);

			$image->save(false);
		}

		//delete old
		foreach ($old as $image) {
			Yii::$app->storage->removeObject($image);
			$image->delete();
		}
	}

}
