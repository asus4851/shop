<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property integer $price
 * @property integer $quantity
 * @property string $photo
 * @property string $thumbnail
 * @property string $date
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'price', 'photo', 'thumbnail', 'date'], 'required'],
            [['description', 'type'], 'string'],
            [['price', 'quantity'], 'integer'],
            [['date'], 'safe'],
            [['name', 'photo', 'thumbnail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'price' => 'Price',
            'quantity' => 'Quantity',
            'photo' => 'Photo',
            'thumbnail' => 'Thumbnail',
            'date' => 'Date',
        ];
    }
}
