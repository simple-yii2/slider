<?php

namespace cms\slider\backend\models;

use Yii;
use yii\base\Model;

use cms\slider\common\models\SliderImage;

/**
 * Slider image editting form
 */
class SliderImageForm extends Model
{

	/**
	 * @var string Image file.
	 */
	public $file;

	/**
	 * @var string Image thumb.
	 */
	public $thumb;

	/**
	 * @var string Image title.
	 */
	public $title;

	/**
	 * @var string Image description.
	 */
	public $description;

	/**
	 * @var string Image url.
	 */
	public $url;

	/**
	 * @var cms\slider\common\models\SliderImage Image model
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\slider\common\models\SliderImage $object 
	 */
	public function __construct(\cms\slider\common\models\SliderImage $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->file = $object->file;
		$this->thumb = $object->thumb;
		$this->title = $object->title;
		$this->description = $object->description;
		$this->url = $object->url;

		//file caching
		Yii::$app->storage->cacheObject($object);

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'file' => Yii::t('slider', 'Image'),
			'title' => Yii::t('slider', 'Title'),
			'description' => Yii::t('slider', 'Description'),
			'url' => Yii::t('slider', 'Url'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['file', 'thumb', 'description', 'url'], 'string', 'max' => 200],
			['title', 'string', 'max' => 100],
			['file', 'required'],
			['url', 'url'],
		];
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
		$object->file = $this->file;
		$object->thumb = $this->thumb;
		$object->title = $this->title;
		$object->description = $this->description;
		$object->url = $this->url;

		//files
		Yii::$app->storage->storeObject($object);

		//saving object
		if (!$object->save(false))
			return false;

		//image count
		$object->slider->updateImageCount();


		$slider = $object->slider;
		if ($slider !== null) {
			$slider->imageCount = SliderImage::find()->andWhere(['slider_id' => $object->slider_id])->count();
			$slider->update(false, ['imageCount']);
		}

		return true;
	}

}
