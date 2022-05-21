<?php
namespace app\controllers;

use Yii;
use app\components\RestController;
use app\models\Category;
use app\models\Transaction;
use yii\helpers\ArrayHelper;
use yii\rest\OptionsAction;

/**
 * Statistics controller
 */
class StatisticsController extends RestController
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => OptionsAction::class,
            ],
        ];
    }
    /**
     * Index action
     * 
     * @return array
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->getId();
        $aggregatedData = [];
        $threeMonthsBefore = date('Y-m-01', strtotime('-3 months'));

        $categories = ArrayHelper::map(Category::findAll(['user_id' => $userId]), 'id', 'name');

        /** @var Transaction[] $transactions */
        $transactions = Transaction::find()
            ->where([
                'and',
                ['user_id' => $userId],
                ['>=', 'transaction_date', $threeMonthsBefore]
            ])
            ->orderBy('transaction_date DESC')
            ->all();

        foreach ($transactions as $transaction) {
            $month = date('Y.m.', strtotime($transaction->transaction_date));
            $category = $categories[$transaction->category_id] ?? 'Uncategorized';

            if (!isset($aggregatedData[$month])) {
                $aggregatedData[$month] = [];
            }

            if (!isset($aggregatedData[$month][$category])) {
                $aggregatedData[$month][$category] = [
                    'incoming' => 0,
                    'expense' => 0,
                    'balance' => 0,
                ];
            }

            if ($transaction->type == Transaction::TYPE_INCOME) {
                $aggregatedData[$month][$category]['incoming'] += $transaction->amount;
            } else {
                $aggregatedData[$month][$category]['expense'] -= $transaction->amount;
            }

            $aggregatedData[$month][$category]['balance'] = $aggregatedData[$month][$category]['incoming'] + $aggregatedData[$month][$category]['expense'];
        }

        $result = [];

        foreach ($aggregatedData as $month => $categories) {
            $resultCategories = [];
            foreach ($categories as $category => $values) {
                $resultCategories[] = [
                    'name' => $category,
                    'values' => $values
                ];
            }

            $result[] = [
                'month' => $month,
                'categories' => $resultCategories
            ];
        }

        return $result;
    }
}
