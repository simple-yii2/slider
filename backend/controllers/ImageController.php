<?php

namespace cms\slider\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\slider\common\models\Slider;
use cms\slider\common\models\SliderImage;
use cms\slider\backend\models\SliderImageForm;
use cms\slider\backend\models\SliderImageSearch;

/**
 * Slider image controller
 */
class ImageController extends Controller
{

	/**
	 * Access control
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
	 * Image list
	 * @param string $slider_id Slider id
	 * @return void
	 */
	public function actionIndex($slider_id)
	{
		$slider = Slider::findOne($slider_id);
		if ($slider === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		$dataProvider = new ActiveDataProvider([
			'query' => SliderImage::find()->andWhere(['slider_id' => $slider->id]),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'slider' => $slider,
		]);
	}

	/**
	 * Image create
	 * @param string $slider_id Slider id
	 * @return void
	 */
	public function actionCreate($slider_id)
	{
		$slider = Slider::findOne($slider_id);
		if ($slider === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		$object = new SliderImage;
		$object->slider_id = $slider->id;

		$model = new SliderImageForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index', 'slider_id' => $slider->id]);
		}

		return $this->render('create', [
			'model' => $model,
			'slider' => $slider,
		]);
	}

	/**
	 * Image update
	 * @param string $id Slider image id
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$object = SliderImage::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Image not found.'));

		$slider = Slider::findOne($object->slider_id);
		if ($slider === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		$model = new SliderImageForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index', 'slider_id' => $slider->id]);
		}

		return $this->render('update', [
			'model' => $model,
			'slider' => $slider,
		]);
	}

	/**
	 * Image delete
	 * @param string $id Slider image id
	 * @return void
	 */
	public function actionDelete($id)
	{
		$object = SliderImage::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Image not found.'));

		$slider = $object->slider;
		if ($slider === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		//object
		if ($object->delete()) {
			Yii::$app->storage->removeObject($object);

			Yii::$app->session->setFlash('success', Yii::t('slider', 'Image deleted successfully.'));
		}

		$slider->updateImageCount();

		return $this->redirect(['index', 'slider_id' => $slider->id]);
	}

}
