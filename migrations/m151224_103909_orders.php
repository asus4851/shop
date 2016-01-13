<?php

use yii\db\Schema;
use yii\db\Migration;

class m151224_103909_orders extends Migration
{
    public $tableName = 'orders';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer(5)->notNull(),
            'product_id' => $this->integer(5)->notNull(),
            'quantity'   => $this->integer(5)->notNull()->defaultValue(0),
            'status'     => $this->integer(1)->notNull()->defaultValue(0),
            'type'     => $this->string(255)->notNull(),
            'price'     => $this->integer(5)->notNull(),
            'confirm'    => $this->integer(1)->notNull()->defaultValue(0),
        ]);

        // При создании таблицы заказов нужно будет поставить индекс для связи таблиц
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
