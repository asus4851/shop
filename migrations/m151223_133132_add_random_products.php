<?php

use yii\db\Schema;
use yii\db\Migration;

class m151223_133132_add_random_products extends Migration
{
    public $tableName    = 'products';
    public $recordsCount = 15;

    public function getName()
    {

        return [
            'constructor',
            'pistol',
            'cube',
            'robot',
            'Barby',
        ];
    }

    public function getContent()
    {
        return [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vel vulputate libero. Nullam rutrum justo eget tellus eleifend, et bibendum leo semper. Vivamus blandit semper justo, a pellentesque arcu dictum at. Ut viverra mattis dapibus. Etiam cursus quam at erat pellentesque volutpat. Mauris scelerisque feugiat velit eu faucibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam vel diam et ex vestibulum mattis sit amet eget ipsum. Cras nec commodo leo, eu mollis sapien. Quisque scelerisque est a justo tincidunt gravida. Vivamus vel imperdiet velit, ut molestie tellus.',
        ];
    }


    public function safeUp()
    {

        $fullNameList = $this->getName();
        $description = $this->getContent();


        for( $i = 0; $i < $this->recordsCount; $i++ )
        {
            $this->insert($this->tableName, [
                'name'        => $fullNameList[rand(0, count($fullNameList) - 1)],
                'description' => $description[0],
                'type'        => 'usual',
                'price'       => rand(50, 25000),
                'quantity'    => rand(5, 1000),
                'photo'       => '/photos/toy.jpg',
                'thumbnail'   => '/thumbnails/toy.jpg',
                'date'        => date('Y-m-d', time() - rand(0, 1E6)),


            ]);

        }
    }

    public function safeDown()
    {
        $this->truncateTable($this->tableName);
    }
}
