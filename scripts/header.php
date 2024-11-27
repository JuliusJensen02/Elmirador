<?php
add_action( 'wp_footer', function () {
    if(get_the_ID() == 2316){
    ?>
        <script>
            jQuery(document).ready(function($){
                $(".elementor-element-86c74cb").css("background-color", "#F6F6F6CC");
                $(".elementor-element-4bda0c1").css("background-color", "#FFFFFFCC");
            });
        </script>
<?php
    }
    else{
	    ?>
        <script>
            jQuery(document).ready(function($){
                $(".elementor-element-86c74cb").css("background-color", "#F6F6F6FF");
                $(".elementor-element-4bda0c1").css("background-color", "#FFFFFFFF");
            });
        </script>
	    <?php
    }
	if(get_post_type() == "boliger" && is_single()){
		?>
        <script>
            jQuery(document).ready(function($){
                if($(window).width() < 768){
                    $(".elementor-element-3bd7c8be").hide();
                }
            });
        </script>
		<?php
	}
	?>
    <script>
        jQuery(document).ready(function($){
            $("#prev-page-button").click(function(){
                window.history.back();
            });
        });
    </script>
	<?php
} );