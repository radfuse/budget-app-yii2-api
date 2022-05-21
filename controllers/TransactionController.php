<?php
namespace app\controllers;

use Yii;
use app\components\RestController;
use app\models\Transaction;
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
 * Transaction controller
 */
class TransactionController extends RestController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => Transaction::class,
                'checkAccess' => [$this, 'checkAccess'],
                'prepareDataProvider' => [$this, 'prepareDataProvider'],
                'dataFilter' => [
                    'class' => ActiveDataFilter::class,
                    'searchModel' => Transaction::class,
                ]
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => Transaction::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => Transaction::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => Transaction::class,
                'checkAccess' => [$this, 'checkAccess'],
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => Transaction::class,
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
     * @param Transaction $model
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
		$query = Transaction::find()->joinWith('category');

		$query->where([Transaction::tableName() . '.user_id' => Yii::$app->user->id]);

        if (!empty($filter)) {
            $query->andWhere($filter);
        }

        return new ActiveDataProvider([
        	'query' => $query,
            'sort' => [
                'attributes' => [
                    'amount' => ['asc' => ['amount' => SORT_ASC, 'id' => SORT_ASC], 'desc' => ['amount' => SORT_DESC, 'id' => SORT_DESC]],
                    'category_id' => ['asc' => ['name' => SORT_ASC], 'desc' => ['name' => SORT_DESC]],
                    'description' => ['asc' => ['description' => SORT_ASC], 'desc' => ['description' => SORT_DESC]],
                    'transaction_date' => ['asc' => ['transaction_date' => SORT_ASC], 'desc' => ['transaction_date' => SORT_DESC]],
                ],
                'defaultOrder' => ['transaction_date' => SORT_DESC],
            ]
		]);
	}
}
