<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $type
 * @property float $amount
 * @property string $description
 * @property string $transaction_date
 */
class Transaction extends \yii\db\ActiveRecord
{
    const TYPE_INCOME = 0;
    const TYPE_EXPENSE = 1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
			'blameable' => [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => false,
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'id',
            'category_id' => function ($model) {
                return $model->category ? $model->category->name : 'Uncategorized';
            },
            'amount' => function ($model) {
                return ($model->type == self::TYPE_INCOME ? 1 : -1) * $model->amount;
            },
            'description',
            'transaction_date'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'amount', 'transaction_date'], 'required'],
            [['user_id', 'category_id', 'type'], 'integer'],
            ['description', 'string', 'max' => 255],
            ['amount', 'number'],
            ['transaction_date', 'safe'],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}