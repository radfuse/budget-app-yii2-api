<?php
namespace app\controllers;

use Yii;
use app\components\RestController;
use app\models\Category;
use yii\data\ActiveDataFilter;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii\rest\IndexAction;
use yii\rest\ViewAction;
use yii\rest\CreateAction;
use yii\rest\UpdateAction;
use yii\rest\DeleteAction;
use yii\rest\OptionsAction;

/**
 * Category controller
 */
class CategoryController extends RestController
{
	/** @var string */
	public $modelClass = 'app\models\Category';

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' => [$this, 'prepareDataProvider'],
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => $this->modelClass,
                ]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }

	/**
     * Checks the privilege of the current user
     *
     * @param string $action
     * @param Category $model
     * @param array $params
     * @throws ForbiddenHttpException
	 */
    public function checkAccess($action, $model = null, $params = [])
    {
		if ($model && $model->user_id != Yii::$app->user->getId()) {
			throw new ForbiddenHttpException;
		}
    }
    
	/**
	 * Prepares dataProvider
     * @param IndexAction $action
     * @param mixed $filter
	 * @return ActiveDataProvider
	 */
	public function prepareDataProvider($action, $filter) 
	{
        $modelClass = $this->modelClass;
		$query = $modelClass::find();

		$query->where(['user_id' => Yii::$app->user->id]);

        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        return new ActiveDataProvider([
        	'query' => $query,
            'sort' => ['defaultOrder' => ['name' => SORT_ASC]]
		]);
	}
}
