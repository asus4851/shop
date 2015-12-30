<?php
$countOrders = count($orders);
$totalSumm   = 0;
foreach( $orders as $order )
{
    $totalSumm = $totalSumm + $order->price;
}
echo "Было сделано всего " . $countOrders . " заказов на общую сумму в " . $totalSumm . " грн";
echo "<br>";
echo "Тут можно подключить библиотеку и попробовать вывести статистику графиком (Также в заказе сделать поле даты для более детальной статистики)";
