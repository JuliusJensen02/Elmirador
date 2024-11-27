<?php

namespace inc\kiosk\classes;

class ProductOrder extends Product {
    private int $quantity;

    public function __construct(int $id, int $quantity) {
        Parent::__construct($id);
        $this->quantity = $quantity;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getTotal(): float {
        return $this->quantity * $this->price;
    }

    public function getTotalWithoutTax(): float{
        return $this->getPriceWithoutTax() * $this->quantity;
    }
}