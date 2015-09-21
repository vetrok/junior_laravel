<?php
/**
 * Входной скрипт приложения для оформления скидок товарам
 * Большинство проверок, и побочных действий опущены,
 * некоторые моменты не оптимизированны для больших количсетв товаров\скидок - оставлена только логика выполнения
 * Динамически учтено всё - и цена товара, и процент скидки и к-во скидок, какие товары учавствуют в скидке,
 * правила скидок - к каким применять к каким не применять...
 * Также опущены комментарии для очевидных методов и свойств в виду тестового задания
 *
 * @author Vitaliy Yatsenko <vetrok87@gmail.com>
 * @version 1.0
 */
function __autoload($className) {
    require_once('./' . $className . '.php');
}

$products = [
    new Product('A', 100),
    new Product('M', 100),
    new Product('B', 100),
    new Product('C', 100),
    new Product('E', 100),
    new Product('D', 100),
    new Product('F', 100),
    new Product('G', 100),
];

//Продукты с чёрного списка, которые не должны попадать под скидку
$notMatchProducts = ['A', 'C'];

$discounts = [
    //Опущена сортировка по приоритетности , приоритетом является просто положение в массиве - кто первый то и приоритетней
    new Discount($notMatchProducts, 20, 7, 5),
    new Discount($notMatchProducts, 10, 6, 4),
    new Discount($notMatchProducts, 5, 5, 3),
    new Discount(['A',['K', 'L', 'M']], 5, 4),
    new Discount(['E', 'F', 'G'], 5, 3),
    new Discount(['D', 'E'], 5, 2),
    new Discount(['A', 'B'], 10, 1),
];

$discount = new MakeDiscount();
echo $discount->getDiscount($products, $discounts);