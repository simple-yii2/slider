<?php

namespace cms\slider\backend\models;

use Yii;
use yii\base\Model;

use cms\slider\common\models\Slider;
use cms\slider\common\models\SliderImage;

/**
 * Slider editting form
 */
class SliderForm extends Model
{

    /**
     * @var boolean Active.
     */
    public $active;

    /**
     * @var string Slider title.
     */
    public $title;

    /**
     * @var string Slider alias.
     */
    public $alias;

    /**
     * @var Slider Slider model
     */
    private $_model;

    /**
     * @inheritdoc
     * @param Slider|null $model 
     */
    public function __construct(Slider $model = null, $config = [])
    {
        if ($model === null)
            $model = new Slider;
        
        $this->_model = $model;

        //attributes
        $this->active = $model->active == 0 ? 0 : 1;
        $this->title = $model->title;
        $this->alias = $model->alias;

        parent::__construct($config);
    }

    /**
     * Model getter
     * @return Slider
     */
    public function getModel()
    {
        return $this->_model;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active' => Yii::t('slider', 'Active'),
            'title' => Yii::t('slider', 'Title'),
            'alias' => Yii::t('slider', 'Alias'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active', 'boolean'],
            [['title', 'alias'], 'string', 'max' => 100],
            [['title', 'alias'], 'required'],
        ];
    }

    /**
     * Object saving
     * @return boolean
     */
    public function save()
    {
        //validation
        if (!$this->validate())
            return false;

        $model = $this->_model;

        //attributes
        $model->active = $this->active == 1;
        $model->title = $this->title;
        $model->alias = $this->alias;

        //saving model
        if ($model->getIsNewRecord()) {
            if (!$model->makeRoot(false))
                return false;
        } else {
            if (!$model->save(false))
                return false;
        }

        return true;
    }

}
