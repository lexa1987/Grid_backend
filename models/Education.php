<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "education".
 *
 * @property string $id
 * @property string $degree
 * @property string $user
 *
 * @property User $user0
 */
class Education extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'education';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['degree', 'user'], 'required'],
            [['user'], 'integer'],
            [['degree'], 'string', 'max' => 255],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'degree' => 'Degree',
            'user' => 'User',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::className(), ['id' => 'user']);
    }
    /**
     * Получение уникальных значений 
     * @return type array
     */
    public function getUnique()
    {
        return Education::find()->select('degree')->distinct()->all();;
    }
}
