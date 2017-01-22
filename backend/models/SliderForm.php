<?php

namespace cms\slider\backend\models;

use Yii;
use yii\base\Model;

use cms\slider\common\models\Slider;
use cms\slider\common\models\SliderImage;

/**
 * Slider editting form
 */
class SliderForm extends Model
{

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
	 * @var Slider Slider model
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param Slider $object 
	 */
	public function __construct(Slider $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->title = $object->title;
		$this->alias = $object->alias;
		$this->height = $object->height;

		parent::__construct($config);
	}

	/**
	 * Object getter
	 * @return Slider
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
			[['title', 'alias', 'height'], 'required'],
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
		$object->active = $this->active == 1;
		$object->title = $this->title;
		$object->alias = $this->alias;
		$object->height = $this->height;

		//saving object
		if ($object->getIsNewRecord()) {
			if (!$object->makeRoot(false))
				return false;
		} else {
			if (!$object->save(false))
				return false;
		}


		return true;
	}

}
