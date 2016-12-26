<?php

namespace cms\slider\backend\models;

use Yii;
use yii\data\ActiveDataProvider;

use cms\slider\common\models\Slider;

/**
 * Slider search model
 */
class SliderSearch extends Slider
{

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('slider', 'Title'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['title', 'string'],
		];
	}

	/**
	 * Search function
	 * @param array $params Attributes array
	 * @return yii\data\ActiveDataProvider
	 */
	public function search($params)
	{
		//ActiveQuery
		$query = static::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		//return data provider if no search
		if (!($this->load($params) && $this->validate()))
			return $dataProvider;

		//search
		$query->andFilterWhere(['like', 'title', $this->title]);

		return $dataProvider;
	}

}
