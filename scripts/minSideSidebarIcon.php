<?php
add_action( 'wp_footer', function () { ?>

	<script>
        jQuery(document).ready(function($){
            $("#forside-service ul li:first-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Forside-Fill.svg">');
            $("#forside-service ul li:nth-child(2) .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/09/Begivenheder-fill.svg">');
            $("#forside-service ul li:last-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Servicekontor-fill.svg">');
            $("#oplevelser-restauranter ul li:first-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Oplevelser-fill.svg">');
            $("#oplevelser-restauranter ul li:last-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Restauranter-fill.svg">');
            $("#kiosk-service-udstyr ul li:first-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/09/Kiosk-fill.svg">');
            $("#kiosk-service-udstyr ul li:nth-child(2) .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Services-fill.svg">');
            $("#kiosk-service-udstyr ul li:last-child .elementor-icon-list-icon").append('<img src="https://elmirador.dk/wp-content/uploads/2022/08/Udstyr-fill.svg">');

            $("#sidemenu-profile ul li a").hover(function(){
                $(this).addClass("active");
            }, function(){
                $(this).removeClass("active");
            });
        });
	</script>
	<?php

	if(get_the_ID() == 5819){?>
		<script>
            jQuery(document).ready(function($){
                $("#forside-service ul li:first-child").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 6974){?>
		<script>
            jQuery(document).ready(function($){
                $("#forside-service ul li:last-child").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 8325){?>
		<script>
            jQuery(document).ready(function($){
                $("#forside-service ul li:nth-child(2)").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 6537){?>
		<script>
            jQuery(document).ready(function($){
                $("#oplevelser-restauranter ul li:first-child").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 7473){?>
		<script>
            jQuery(document).ready(function($){
                $("#oplevelser-restauranter ul li:last-child").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 6649){?>
		<script>
            jQuery(document).ready(function($){
                $("#kiosk-service-udstyr ul li:first-child").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 6977){?>
		<script>
            jQuery(document).ready(function($){
                $("#kiosk-service-udstyr ul li:nth-child(2)").addClass("active");
            });
		</script>
		<?php
	}
	else if(get_the_ID() == 6980){?>
		<script>
            jQuery(document).ready(function($){
                $("#kiosk-service-udstyr ul li:last-child").addClass("active");
            });
		</script>
		<?php
	}
});
