<?php
add_action( 'wp_footer', function () { ?>
	<script>
        jQuery(document).ready(function($){
            $(document).ready(function(){
                let scrollPosition = 0;
                let menuOpen = false;
                $("#open-menu-popup").click(function(){
                    menuOpen = true;
                    $("#mobile-popup-menu").show();
                    $('html, body').css({
                        overflow: 'hidden',
                        height: '100%'
                    });
                    setTimeout(function() {
                        $("#close-menu-popup").addClass("open");
                        $("#mobile-popup-menu").css("opacity", 1);
                    }, 10);
                });
                $("#close-menu-popup").click(function(){
                    $("#mobile-popup-menu").hide();
                    $('html, body').css({
                        overflow: 'auto',
                        height: 'auto'
                    });
                    $(window).scrollTop(scrollPosition);
                    menuOpen = false;
                    setTimeout(function() {
                        $("#close-menu-popup").removeClass("open");
                    }, 10);
                });
                $(window).scroll(function(){
                    if(!menuOpen){
                        scrollPosition = $(window).scrollTop();
                    }
                });
            });
        });
	</script>
<?php } );