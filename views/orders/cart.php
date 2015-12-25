<?php
/**
 * Created by PhpStorm.
 * User: r1
 * Date: 25.12.2015
 * Time: 13:17
 */
?>

<?php foreach( $model as $item )
{
    echo 'Заказал пользователь ' . $item['user_id'];
    echo "<br>";
    echo 'id заказанного товара ' . $item['product_id'];
    echo "<br>";
    echo "<hr>";
}
?>
