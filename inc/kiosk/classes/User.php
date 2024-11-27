<?php

namespace inc\kiosk\classes;

class User extends \WP_User {
    private string $country;
    private string $countryCode;
    private string $address;
    private string $city;
    private string $zip;
    private string $phone;

    public function __construct($id = 0) {
        parent::__construct($id);
        $this->country = get_user_meta($id, 'country', true);
        $this->countryCode = get_user_meta($id, 'landcode', true);
        $this->address = get_user_meta($id, 'address', true);
        $this->city = get_user_meta($id, 'city', true);
        $this->zip = get_user_meta($id, 'postal', true);
        $this->phone = get_user_meta($id, 'phone', true);
    }

    public function getFullName(): string {
        return $this->first_name . " " . $this->last_name;
    }
    public function getFirstname(): string {
        return $this->first_name;
    }
    public function getLastname(): string {
        return $this->last_name;
    }
    public function getEmail(): string {
        return $this->user_email;
    }
    public function getCountry(): string {
        return $this->country;
    }
    public function getCountryCode(): string {
        return $this->countryCode;
    }
    public function getAddress(): string {
        return $this->address;
    }
    public function getCity(): string {
        return $this->city;
    }
    public function getZip(): string {
        return $this->zip;
    }
    public function getPhone(): string {
        return $this->phone;
    }

    public function setCountry(string $country): void {
        $this->country = $country;
    }
    public function setCountryCode(string $countryCode): void {
        $this->countryCode = $countryCode;
    }
    public function setAddress(string $address): void {
        $this->address = $address;
    }
    public function setCity(string $city): void {
        $this->city = $city;
    }
    public function setZip(string $zip): void {
        $this->zip = $zip;
    }
    public function setPhone(string $phone): void {
        $this->phone = $phone;
    }

    public function update() :void {
        $args = array(
            'ID'         => $this->ID,
            'user_email' => $this->getEmail(),
            'first_name' => $this->getFirstname(),
            'last_name'  => $this->getLastname()
        );
        wp_update_user( $args );

        update_user_meta($this->ID, 'country', $this->country);
        update_user_meta($this->ID, 'landcode', $this->countryCode);
        update_user_meta($this->ID, 'address', $this->address);
        update_user_meta($this->ID, 'city', $this->city);
        update_user_meta($this->ID, 'postal', $this->zip);
        update_user_meta($this->ID, 'phone', $this->phone);
    }







    public function getOrders() :array {
        $orders = array();
        $args = array(
            'post_type' => 'order',
            'post_status' => 'publish',
            'author' => $this->ID,
            'posts_per_page' => -1
        );
        $query = new \WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $order = new Order(get_post_meta(get_the_ID(), 'status', true), $this, unserialize(get_post_meta(get_the_ID(), 'products', true)));
                $order->id = get_the_ID();
                $orders[] = $order;
            }
        }
        wp_reset_postdata();
        return $orders;
    }

    public function setFirstname(string $firstname): void {
        $this->first_name = $firstname;
    }

    public function setLastname(string $lastname): void {
        $this->last_name = $lastname;
    }

    public function setEmail(string $email): void {
        $this->user_email = $email;
    }

}