<?php
add_shortcode('kioskBreadcrumbs', 'kioskBreadcrumbs');
function kioskBreadcrumbs(): void {
    if(isset($_GET["cat"])){
        echo "<div class='breadcrumbs'>";
        echo "<a href='/kiosk'>Kiosk</a>";
        echo "<span> // </span>";
        $categoryName = $_GET["cat"];
        $categoryName = str_replace("_", " ", $categoryName);
        $categoryName = ucfirst($categoryName);
        echo "<a href='?cat={$_GET["cat"]}'>{$categoryName}</a>";
        echo "</div>";
    }
}