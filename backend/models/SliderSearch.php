<?php

namespace slider\backend\models;

use Yii;
use yii\data\ActiveDataProvider;

use slider\common\models\Slider;

/**
 * Slider search model
 */
class SliderSearch extends Slider {

	/**
	 * Search rules
	 * @return array
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
	public function search($params) {
		//ActiveQuery
		$query = Slider::find();

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
