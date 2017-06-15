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
	 * @var boolean Active
	 */
	public $active;

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
	private $_model;

	/**
	 * @inheritdoc
	 * @param SliderImage $model 
	 */
	public function __construct(SliderImage $model = null, $config = [])
	{
		if ($model === null)
			$model = new SliderImage;

		$this->_model = $model;

		//attributes
		$this->file = $model->file;
		$this->thumb = $model->thumb;
		$this->background = $model->background;
		$this->active = $model->active == 0 ? 0 : 1;
		$this->title = $model->title;
		$this->description = $model->description;
		$this->url = $model->url;

		//file caching
		Yii::$app->storage->cacheObject($model);

		parent::__construct($config);
	}

	/**
	 * Model getter
	 * @return SliderImage
	 */
	public function getModel()
	{
		return $this->_model;
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'file' => Yii::t('slider', 'Image'),
			'background' => Yii::t('slider', 'Background color'),
			'active' => Yii::t('slider', 'Active'),
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
			[['file', 'thumb', 'url'], 'string', 'max' => 200],
			['description', 'string'],
			['background', 'string', 'max' => 10],
			['active', 'boolean'],
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

		$model = $this->_model;

		//attributes
		$model->file = $this->file;
		$model->thumb = $this->thumb;
		$model->background = $this->background;
		$model->active = $this->active == 1;
		$model->title = $this->title;
		$model->description = $this->description;
		$model->url = $this->url;

		//files
		Yii::$app->storage->storeObject($model);

		//saving model
		if ($model->getIsNewRecord()) {
			if (!$model->appendTo($parent, false))
				return false;
		} else {
			if (!$model->save(false))
				return false;
		}

		return true;
	}

}
