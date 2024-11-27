<?php
add_action( 'wp_footer', function () { ?>
	<script>
        jQuery(document).ready(function($){
            $(document).ready(function(){
                $("#loginpage-form .jet-login-lost-password-link").insertAfter("#loginpage-form .login-remember");
                $("#loginpage-form .jet-login-lost-password-link").attr("href", "https://elmirador.dk/glemt-adgangskode/");
            });
        });
	</script>
<?php } );