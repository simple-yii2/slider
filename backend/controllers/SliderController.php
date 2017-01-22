<?php

namespace cms\slider\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\slider\backend\models\SliderForm;
use cms\slider\common\models\BaseSlider;
use cms\slider\common\models\Slider;

/**
 * Slider controller.
 */
class SliderController extends Controller
{

	/**
	 * Access control.
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Slider']],
				],
			],
		];
	}

	/**
	 * Tree
	 * @param integer|null $id Initial item id
	 * @return string
	 */
	public function actionIndex($id = null)
	{
		$initial = BaseSlider::findOne($id);

		$dataProvider = new ActiveDataProvider([
			'query' => BaseSlider::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'initial' => $initial,
		]);
	}

	/**
	 * Slider creating.
	 * @return void
	 */
	public function actionCreate()
	{
		$model = new SliderForm(new Slider);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Slider updating.
	 * @param integer $id Slider id.
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$object = Slider::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		$model = new SliderForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Slider deleting.
	 * @param integer $id Slider id.
	 * @return void
	 */
	public function actionDelete($id)
	{
		$object = Slider::findOne($id);
		if ($object === null || !$object->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		//images
		foreach ($object->images as $image) {
			$image->deleteWithChildren();
			Yii::$app->storage->removeObject($image);
		}

		//object
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Item deleted successfully.'));

		return $this->redirect(['index']);
	}

	/**
	 * Move
	 * @param integer $id 
	 * @param integer $target 
	 * @param integer $position 
	 * @return void
	 */
	public function actionMove($id, $target, $position)
	{
		$object = Slider::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));
		if ($object->isRoot())
			return;


		$t = Slider::findOne($target);
		if ($t === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));
		if ($t->isRoot())
			return;

		if ($object->tree != $t->tree)
			return;

		switch ($position) {
			case 0:
				$object->insertBefore($t);
				break;
			
			case 2:
				$object->insertAfter($t);
				break;
		}
	}

}
