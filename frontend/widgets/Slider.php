<?php

namespace cms\slider\frontend\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap\Carousel;
use yii\helpers\Html;
use cms\slider\common\models;
use cms\slider\frontend\widgets\assets\SliderAsset;

/**
 * Slider widwget
 */
class Slider extends Carousel
{

	/**
	 * @var string Slider alias
	 */
	public $alias;

	/**
	 * @var boolean Set to true in needed to shuffle slider images
	 */
	public $shuffle = true;

	/**
	 * @var array title tag options
	 */
	public $titleOptions = ['class' => 'h4'];

	/**
	 * @var array description tag options
	 */
	public $descriptionOptions = [];

	/**
	 * @var cms\slider\common\models\Slider Slider model
	 */
	private $model;

	/**
	 * @var array Prev and next buttons content
	 */
	public $controls = [
		'<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span>',
		'<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span>',
	];

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->alias === null)
			throw new InvalidConfigException('Property "alias" must be set.');

		$this->model = models\Slider::findByAlias($this->alias);
		
		if ($this->model && !$this->model->active)
			$this->model = null;

		if (empty($this->items))
			$this->prepareItems();

		if (sizeof($this->items) < 2)
			$this->controls = $this->showIndicators = false;

		$this->registerClientScript();
	}

	/**
	 * Prepare items to show
	 * @return void
	 */
	private function prepareItems()
	{
		$model = $this->model;
		if ($model === null)
			return;

		$items = [];
		foreach ($model->getImages(true) as $item) {
			$image = Html::img($item->thumb);

			$caption = '';
			//title
			if (!empty($item->title)) {
				$o = $this->titleOptions;
				Html::addCssClass($o, 'carousel-title');

				$caption .= Html::tag('span', $item->title, $o);
			}
			//description
			if (!empty($item->description)) {
				$o = $this->descriptionOptions;
				Html::addCssClass($o, 'carousel-title');

				$caption .= Html::tag('span', $item->description, $o);
			}
			if (!empty($caption)) {
				$caption = Html::tag('span', $caption, ['class' => 'carousel-caption']);
			}

			$content = $image . $caption;
			if (!empty($item->url))
				$content = Html::a($content, $item->url);

			$options = [];
			if (!empty($item->background))
				Html::addCssStyle($options, ['background-color' => $item->background]);

			$items[] = [
				'content' => $content,
				'options' => $options,
			];
		}

		if ($this->shuffle)
			shuffle($items);

		$this->items = $items;
	}

	/**
	 * Styles registration
	 * @return void
	 */
	private function registerClientScript()
	{
		if ($this->model === null)
			return;

		SliderAsset::register($this->view);

		$height = $this->model->height;
		$id = $this->id;

		$this->view->registerCss("
			#{$id}.carousel {
				height: {$height}px;
			}
			#{$id}.carousel .item {
				height: {$height}px;
			}
			#{$id}.carousel .item > img,
			#{$id}.carousel .item > a {
				height: {$height}px;
			}
		");
	}

}
