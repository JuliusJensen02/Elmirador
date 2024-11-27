<?php
add_action( 'wp_footer', function () {
	$post_data = get_the_category(get_the_ID());
	foreach($post_data as $key => $value){
		if($value->name == "Resort Underside"){?>
			<script>
                jQuery(document).ready(function($){
                    let pageURL = $(location).attr("href");
                    let imageUrls = [];
                    let counter = 1;
                    let countImages = $("#smallImages .elementor-gallery__container .e-gallery-item").length;
                    if(pageURL == "https://elmirador.dk/infinitypool/"){
                        setImage(imageUrls[counter-1]);
                        let interval;
                        let timer = function(){
                            interval = setInterval(function(){
                                counter++;
                                if(counter > countImages){
                                    counter = 1;
                                }
                                setImage(imageUrls[counter-1]);
                            }, 6500);
                        };
                        timer();

                        $(".elementor-gallery-item__overlay").on("click", function(){
                            counter = $(".e-gallery-item").index($(this).parent()) + 1;
                            setImage(imageUrls[counter-1]);
                        });

                        $.each($("#smallImages .e-gallery-item"), function(i, val){
                            imageUrls.push($(this).find(".e-gallery-image").attr("data-thumbnail"));
                            $(this).find(".e-gallery-image").attr("id", i);
                        });

                        function setImage(imgsrc){
                            $("#bigImage img").fadeOut(200, function(){
                                $("#bigImage img").attr("src", imgsrc);
                                $("#bigImage img").attr("srcset", imgsrc);
                            })
                                .fadeIn(200);
                            $(".hover-overlay").removeClass("hover-overlay");
                            $("#smallImages .e-gallery-item:nth-child(" + counter + ")>.elementor-gallery-item__overlay").addClass("hover-overlay");
                        }
                    }
                });
			</script>
		<?php }}} );