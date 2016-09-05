<?php

namespace slider\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use slider\backend\models\SliderForm;
use slider\backend\models\SliderSearch;
use slider\common\models\Slider;

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
	 * Slider list.
	 * @return void
	 */
	public function actionIndex()
	{
		$model = new SliderSearch;

		return $this->render('index', [
			'dataProvider' => $model->search(Yii::$app->getRequest()->get()),
			'model' => $model,
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
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		//images
		foreach ($object->images as $image) {
			$image->delete();
			Yii::$app->storage->removeObject($image);
		}

		//object
		if ($object->delete())
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Slider deleted successfully.'));

		return $this->redirect(['index']);
	}

}
