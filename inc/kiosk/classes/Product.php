<?php

namespace inc\kiosk\classes;
class Product {
    protected int $id;
    protected string $name;
    protected string $description;
    protected float $price;
    protected string $image;
    protected string $category;
    protected bool $inStock;
    protected float $vat;
    protected string $unit;

    public function __construct(int $id) {
        $this->id = $id;
        $post = get_post($id);
        $this->name = $post->post_title;
        $this->description = get_post_meta($id, 'description', true);
        $this->price = get_post_meta($id, 'price', true);
        $this->image = get_post_meta($id, 'image', true);
        $this->category = get_post_meta($id, 'category', true);
        $this->inStock = get_post_meta($id, 'in_stock', true);
        $this->vat = get_post_meta($id, 'vat', true)/100;
        $this->unit = get_post_meta($id, 'unit', true);
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getImage(): string {
        return $this->image;
    }

    public function getCategory(): string {
        return $this->category;
    }

    public function isInStock(): bool {
        return $this->inStock;
    }

    public function setPrice(float $price): void {
        $this->price = $price;
    }

    public function getTax(): int{
        return $this->vat;
    }

    public function getPriceWithoutTax(): float{
        return $this->price / (1 + $this->vat);
    }

    public function getUnit(): string{
        return $this->unit;
    }

    public function getImageUrl(): string{
        return wp_get_attachment_url($this->image);
    }

}