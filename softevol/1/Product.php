<?php

/**
 * Class Product
 *
 * Хранит информацию о продукте
 */
class Product
{
    protected $price;
    protected $name;

    /**
     * Конструктор клааса с присвоением имени и цены продукта
     *
     * @param string $name Имя продукта
     * @param float $price цена продукта
     */
    public function __construct($name, $price)
    {
        $this->name = (string)$name;
        $this->price = (float)$price;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        return $this->price = (float)$price;
    }
}