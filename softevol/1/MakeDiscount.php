<?php

/**
 * Class MakeDiscount
 *
 * Подсчитывает скидку для товаров
 *
 * 2 основных направления проверки скидки -
 * 1. проверяю товары которые подходят под список скидочных товаров
 * 2. проверяю товары которые не подходят под скидку - т.е. для всех товаров кроме некоторых (blackList)
 */
class MakeDiscount
{
    protected $discounts;
    protected $discountedProducts = [];
    protected $notDiscountedProducts = [];

    /**
     * Применяет скидку к товару, по приоритетности скидки
     * Проверки классов и сортировки опущены
     *
     * @param array $products массив продуктов
     * @param array $discounts массив скидок
     */
    public function getDiscount($products, $discounts)
    {
        $this->notDiscountedProducts  = $this->isArray($products);
        $this->discounts = $this->isArray($discounts);
        $makeDiscount = [];
        //Получаю массив скидок с именами продуктов
        foreach ($this->discounts as $dis) {
            //Нужно узнать я работа с чёрным или белым списком!
            if (($discArr = $dis->getWhiteList()) === false) {
                $discArr = $dis->getBlackList();
                //Устанавливаю флаг в значение true, чтобы было понятно что товары нужно исключать
                $doBlackList = true;
                //По умолчанию под скидку попал все товары!
                $products = $this->notDiscountedProducts;
            } else {
                $doBlackList = false;
            }
            //Каждый продукт
            foreach ($this->notDiscountedProducts as $j => $prod) {
                //Каждое имя продукта в массиве скидок
                foreach ($discArr as $key => $prodFromDisc) {
                    //Если в скидках в ячейке массива не скаляр, а массив то проверяю в нём
                    if ($doBlackList === true) {
                        //Если подходят все товары кроме некоторых, добавляю их в массив скидок
                        if ($prod->getName() == $prodFromDisc) {
                            unset($products[$j]);
                            continue;
                        }
                        $makeDiscount = $products;
                    } else {
                        if (is_array($prodFromDisc)) {
                            if (array_search($prod->getName(), $prodFromDisc) !== false) {
                                //Если нашлось во втором уровне массива значение
                                $makeDiscount[] = $prod;
                                unset($prodFromDisc);
                            }
                        } else {
                            if ($prod->getName() === $prodFromDisc) {
                                $makeDiscount[] = $prod;
                             //   unset($prodFromDisc);
                                unset($discArr[$key]);
                            }
                        }
                    }
                }
            }


            //Теперь если для скидки подошло несколько продуктов, я проверяю сколько эл-во осталось
            //в массиве скидки , и проверяю удовлетворяет ли к-ву скидочных товаров из скидки
            $superNum = $dis->countProducts();
            if ($superNum <= count($makeDiscount)) {
                foreach ($makeDiscount as $singleProd) {
                    //Устанавливаю новую цену товара
                    $singleProd->setPrice($singleProd->getPrice() - ($singleProd->getPrice() * $dis->getDiscountAmount()));
                    //Удаляю продукты которые уже прошли скидку из основного массива продуктов
                    foreach ($this->notDiscountedProducts as $k => $prodFromArr) {
                        if ($prodFromArr->getName() == $singleProd->getName()) {
                            unset($this->notDiscountedProducts[$k]);
                        }
                    }
                }
                $this->discountedProducts = array_merge($this->discountedProducts, $makeDiscount);
            }
            $makeDiscount = [];
        }
        return $this->getTotalPrice();
    }

    protected function isArray($value)
    {
        return is_array($value) ? $value : [$value];
    }

    protected function getTotalPrice()
    {
        $price = 0;
        $price += $this->fetchHelper($this->discountedProducts);
        $price += $this->fetchHelper($this->notDiscountedProducts);
        return $price;
    }

    protected function fetchHelper($arr)
    {
        $price = 0;
        if (!empty($arr)) {
            foreach ($arr as $prod) {
                $price += $prod->getPrice();
            }
        }
        return $price;
    }
}