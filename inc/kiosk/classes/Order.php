<?php

namespace inc\kiosk\classes;

use DateTime;


class Order {
    public int $id;
    private string $status;
    private User|false $user;
    private array $products;
    private DateTime|null $dateTime;
    private string|null $note;
    private bool|null $isFastDelivery;
    private string|null $location;
    private string|null $firstname;
    private string|null $lastname;
    private string|null $country;
    private string|null $countrycode;
    private string|null $city;
    private string|null $zip;
    private string|null $address;
    private string|null $phone;
    private string|null $email;
    private string $createdDate;
    private string|null $invoice;

    public function __construct(int $id) {
        $this->id = $id;
        global $wpdb;
        $table = $wpdb->prefix . "jet_cct_ordre";
        $result = $wpdb->get_results("SELECT * FROM $table WHERE _ID = $id")[0];
        $this->status = $result->status;
        $this->user = new User($result->user);
        $this->dateTime = new DateTime($result->datetime);
        $this->note = $result->comment;
        $this->isFastDelivery = $result->fastdelivery;
        $this->location = $result->location;
        $this->firstname = $result->firstname;
        $this->lastname = $result->lastname;
        $this->country = $result->country;
        $this->countrycode = $result->countrycode;
        $this->city = $result->city;
        $this->zip = $result->zip;
        $this->address = $result->address;
        $this->phone = $result->phone;
        $this->email = $result->email;
        $this->createdDate = $result->cct_created;
        $this->invoice = $result->invoice;


        foreach (unserialize($result->products) as $product) {
            $product_order = new ProductOrder($product["product"], $product["quantity"]);
            $this->products[$product["product"]] = $product_order;
            if(isset($product["price"])){
                $product_order->setPrice($product["price"]);
            }
        }
    }

    public function getID(): int {
        return $this->id;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getUser(): User|false {
        return $this->user;
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime {
        return $this->dateTime;
    }

    public function getNote(): string {
        return $this->note;
    }

    public function isFastDelivery(): bool {
        return $this->isFastDelivery;
    }

    public function getLocation(): string {
        return $this->location;
    }

    public function getCreationDate(): string {
        $date = new DateTime($this->createdDate);
        return $date->format('d/m-Y');
    }

    /**
     * @return ProductOrder[]
     */
    public function getProducts(): array {
        return $this->products;
    }

    public function getTotal(): float {
        $total = 0;
        foreach ($this->getProducts() as $product) {
            $total += $product->getTotal();
        }
        return $total;
    }

    public function getProductsForDB(): string {
        $products = array();
        foreach ($this->getProducts() as $product) {
            $products[] = array(
                'product' => $product->getId(),
                'quantity' => $product->getQuantity(),
                'price' => $product->getPrice()
            );
        }
        return serialize($products);
    }

    public function getInvoice(): string|null {
        return $this->invoice;
    }

    public function setInvoice(string $invoiceURL): void{
        global $wpdb;
        $table = $wpdb->prefix . "jet_cct_ordre";
        $data = array(
            'invoice' => $invoiceURL
        );
        $where = array('_ID' => $this->id);
        $wpdb->update($table, $data, $where);
    }

    public function setStatus(string $status): void{
        global $wpdb;
        $table = $wpdb->prefix . "jet_cct_ordre";
        $data = array(
            'status' => $status,
        );
        $where = array('_ID' => $this->id);
        $wpdb->update($table, $data, $where);
    }

    public function save(): void {
        global $wpdb;
        $table = $wpdb->prefix . "jet_cct_ordre";
        $data = array(
            'status' => $this->status,
            'user' => $this->user->ID,
            'products' => $this->getProductsForDB(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'email' => $this->user->getEmail(),
            'phone' => $this->user->getPhone(),
            'address' => $this->user->getAddress(),
            'city' => $this->user->getCity(),
            'postal' => $this->user->getZip(),
            'country' => $this->user->getCountry(),
            'landcode' => $this->user->getCountryCode(),
            'invoice' => $this->invoice
        );

        $where = array('_ID' => $this->id);
        $wpdb->update($table, $data, $where);
    }

    public function insert(): void {
        global $wpdb;
        $table = $wpdb->prefix . "jet_cct_ordre";
        $data = array(
            'cct_status' => 'publish',
            'status' => $this->status,
            'user' => $this->user->ID,
            'products' => $this->getProductsForDB(),
            'firstname' => $this->user->getFirstname(),
            'lastname' => $this->user->getLastname(),
            'email' => $this->user->getEmail(),
            'phone' => $this->user->getPhone(),
            'address' => $this->user->getAddress(),
            'city' => $this->user->getCity(),
            'postal' => $this->user->getZip(),
            'country' => $this->user->getCountry(),
            'landcode' => $this->user->getCountryCode()
        );
        $wpdb->insert($table, $data);
    }
}
