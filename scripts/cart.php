<?php
add_action( 'wp_footer', function () {
	if(get_the_ID() == 1342){?>
		<script>
            jQuery(document).ready(function($){
                $(document).ready(function(){
                    let place = $("#product-data .product-place").html();
                    let houseNumber = $("#product-data .product-houseNumber").html();
                    let images = $("#product-data .product-image-id").html();
                    let area = $("#product-data .product-area").html();
                    let rooms = $("#product-data .product-rooms").html();
                    let beds = $("#product-data .product-beds").html();
                    let total = $("#product-data .product-total").html();
                    let dbeds = $("#product-data .product-doubleBeds").html();
                    let sbeds = $("#product-data .product-singleBeds").html();
                    let baths = $("#product-data .product-baths").html();
                    let price_per_night = $("#product-data .product-pricePerNight").html();
                    let dateJoint = $("#product-data .product-dates .joint-date").html();
                    let datein = $("#product-data .product-dates .first-date").html();
                    let dateout = $("#product-data .product-dates .last-date").html();
                    let order_id = $("#product-data .product-order_id").html();
                    $("#product-heading mark").html(houseNumber);
                    $("#cart-kvm mark").html(area);
                    $("#cart-guests mark").html(beds);
                    $("#cart-bedrooms mark").html(rooms);
                    $("#cart-beds .total").html(beds);
                    $("#cart-beds .dual").html(dbeds);
                    $("#cart-beds .single").html(sbeds);
                    $("#cart-baths mark").html(baths);
                    $("#cart-booking-form input[name=room_id]").val($("#product-data .product-room_id").html());
                    $("#product-data #cart-images img").each(function(){
                        $("#image-carousel .elementor-image-carousel").append($(this));
                    });
                    $("#cart-coupon form .form-row-last button").html("<? e_("Anvend", "hello-elementor-child"); ?>");
                    $("#cart-coupon form .form-row-first input").attr("placeholder", "<? e_("Rabatkode.." , "hello-elementor-child"); ?>");

                    let guests = $("#product-data .product-guests").html();
                    let guest = $("#product-data .product-guest").html();
                    for(let i=1; i<=guests; i++){
                        if(i==guest){
                            $("#cart-booking-form select[name=guest]").append("<option selected value='"+i+"'>"+i+"</option>");
                        }
                        else{
                            $("#cart-booking-form select[name=guest]").append("<option value='"+i+"'>"+i+"</option>");
                        }
                    }

                    $("#cart-booking-form > div > form > div:nth-child(6) > div > div.jet-form__calculated-field > div.jet-form__calculated-field-val").html(total);
                    $("#cart-booking-form input[name=place]").val(place);
                    $("#cart-booking-form input[name=husnummer]").val(houseNumber);
                    $("#cart-booking-form input[name=billeder]").val(images);
                    $("#cart-booking-form input[name=areal]").val(area);
                    $("#cart-booking-form input[name=guests]").val(guests);
                    $("#cart-booking-form input[name=rooms]").val(rooms);
                    $("#cart-booking-form input[name=beds]").val(beds);
                    $("#cart-booking-form input[name=dobbelt-senge]").val(dbeds);
                    $("#cart-booking-form input[name=enkelt-senge]").val(sbeds);
                    $("#cart-booking-form input[name=baths]").val(baths);
                    $("#cart-booking-form input[name=price-per-night]").val(price_per_night);
                    $("#cart-booking-form input[name=_dates__in]").val(datein);
                    $("#cart-booking-form input[name=_dates__out]").val(dateout);
                    $("#cart-booking-form input[name=order_id]").val(order_id);
                    $("#cart-totals-bottom a").html("<? _e("Fortsæt", "hello-elementor-child"); ?>");
                    $("#cart-totals a").html("<? _e("Fortsæt", "hello-elementor-child"); ?>");

                    let carouselImageWidth = $("#image-carousel").find(".elementor-image-carousel img").width();
                    let carouselImageCount = $("#image-carousel .elementor-image-carousel img").length;
                    let carouselImagePosition = 0;

                    $("#image-carousel .cart-swiper-button-prev").click(function(){
                        carouselImagePosition--;
                        carouselSlide();
                    });

                    $("#image-carousel .cart-swiper-button-next").click(function(){
                        carouselImagePosition++;
                        carouselSlide();
                    });

                    function carouselSlide(){
                        if(carouselImagePosition > carouselImageCount-1){
                            carouselImagePosition = 0;
                        }
                        else if(carouselImagePosition < 0){
                            carouselImagePosition = carouselImageCount-1;
                        }
                        $("#image-carousel .elementor-image-carousel").css("transform", 'translateX('+-carouselImagePosition*carouselImageWidth+'px)');
                    }
                    $("#image-carousel .elementor-image-carousel-wrapper .swiper-slide-image").css("width", $("#image-carousel .elementor-image-carousel-wrapper").width());
                    $(window).on('resize', function(){
                        $("#image-carousel .elementor-image-carousel-wrapper .swiper-slide-image").css("width", $("#image-carousel .elementor-image-carousel-wrapper").width());
                    });

                    $('#cart-booking-form .date-picker-wrapper').click(function(){
                        if(dateJoint != $("#cart-booking-form #jet_abaf_field_range").val()){
                            $("#cart-booking-form .jet-form__submit").trigger("click");
                        }
                    });

                    $('#cart-booking-form form select').on('change', function() {
                        $("#cart-booking-form .jet-form__submit").trigger("click");
                    });
                });
            });
		</script>
	<?php }} );