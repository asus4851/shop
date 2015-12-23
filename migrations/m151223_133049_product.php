<?php

use yii\db\Schema;
use yii\db\Migration;

class m151223_133049_product extends Migration
{
    public $tableName = 'products';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'name'        => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'type'        => "ENUM('hot', 'usual') NOT NULL DEFAULT 'usual'",
            'price'       => $this->integer(10)->notNull(),
            'quantity'    => $this->integer(5)->notNull()->defaultValue(0),
            'photo'       => $this->string(255)->notNull(),
            'thumbnail'   => $this->string(255)->notNull(),
            'date'        => $this->date()->notNull(),
        ]);

        // При создании таблицы заказов нужно будет поставить индекс для связи таблиц
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
