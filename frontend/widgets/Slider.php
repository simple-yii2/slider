<?php

namespace slider\frontend\widgets;

use yii\base\InvalidConfigException;
use yii\bootstrap\Carousel;
use yii\helpers\Html;

use slider\common\models;

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
	 * @var \slider\common\models\Slider Slider model
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

		if (empty($this->items))
			$this->prepareItems();

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
		foreach ($model->images as $image) {
			$content = Html::img($image->thumb);
			if (!empty($image->url))
				$content = Html::a($content, $image->url);

			$caption = '';
			if (!empty($image->title))
				$caption .= Html::tag('h4', $image->title);
			if (!empty($image->description))
				$caption .= Html::tag('p', $image->description);

			$items[] = [
				'content' => $content,
				'caption' => $caption,
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

		$height = $this->model->height;
		$id = $this->id;

		$this->view->registerCss("
			#{$id}.carousel {
				height: {$height}px;
			}
			#{$id}.carousel .item {
				height: {$height}px;
				overflow: hidden;
			}
			#{$id}.carousel .item > img,
			#{$id}.carousel .item > a {
				height: {$height}px;
				left: 50%;
				margin-left: -600px;
				max-width: none;
				position: absolute;
				top: 0;
				width: 1200px;
			}
		");
	}

}
