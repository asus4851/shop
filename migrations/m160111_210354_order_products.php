<?php

use app\models\Orders;
use yii\db\Migration;

class m160111_210354_order_products extends Migration
{
    public $tableName = 'order_products';

    public function up()
    {
        $this->createTable($this->tableName, [
            'order_id'   => $this->integer(5)->notNull(),
            'product_id' => $this->integer(5)->notNull(),
            'quantity'   => $this->integer(5)->notNull()->defaultValue(0),
        ]);

        $this->createIndex('order_product_uidx', $this->tableName, ['order_id', 'product_id'], true);

        $this->dropColumn(Orders::tableName(), 'quantity');
        $this->dropColumn(Orders::tableName(), 'product_id');
        $this->dropColumn(Orders::tableName(), 'type');
    }

    public function down()
    {
        $this->dropTable($this->tableName);
        $this->addColumn(Orders::tableName(), 'quantity', $this->integer(5)->notNull()->defaultValue(0));
        $this->addColumn(Orders::tableName(), 'product_id', $this->integer(5)->notNull());
        $this->addColumn(Orders::tableName(), 'type', $this->string(255)->notNull());
    }
}
