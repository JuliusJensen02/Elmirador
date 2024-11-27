<?php
add_action( 'wp_footer', function () {
	if(get_the_ID() == 33 || get_the_ID() == 14541){?>
		<script>
        jQuery(document).ready(function($){
            /*Manuel translation*/
            const searchParams = new URLSearchParams(window.location.search);
            const lang = searchParams.get("lang");
            if(lang == "en"){
                $(".apply-filters__button").text("Search");
                $(".jet-smart-filters-apply-button>div").attr("data-redirect-path", "boliger/?lang=en");
            }
        });
		</script>
	<?php }} );