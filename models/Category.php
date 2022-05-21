<?php

namespace app\models;

use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
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
            'id', 'name',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            ['user_id', 'integer'],
            ['name', 'string', 'max' => 255],
            ['user_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }
}