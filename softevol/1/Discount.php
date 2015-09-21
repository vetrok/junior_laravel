<?php
/**
 * Class Discount
 *
 * Класс скидок, хранит в себе информацию о скидке применяемую к товарам
 */
class Discount
{
    protected $blackList;
    protected $productList;
    protected $discountAmount;
    protected $priority;
    protected $quantity;

    /**
     * @param array $products - массив продуктов к которым будет применятся скидка,
     * либо к которым не должна применятся, в случае передачи $quantity
     * @param float $discount - процент скидки отниманимой от товара
     * @param int $priority - приоритет скидки по сравнению с другими скидками
     * @param int $quantity - указывает к какому к-ву товара применяется скидка,
     * имеет смысл только в случае указания товаров не проходящих условий скидки
     * пример - покупка от 5-и любых товаров кроме товара с именем 'Z'(этот товар передаётся в $products)
     */
    public function __construct($products, $discount, $priority, $quantity = null)
    {
        $this->productList = is_array($products) ? $products : [$products];
        $this->discountAmount = (float)$discount;
        $this->priority = (int)$priority;
        if (isset($quantity)) {
            $this->quantity = (int)$quantity;
            $this->blackList = true;
        } else {
            $this->quantity = count($products);
        }
    }

    /**
     * Возвращает массив продуктов которые подлежат скидке, если 1 продукт может быть X or Y or Z
     * т.е. один из, то будет возвращён двумерный массив
     *
     * Если скидка применяется ко всем продуктам, либо ко всем кроме некоторых, возвратит false
     * @return mixed
     */
    public function getWhiteList()
    {
        if (isset($this->blackList)) {
            return false;
        }
        return $this->productList;
    }

    /**
     * Возвращает массив продуктов которые не должны учавствовать в скидке, если это условие скидки
     * @return mixed;
     */
    public function getBlackList()
    {
        if (!isset($this->blackList)) {
            return false;
        }
        return $this->productList;
    }

    /**
     * К-во товаров подлежащее скидке
     * @return int
     */
    public function countProducts()
    {
        return $this->quantity;
    }

    public function getDiscountAmount()
    {
        return $this->discountAmount * 0.01;
    }
}