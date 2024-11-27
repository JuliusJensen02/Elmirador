<?php
add_action( 'wp_footer', function () {
    if(get_the_ID() == 1344 || get_the_ID() == 5919 || get_the_ID() == 6238 || get_the_ID() == 6537 || get_the_ID() == 6649 || get_the_ID() == 6974 || get_the_ID() == 6977 || get_the_ID() == 6980){
    ?>
	<script>
        jQuery(document).ready(function($){
            let lastScrollTop = 0;
            $("div").scroll(function(event){
                let st = $(this).scrollTop();
                if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                    $(".elementor-1344, .elementor-5919, .elementor-6238 , .elementor-6537, .elementor-6649, .elementor-6974, .elementor-6977, .elementor-6980").addClass("extendMainPage");
                    $("#bottom-bar-profile").addClass("hidden");
                }
                else if(st <= 10) {
                    $(".elementor-1344, .elementor-5919, .elementor-6238 , .elementor-6537, .elementor-6649, .elementor-6974, .elementor-6977, .elementor-6980").removeClass("extendMainPage");
                    $("#bottom-bar-profile").removeClass("hidden");
                }
                else if(st > lastScrollTop){
                    $(".elementor-1344, .elementor-5919, .elementor-6238 , .elementor-6537, .elementor-6649, .elementor-6974, .elementor-6977, .elementor-6980").addClass("extendMainPage");
                    $("#bottom-bar-profile").addClass("hidden");
                }
                else{
                    $(".elementor-1344, .elementor-5919, .elementor-6238 , .elementor-6537, .elementor-6649, .elementor-6974, .elementor-6977, .elementor-6980").removeClass("extendMainPage");
                    $("#bottom-bar-profile").removeClass("hidden");
                }
                lastScrollTop = st;
            });
        });
	</script>
<?php }} );