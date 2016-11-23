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
	 * @var integer Slider widget height.
	 */
	public $height;

	/**
	 * @var integer Slider image count.
	 */
	private $_imageCount;

	/**
	 * @var slider\common\models\Slider Slider model
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param \slider\common\models\Slider $object 
	 */
	public function __construct(\slider\common\models\Slider $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->title = $object->title;
		$this->alias = $object->alias;
		$this->height = $object->height;
		$this->_imageCount = $object->imageCount;

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
			'height' => Yii::t('slider', 'Height'),
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
			['height', 'integer', 'min' => 100, 'max' => 1000],
			[['alias', 'height'], 'required'],
		];
	}

	/**
	 * Image count getter.
	 * @return integer
	 */
	public function getImageCount()
	{
		return $this->_imageCount;
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
		$object->height = $this->height;

		//saving object
		if (!$object->save(false))
			return false;

		return true;
	}

}
