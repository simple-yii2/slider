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
	 * Creating
	 * @return string
	 */
	public function actionCreate()
	{
		$form = new SliderForm;

		if ($form->load(Yii::$app->getRequest()->post()) && $form->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('create', [
			'form' => $form,
		]);
	}

	/**
	 * Updating
	 * @param integer $id Slider id.
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = Slider::findOne($id);
		if ($object === null || !$object->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Slider not found.'));

		$form = new SliderForm($object);

		if ($form->load(Yii::$app->getRequest()->post()) && $form->save()) {
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'form' => $form,
		]);
	}

	/**
	 * Deleting
	 * @param integer $id Slider id.
	 * @return string
	 */
	public function actionDelete($id)
	{
		$model = Slider::findOne($id);
		if ($model === null || !$model->isRoot())
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));

		//images
		foreach ($model->images as $image) {
			$image->deleteWithChildren();
			Yii::$app->storage->removeObject($image);
		}

		//model
		if ($model->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('slider', 'Item deleted successfully.'));

		return $this->redirect(['index']);
	}

	/**
	 * Move
	 * @param integer $id 
	 * @param integer $target 
	 * @param integer $position 
	 * @return string
	 */
	public function actionMove($id, $target, $position)
	{
		$model = BaseSlider::findOne($id);
		if ($model === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));
		if ($model->isRoot())
			return;

		$t = BaseSlider::findOne($target);
		if ($t === null)
			throw new BadRequestHttpException(Yii::t('slider', 'Item not found.'));
		if ($t->isRoot())
			return;

		if ($model->tree != $t->tree)
			return;

		switch ($position) {
			case 0:
				$model->insertBefore($t);
				break;
			
			case 2:
				$model->insertAfter($t);
				break;
		}
	}

}
