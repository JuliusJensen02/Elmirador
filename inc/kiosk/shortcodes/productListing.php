<?php

use inc\kiosk\classes\Product;
add_shortcode('products', 'productListing');
function productListing() :void{
    if(isset($_GET["cat"])){
        $products = get_posts(array('post_type' => 'produkter', 'numberposts' => -1, 'meta_key' => 'category', 'meta_value' => $_GET["cat"]));
        if(count($products) == 0){
            echo "<p>Der er ingen produkter i denne kategori</p>";
        }
        else {
            echo "<div class='products'>";
            foreach ($products as $product) {
                $product = new Product($product->ID);
                echo "<div class='product'>";
                echo "<div class='productContainer'>";
                echo "<img class='kioskProductImage' src='{$product->getImageUrl()}' alt='{$product->getName()}'>";
                echo "<h2 class='productName'>{$product->getName()}</h2>";
                echo "<p class='productPrice'>€{$product->getPrice()} / {$product->getUnit()}</p>";
                echo "<p class='productDescription'>{$product->getDescription()}</p>";
                echo "</div>";
                if ($product->isInStock()) {
                    echo "<div class='addContainer'>";
                    echo "<input class='quantity' type='number' value='1' min='1' max='100'>";
                    echo "<button class='add-to-cart' value='{$product->getId()}'>Tilføj til kurv</button>";
                    echo "</div>";
                } else {
                    echo "<p>Ikke på lager</p>";
                }
                echo "</div>";
            }
        }
    }
    else{
        $products = get_posts(array('post_type' => 'produkter', 'numberposts' => -1));
        $categories = array();
        echo "<div class='categories'>";
        foreach ($products as $product) {
            $product = new Product($product->ID);
            if(!in_array($product->getCategory(), $categories)){
                $categories[] = $product->getCategory();
                $categoryName = $product->getCategory();
                $categoryName = str_replace("_", " ", $categoryName);
                $categoryName = ucfirst($categoryName);
                echo "<a href='?cat={$product->getCategory()}'>{$categoryName}</a>";
            }
        }
    }
    echo "</div>";
}