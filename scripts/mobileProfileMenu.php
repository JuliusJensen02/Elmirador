<?php
add_action( 'wp_footer', function () { ?>
	<script>
        jQuery(document).ready(function($){
            $("#mobile-profile-menu a").click(function(){
                $("#sidemenu-profile").toggleClass("show");
                $("#mobile-profile-menu").toggleClass("show");
            });

            $("#close-profile-menu").click(function(){
                $("#sidemenu-profile").removeClass("show");
                $("#mobile-profile-menu").removeClass("show");
            });

            $(".jet-blocks-cart__heading-link").attr("href", "https://elmirador.dk/profil/kasse/");
        });
	</script>
<?php } );