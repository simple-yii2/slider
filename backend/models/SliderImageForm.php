<?php

namespace cms\slider\backend\models;

use Yii;
use yii\base\Model;

use cms\slider\common\models\Slider;
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
	 * @var string Background color
	 */
	public $background;

	/**
	 * @var string Image description.
	 */
	public $description;

	/**
	 * @var string Image url.
	 */
	public $url;

	/**
	 * @var SliderImage Image model
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param SliderImage $object 
	 */
	public function __construct(SliderImage $object = null, $config = [])
	{
		if ($object === null)
			$object = new SliderImage;

		$this->_object = $object;

		//attributes
		$this->file = $object->file;
		$this->thumb = $object->thumb;
		$this->background = $object->background;
		$this->title = $object->title;
		$this->description = $object->description;
		$this->url = $object->url;

		//file caching
		Yii::$app->storage->cacheObject($object);

		parent::__construct($config);
	}

	/**
	 * Object getter
	 * @return SliderImage
	 */
	public function getObject()
	{
		return $this->_object;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'file' => Yii::t('slider', 'Image'),
			'background' => Yii::t('slider', 'Background color'),
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
			['background', 'string', 'max' => 10],
			['title', 'string', 'max' => 100],
			['file', 'required'],
		];
	}

	/**
	 * Object saving
	 * @param Slider|null $parent 
	 * @return boolean
	 */
	public function save(Slider $parent = null)
	{
		//validation
		if (!$this->validate())
			return false;

		$object = $this->_object;

		//attributes
		$object->file = $this->file;
		$object->thumb = $this->thumb;
		$object->background = $this->background;
		$object->title = $this->title;
		$object->description = $this->description;
		$object->url = $this->url;

		//files
		Yii::$app->storage->storeObject($object);

		//saving object
		if ($object->getIsNewRecord()) {
			if (!$object->appendTo($parent, false))
				return false;
		} else {
			if (!$object->save(false))
				return false;

		}

		return true;
	}

}
