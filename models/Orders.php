<?php

namespace app\models;

use dektrium\user\models\User;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $status
 * @property integer $confirm
 * @property integer $price
 * @property User $user
 * @property Products[] $products
 */
class Orders extends \yii\db\ActiveRecord
{
    const PRODUCT_RELATIONS_TABLE = 'order_products';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'price', 'status', 'confirm'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User ID',
            'status'     => 'Status',
            'confirm'    => 'Confirmed',
            'price'      => 'Price',
        ];
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return Products[]
     */
    public function getProducts()
    {
        return $this->hasMany(Products::className(), ['id' => 'product_id']) // указываем что у нас будет связь id (Products) к product_id (таблица связи)
            ->viaTable(self::PRODUCT_RELATIONS_TABLE, ['order_id' => 'id'])->all(); // через таблицу связи свзяь order_id (таблица связи) к orders
        // в итоге получаем все продукты, при связи много продуктов в 1 заказе
    }

    public function getQuantity($productId = null)  // считаем количество
    {
        $quantityQuery = (new Query())->select('quantity')
            ->from(self::PRODUCT_RELATIONS_TABLE)
            ->where(['order_id' => $this->id]);

        if($productId)
            return $quantityQuery->andWhere(['product_id' => $productId])->scalar();

        return $quantityQuery->sum('quantity');
    }

    public function calculateGrandTotal() //считаем сумму всего заказа
    {
        $products = $this->getProducts();
        $sum      = 0;
        foreach( $products as $product )
        {
            $quantity = $this->getQuantity($product->id);
            $sum += $product->getFullPrice($quantity);
        }

        return $sum;
    }

    public function addProducts( $productId, $quantity )
    {
        $insert = Yii::$app->db->createCommand()->insert(self::PRODUCT_RELATIONS_TABLE, [
            'order_id'   => $this->id,
            'product_id' => $productId,
            'quantity'   => $quantity,
        ])->execute();

        return $insert;
    }

    public function removeProducts( $productId )
    {
        $remove = Yii::$app->db->createCommand()->delete(self::PRODUCT_RELATIONS_TABLE, [
            'order_id'   => $this->id,
            'product_id' => $productId,
        ])->execute();

        return $remove;
    }

    public function updateProducts( $productId, $quantity )
    {
        $update = Yii::$app->db->createCommand()->update(self::PRODUCT_RELATIONS_TABLE, [
            'quantity' => $quantity,
        ], [
            'order_id'   => $this->id,
            'product_id' => $productId,
        ])->execute();

        return $update;
    }
}


