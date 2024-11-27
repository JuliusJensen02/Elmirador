<?php
add_action( 'wp_footer', function () {
	$post = get_post();
	if(/*$post->post_type == "boliger" && is_archive()*/false){?>
		<script>
            jQuery(document).ready(function($){
                document.addEventListener( 'jet-smart-filters/inited', function( initEvent ) {
                    waitForElm(".jet-listing-grid__item a").then((elm) => {
                        changeListingUrl();
                    });
                } );
                window.JetSmartFilters.events.subscribe('ajaxFilters/end-loading', changeListingUrl);

                function changeListingUrl(){
                    let dateRange = document.querySelector("input.jet-date-range__input").value.split("-");
                    if(dateRange.length > 1) {
                        let dateFrom = dateRange[0].split(".").reverse().join(".");
                        let dateTo = dateRange[1].split(".").reverse().join(".");
                        let dateStr = dateFrom + " - " + dateTo;
                        document.querySelectorAll(".jet-listing-grid__item a").forEach((element) => {
                            let searchParams = new URLSearchParams(window.location.search);
                            if (searchParams.has("lang")) {
                                element.href = element.href + "&_dates=" + dateStr;
                            } else {
                                element.href = element.href + "?_dates=" + dateStr;
                            }
                        });
                    }
                }

                //
                //Mobile
                //
                $("#open-close-filter .elementor-button-icon").append('<svg class="openSvg" version="1.1" id="Lag_1" xmlns:svgjs="http://svgjs.com/svgjs" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><style type="text/css">.st0{fill:#1A242F;}</style><g><path class="st0" d="M486.4,230.4H25.6C11.5,230.4,0,241.9,0,256c0,14.1,11.5,25.6,25.6,25.6h460.8c14.1,0,25.6-11.5,25.6-25.6C512,241.9,500.5,230.4,486.4,230.4z"/></g></svg>')
                document.getElementById("open-close-filter").addEventListener("click", function(){
                    document.getElementById("filter-container").classList.toggle("open");
                    this.classList.toggle("open");
                    if(document.getElementById("filter-container").classList.contains("open")){
                        this.querySelector(".elementor-button-text").textContent = "<? _e("Luk filtre", "hello-elementor-child"); ?>";
                    }
                    else{
                        this.querySelector(".elementor-button-text").textContent = "<? _e("Åbn filtre", "hello-elementor-child"); ?>";
                    }
                });

                function waitForElm(selector) {
                    return new Promise(resolve => {
                        if (document.querySelector(selector)) {
                            return resolve(document.querySelector(selector));
                        }

                        const observer = new MutationObserver(mutations => {
                            if (document.querySelector(selector)) {
                                resolve(document.querySelector(selector));
                                observer.disconnect();
                            }
                        });

                        observer.observe(document.body, {
                            childList: true,
                            subtree: true
                        });
                    });
                }
            });
		</script>
	<?php }} );