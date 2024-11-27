<?php
/*add_action( 'wp_head', function () {
if (is_product_category()) {?>
<script>
jQuery(document).ready(function($){
    let pageURL = $(location).attr("href").split('kiosk/')[1].replace(/[#_/\*]/g, '').replace(/[-]/g, ' ');
    let pageURLUppercase = pageURL.charAt(0).toUpperCase() + pageURL.slice(1);

    $("#main .woocommerce-breadcrumb").html("<a href='https://elmirador.dk/profil/kiosk/'>kiosk</a> // <a class='site'>"+pageURLUppercase+"</a>");
    $("#main .woocommerce-products-header").prepend("<img src='https://elmirador.dk/wp-content/uploads/2022/07/Kiosk-outline.svg'>");

    $("form").css({
        "display" : "flex",
        "align-items" : "center"
    });

    $(".open-product-popup").click(function(){
        $("#"+$(this).attr("id")+"c").toggleClass("open");
    });
});
</script>
<style>
	#primary{
		padding-top: 50px !important;
	}

	#primary #main{
		padding-left: 5%;
		padding-right: 5%;
	}

	#primary #main .woocommerce-result-count, #primary #main .woocommerce-ordering, #primary #main .woocommerce-notices-wrapper, #primary #main .woocommerce-message{
		display: none !important;
	}

	#primary #main .woocommerce-breadcrumb .site{
		color: #ff945b;
		font-size: 15px;
	}

	#primary #main .woocommerce-products-header__title{
		font-size: 20px;
		margin-left: 15px;
	}

	#primary #main .woocommerce-products-header{
		display: flex;
		flex-direction: row;
		max-width: 300px;
		border-bottom: 1px solid #BBBBBB;
		padding-bottom: 15px;
		margin-bottom: 30px;
	}

	#primary #main>span{
		width: 100%;
		position: absolute;
		left: 0;
		top: 30px;
		padding: 0 5%;
		display: flex;
		justify-content: space-between;
	}

	#primary #main>span>a{
		display: flex;
		align-items: center;
		transition: 0.3s;
	}

	#primary #main>span>a:hover{
		transform: scale(1.03);
	}

	#primary #main span .back{
		color: white;
		background-color: #BBBBBB;
		padding: 12px 16px;
		border-radius: 8px;
	}

	#primary #main span .back img{
		transform: rotate(180deg);
		margin-right: 10px;
	}

	#primary #main span .cart{
		color: white;
		background-color: #FF945B;
		padding: 12px 16px;
		border-radius: 8px;
	}

	#primary #main span .cart img{
		margin-left: 10px;
	}

	@media (max-width: 1250px){
		#primary #main .elementor-widget-jet-woo-builder-archive-add-to-cart>div{
			margin-left: 10px;
		}

		#primary #main .elementor-widget-button>div{
			margin-right: 10px;
		}

		#primary #main .add_to_cart_button{
			padding-left: 5px;
		}

		#primary #main .qty{
			width: 30px !important;
		}-webkit-inner-spin-button

		#primary #main input::-webkit-outer-spin-button,
		#primary #main input::-webkit-inner-spin-button {
  			-webkit-appearance: none;
  			margin: 0;
		}

		#primary #main .quantity{
			width: auto;
		}
	}

	@media (max-width: 1024px){
		#primary #main{
			max-width: none;
		}
	}

</style>
<?php } });*/