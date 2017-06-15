<?php

namespace cms\slider\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use dkhlystov\helpers\ImageFile;
use cms\slider\common\models\Slider;
use cms\slider\common\models\SliderImage;
use cms\slider\backend\models\SliderImageForm;

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
	 * Creating
	 * @param integer $id
	 * @return string
	 */
	public function actionCreate($id)
	{
		$parent = Slider::findOne($id);
		if ($parent === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$form = new SliderImageForm;

		if ($form->load(Yii::$app->getRequest()->post()) && $form->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect([
				'slider/index',
				'id' => $form->getModel()->id,
			]);
		}

		return $this->render('create', [
			'form' => $form,
			'id' => $id,
			'parent' => $parent,
		]);
	}

	/**
	 * Updating
	 * @param string $id
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$model = SliderImage::findOne($id);
		if ($model === null || $model->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$form = new SliderImageForm($model);

		if ($form->load(Yii::$app->getRequest()->post()) && $form->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect([
				'slider/index',
				'id' => $form->getModel()->id,
			]);
		}

		return $this->render('update', [
			'form' => $form,
			'id' => $model->id,
			'parent' => $model->parents(1)->one(),
		]);
	}

	/**
	 * Deleting
	 * @param string $id
	 * @return void
	 */
	public function actionDelete($id)
	{
		$model = SliderImage::findOne($id);
		if ($model === null || $model->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		$sibling = $model->prev()->one();
		if ($sibling === null)
			$sibling = $model->next()->one();

		if ($model->deleteWithChildren()) {
			Yii::$app->storage->removeObject($model);

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
