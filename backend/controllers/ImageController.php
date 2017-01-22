<?php

namespace cms\slider\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use dkhlystov\helpers\ImageFile;
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
	 * Create
	 * @param integer $id 
	 * @return string
	 */
	public function actionCreate($id)
	{
		$parent = Slider::findOne($id);
		if ($parent === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$model = new SliderImageForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect([
				'slider/index',
				'id' => $model->getObject()->id,
			]);
		}

		return $this->render('create', [
			'model' => $model,
			'id' => $id,
			'parent' => $parent,
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
		if ($object === null || $object->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$model = new SliderImageForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect([
				'slider/index',
				'id' => $model->getObject()->id,
			]);
		}

		return $this->render('update', [
			'model' => $model,
			'id' => $object->id,
			'parent' => $object->parents(1)->one(),
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
		if ($object === null || $object->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$sibling = $object->prev()->one();
		if ($sibling === null)
			$sibling = $object->next()->one();

		if ($object->deleteWithChildren()) {
			Yii::$app->storage->removeObject($object);

			Yii::$app->session->setFlash('success', Yii::t('slider', 'Item deleted successfully.'));
		}

		return $this->redirect(['slider/index', 'id' => $sibling ? $sibling->id : null]);
	}

	/**
	 * Determine background color for thumb file
	 * @param string $thumb 
	 * @return string
	 */
	public function actionColor($thumb)
	{
		$filename = Yii::getAlias('@webroot') . $thumb;

		$image = new ImageFile($filename);

		return Json::encode('#' . str_pad(dechex($image->colorAt(0, 0)), 6, '0', STR_PAD_LEFT));
	}

}
