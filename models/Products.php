<?php

namespace app\models;

use Imagine\Image\Box;
use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

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
    const IMAGE_WIDTH = 300;

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
            [['name', 'photo', 'thumbnail'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'name'        => 'Name',
            'description' => 'Description',
            'type'        => 'Type',
            'price'       => 'Price',
            'quantity'    => 'Quantity',
            'photo'       => 'Photo',
            'thumbnail'   => 'Thumbnail',
            'date'        => 'Date',
        ];
    }

    public function isHot()
    {
        return $this->type == 'hot';
    }

    public function getSale( $quantity )
    {
        if( $this->isHot() === false )
            return 0;

        if( $quantity < 1 )
            return 0;

        if( $quantity >= 1 && $quantity <= 5 )
            return 0.03;

        if( $quantity >= 6 && $quantity <= 10 )
            return 0.07;

        if( $quantity > 10 )
            return 0.1;
    }

    public function getFullPrice( $quantity ) // получаем полную стоимость продукта
    {
        return $this->price * $quantity * (1 - $this->getSale($quantity)); // если не hot то вернет 0 и скидка будет 0
        // тоже самое если меньше нуля, хоть я и добавил html5 min max для input type=number
    }

    public static function saveImage($model){
        $model->date = date('Y-m-d');

        $maxId     = Products::find()->select('max(id)')->scalar();
        $imageName = $maxId + 1; // (uniqid('img_')-как вариант, без нагрузки на бд)лучше вариант чем с датой, primary key всегда будет
        // уникальным + запрос вроде не сложный на выборку поскольку primary индексированый и взять максимальное
        // не составит большую нагрузку на бд

        $model->photo = UploadedFile::getInstance($model, 'photo');

        $fullName = Yii::getAlias('@webroot') . '/photos/' . $imageName . '.' . $model->photo->extension;
        $model->photo->saveAs($fullName);

        $img = Image::getImagine()->open($fullName);

        $size  = $img->getSize();
        $ratio = $size->getWidth() / $size->getHeight();

        $height = round(Products::IMAGE_WIDTH / $ratio);

        $box = new Box(Products::IMAGE_WIDTH, $height);
        $img->resize($box)->save(Yii::getAlias('@webroot') . '/thumbnails/' . $imageName . '.' . $model->photo->extension);


        $model->thumbnail = '/thumbnails/' . $imageName . '.' . $model->photo->extension;
        $model->photo     = '/photos/' . $imageName . '.' . $model->photo->extension;
        $save             = $model->save();
        if( $save )
        {
            return true;
        } else
        {
            die('product model was not save');
        }
    }
}
