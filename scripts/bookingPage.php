<?php
add_action( 'wp_footer', function () {
	$post = get_post();
	if($post->post_type == "boliger" && is_single()){?>
        <style>
            .jet-sm-gb-594a8ba7-827a-4a94-9150-599a9f07dc58{
                display: none;
            }
        </style>
		<script>
            jQuery(document).ready(function($){
                $("#accordion-season-price-button .elementor-icon").prepend('<svg class="openSvg" version="1.1" id="Lag_1" xmlns:svgjs="http://svgjs.com/svgjs" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><style type="text/css">.st0{fill:#1A242F;}</style><g><path class="st0" d="M486.4,230.4H25.6C11.5,230.4,0,241.9,0,256c0,14.1,11.5,25.6,25.6,25.6h460.8c14.1,0,25.6-11.5,25.6-25.6C512,241.9,500.5,230.4,486.4,230.4z"/></g></svg>');
                $("#final-booking-form .jet-sm-gb-92461b97-0f3a-4e62-a613-50b17e3b303b .jet-form-builder__calculated-field-val").text(0);
                $("#final-booking-form .jet-sm-gb-92461b97-0f3a-4e62-a613-50b17e3b303b input").val(0);
                $("#accordion-season-price-button").click(function(){
                    $("#accordion-season-price-button").toggleClass("accordion-open");
                    $("#accordion-season-price-container").toggleClass("accordion-open");
                });

                waitForElm('.elementor-widget-jet-booking-calendar .date-picker-wrapper').then((calWidget) => {
                    calWidget.querySelectorAll(".next").forEach((elm) => {
                        elm.innerHTML = "<img src='https://elmirador.dk/wp-content/uploads/2022/07/arrow-right-grey-new.svg'>";
                    });
                    calWidget.querySelectorAll(".prev").forEach((elm) => {
                        elm.innerHTML = "<img src='https://elmirador.dk/wp-content/uploads/2022/07/arrow-left-grey-new.svg'>";
                    });
                });

                let curSym = "€";

                $("#final-booking-form .jet-sm-gb-04b9926d-4317-4d84-91bf-2c1cacbfb219 .field-type-checkbox-field label").each(function(){
                    let value = $(this).find("input").attr("data-calculate");
                    $(this).find("input").attr("data-original", value);
                    $(this).append("<span><span class='prefix'>"+curSym+"</span><span class='ydelse-price'>"+value+"</span></span>");
                });

                $("#ekstra-services-total").html(curSym + Math.round($(".field-type-calculated.hidden_ekstra_services input").val()));
                $("#final-booking-form").click(function(){
                    $("#ekstra-services-total").html(curSym + Math.round($(".field-type-calculated.hidden_ekstra_services input").val()));
                });


                /*Extra udstyr function */
                $("#ydelse-listing-grid-startpakker a.elementor-button, #ydelse-listing-grid-udstyr a.elementor-button, #ydelse-listing-grid-ydelser a.elementor-button").click(function(){   //Set click events on the listing buttons
                    $(this).toggleClass("added");																						   //Add the class
                    let id = $(this).closest(".ydelses-container").attr("id");
                    id = id.replace(/\D/g, '');
                    document.querySelectorAll("#final-booking-form .extra-services-checkboxes").forEach((element) => {
                        if(element.value == id){
                            element.parentElement.click();
                            $(element.parentElement.parentElement).toggleClass("show");
                        }
                    });

                    if($(this).hasClass("added")){
                        $(this).find(".elementor-button-text").html("<? _e("Fjern", "hello-elementor-child"); ?>");
                        $(this).find(".elementor-button-icon").html('<svg xmlns:svgjs="http://svgjs.com/svgjs" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Lag_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><style type="text/css">.st0{fill:#FFFFFF;}</style><g><path class="st0" d="M486.4,230.4H25.6C11.5,230.4,0,241.9,0,256c0,14.1,11.5,25.6,25.6,25.6h460.8c14.1,0,25.6-11.5,25.6-25.6   C512,241.9,500.5,230.4,486.4,230.4z"/></g></svg>');
                    }
                    else{
                        $(this).find(".elementor-button-text").html("<? _e("Tilføj til bestilling", "hello-elementor-child"); ?>");
                        $(this).find(".elementor-button-icon").html('<svg xmlns="http://www.w3.org/2000/svg" width="23.882" height="12" viewBox="0 0 23.882 12"><g id="right-arrow_5_" data-name="right-arrow (5)" transform="translate(0 -126.369)"><g id="Group_31" data-name="Group 31" transform="translate(0 127.369)"><g id="Group_30" data-name="Group 30" transform="translate(0 -1)"><path id="Path_24" data-name="Path 24" d="M23.724,132.987h0L18.3,127.559a.543.543,0,1,0-.765.765l4.5,4.5H.543a.543.543,0,0,0,0,1.086H22.031l-4.5,4.5a.543.543,0,1,0,.765.765l5.428-5.428A.543.543,0,0,0,23.724,132.987Z" transform="translate(0 -127.369)" fill="#fff"></path></g></g></g></svg>');
                    }
                    $("#desc-price-total").html(curSym + $(".jet-sm-gb-92461b97-0f3a-4e62-a613-50b17e3b303b input").val());    //sets the price in the bottom of extra ydelser
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


                /*Pages triggers*/
                $("#mobile-floating-next-step").click(function(){
                    let element = $("#final-booking-form").parent();
                    let headerOffset = 50;
                    let elementPosition = element[0].getBoundingClientRect().top;
                    let offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                });


                class Flow{
                    constructor(){
                        this.date = document.querySelector("#final-booking-form .jet-sm-gb-21b533c2-a124-4e65-ad9a-4978742426c0");
                        this.topInfo = document.querySelectorAll("#final-booking-form .top-info");
                        this.page0 = document.getElementById("step-0-page");
                        this.page0top = document.getElementById("step-0-page-top");
                        this.page1 = document.getElementById("step-1-page");
                        this.page2 = document.getElementById("step-2-page");
                        this.page3 = document.getElementById("step-3-page");
                        this.nextButton = document.querySelector(".step-button");
                        this.button2b = document.getElementById("second-page-button-bottom");
                        this.button1b = document.getElementById("first-page-button-bottom");
                        this.backButton = document.getElementById("step-back");
                        this.step = 0;
                        let notice = document.createElement("p");
                        notice.textContent = "<? _e("Udyld datoer for at fortsætte*", "hello-elementor-child"); ?>";
                        notice.style.color = "red";
                        notice.id = "bookingDateNotice";
                        document.querySelector("#final-booking-form form").append(notice);
                        this.pages();
                        this.button1b.addEventListener("click", function(){this.nextButton.click()}.bind(this));
                        this.button2b.addEventListener("click", function(){this.nextButton.click()}.bind(this));
                        this.locked = true;

                        this.nextButton.addEventListener("click", function(){
                            $("#final-booking-form input[name=bestilling_country]").val($("#form-field-bestilling_country").val());
                            if(!this.locked){
                                this.step++;
                                if(this.step > 3){
                                    this.step = 3;
                                }
                                this.pages();
                            }
                            else{
                                document.getElementById("bookingDateNotice").style.display = "block";
                            }
                        }.bind(this));

                        this.backButton.addEventListener("click", function(){
                            this.step--;
                            if(this.step < 0){
                                this.step = 0;
                            }
                            this.pages();
                        }.bind(this));
                    }

                    skipFirstStep(){
                        this.step = 1;
                        this.pages();
                    }

                    toPage0(){
                        document.querySelectorAll("#final-booking-form .jet-form-builder-page").forEach((element) => {
                            if(element.getAttribute("data-page") == 1){
                                element.classList.remove("jet-form-builder-page--hidden");
                            }
                            else{
                                element.classList.add("jet-form-builder-page--hidden");
                            }
                        });
                        document.getElementById("step-back-container").style.display = "none";
                        this.page0.style.display = "flex";
                        this.page0top.style.display = "flex";
                        document.querySelector("#mobile-floating-next-step .elementor-button-text").textContent = "<? _e("Se oversigt", "hello-elementor-child"); ?>";
                        this.nextButton.style.display = "flex";
                        this.date.style.display = "block";
                    }

                    toPage1(){
                        document.querySelectorAll("#final-booking-form .jet-form-builder-page").forEach((element) => {
                            if(element.getAttribute("data-page") == 2){
                                element.classList.remove("jet-form-builder-page--hidden");
                            }
                            else{
                                element.classList.add("jet-form-builder-page--hidden");
                            }
                        });
                        //this.page1.style.display = "flex";
                        this.page1.style.display = "flex";
                        document.querySelector("#mobile-floating-next-step .elementor-button-text").textContent = "Se oversigt";
                        window.dispatchEvent(new Event('resize'));
                        this.nextButton.style.display = "flex";
                        this.topInfo[0].style.display = "flex";
                        this.topInfo[1].style.display = "flex";
                    }

                    toPage2(){
                        this.page2.style.display = "flex";
                        this.nextButton.style.display = "flex";
                        this.topInfo[0].style.display = "flex";
                        this.topInfo[1].style.display = "flex";
                    }

                    toPage3(){
                        this.page3.style.display = "flex";
                        $(".jet-sm-gb-1ef49fff-1263-417e-adb4-c15af40f2879").show();
                        this.topInfo[0].style.display = "flex";
                        this.topInfo[1].style.display = "flex";
                    }

                    resetPages(){
                        document.getElementById("bookingDateNotice").style.display = "none";
                        this.date.style.display = "none";
                        this.topInfo[0].style.display = "none";
                        this.topInfo[1].style.display = "none";
                        this.nextButton.style.display = "none";
                        document.getElementById("step-back-container").style.display = "flex";
                        $(".jet-sm-gb-1ef49fff-1263-417e-adb4-c15af40f2879").hide();
                        this.page0.style.removeProperty("display");
                        this.page0top.style.removeProperty("display");
                        //this.page1.style.removeProperty("display");
                        this.page1.style.removeProperty("display");
                        this.page2.style.removeProperty("display");
                        this.page3.style.removeProperty("display");
                    }

                    pages(){
                        window.scrollTo({top: 0, behavior: "smooth"});
                        this.resetPages();
                        switch(this.step){
                            case 0:
                                this.toPage0();
                                break;
                            case 1:
                                this.toPage1();
                                break;
                            case 2:
                                this.toPage2();
                                break;
                            case 3:
                                this.toPage3();
                                break;
                        }
                    }
                }

                $(document).on("mouseenter", ".added", function() {
                    $(this).find(".elementor-button-text").html("<? _e("Fjern", "hello-elementor-child"); ?>)");
                });

                $(document).on("mouseleave", ".added", function() {
                    $(this).find(".elementor-button-text").html("<? _e("Tilføjet", "hello-elementor-child"); ?>");
                });

                window.JetPlugins.hooks.addFilter( "jet-booking.input.config", "jetBooking", ( config ) => {
                    config.format = "DD.MM.YYYY";
                    return config;
                });

                let getDaysArray = function(start, end) {
                    for(var arr=[],dt=new Date(start); dt<=new Date(end); dt.setDate(dt.getDate()+1)){
                        arr.push(new Date(dt));
                    }
                    return arr;
                };

                /*Translation start*/

                function replace(search,replacement){
                    let xpathResult = document.evaluate(
                        "//*/text()",
                        document,
                        null,
                        XPathResult.ORDERED_NODE_ITERATOR_TYPE,
                        null
                    );
                    let results = [];
                    let res;
                    while (res = xpathResult.iterateNext()) {
                        results.push(res);
                    }
                    results.forEach(function(res){
                        res.textContent = res.textContent.replace(search,replacement);
                    })
                }

                if(document.documentElement.lang == "en-US"){
                    replace(/Fortsæt/g,'Continue');
                    replace(/Sæsonpriser og tilbud/g,'Seasonal prices and discounts');
                    replace(/Bolig/g,'House');
                    replace(/Dato/g,'Date');
                    replace(/Ankomst - Udtjekning/g,'Arrival - Departure');
                    replace(/I alt/g,'Total');
                    replace(/Inkl. moms/g,'Including VAT');
                    replace(/Minimum 3 dages booking. Faktura tilsendes efter bekræftet booking/g,'Minimum 3 days booking. Invoice will be sent after confirmed booking');
                    replace(/Housing/g,'House');
                    replace(/At betale nu/g,'');
                }

                /*Translation end*/

                let flow = new Flow();
                new class Calendar{
                    constructor(){
                        this.queryString = window.location.search;
                        this.urlParams = new URLSearchParams(this.queryString);
                        if(this.urlParams.has('_dates')){
                            this.urlDates = this.urlParams.get('_dates');
                            this.sepDates = this.urlDates.split(" - ");
                        }
                        jQuery(document).on("jet-booking/init-field", ($event, $field) => {
                            this.calendars = $field[0].querySelectorAll(".date-picker-wrapper");
                            $field.bind('datepicker-change', (event, obj) => {
                                this.changeTime(obj.date1, obj.date2);
                                JetBooking.getApartmentPrice( $(document.querySelectorAll("#final-booking-form #jet_abaf_field")) );
                            });
                            if(this.calendars.length > 1){
                                this.calendarInsertHtml(this.calendars);
                                const regex = /[0-9]+\.[0-9]+\.[0-9]+\s-\s[0-9]+\.[0-9]+\.[0-9]+/i;
                                if(this.urlParams.has('_dates') && regex.test(this.urlDates)){
                                    $field.data("dateRangePicker").setDateRange(this.sepDates[0], this.sepDates[1], true);

                                    let urlDateArr1 = this.sepDates[0].split(".");
                                    let urlDateArr2 = this.sepDates[1].split(".");
                                    let datesValid = true;
                                    let daylist = getDaysArray(toDateFormat(urlDateArr1[0], urlDateArr1[1], urlDateArr1[2]),toDateFormat(urlDateArr2[0], urlDateArr2[1], urlDateArr2[2]));
                                    daylist.map((v)=>v.toISOString().slice(0,10)).join("")
                                    daylist.forEach(day => {
                                        if(!window.JetBooking.validateDay(day)[0]){
                                            datesValid=false;
                                        }
                                    });
                                    if(datesValid) {
                                        this.changeTime(toDateFormat(urlDateArr1[0], urlDateArr1[1], urlDateArr1[2]), toDateFormat(urlDateArr2[0], urlDateArr2[1], urlDateArr2[2]));
                                    }
                                    else{
                                        $field.data("dateRangePicker").clear();
                                        document.getElementById("jet_abaf_field").value = "";
                                    }
                                    //flow.skipFirstStep();
                                }
                                else{
                                    $field.data("dateRangePicker").clear();
                                    document.getElementById("jet_abaf_field").value = "";
                                }
                                flow.pages();
                            }
                        });
                        jQuery( document ).on( "jet-booking/init-calendar", ( $event, $calendar ) => {
                            $calendar.bind( "datepicker-change", ( event, obj ) => {
                                this.changeTime(obj.date1, obj.date2);
                                JetBooking.getApartmentPrice( $(document.querySelectorAll("#final-booking-form #jet_abaf_field")) );
                            });
                        });
                    }


                    calendarInsertHtml(calendars){
                        calendars.forEach((element) => {
                            element.querySelector(".footer").remove();
                            element.querySelector(".drp_top-bar").remove();
                            element.insertAdjacentHTML('afterbegin', ''+
                                '<div class="calendar-top">'+
                                '<div class="left">'+
                                '<span><? _e("Ankomst - Udtjekning", "hello-elementor-child"); ?></span>'+
                                '<span class="dates"><? _e("Vælg venligst ankomst- og udtjekningsdato", "hello-elementor-child"); ?></span>'+
                                '</div>'+
                                '<div class="right">'+
                                '<span><? _e("I alt", "hello-elementor-child"); ?></span>'+
                                '<span><span class="days"></span><? _e(" dage ", "hello-elementor-child"); ?>(<span class="nights"></span><? _e(" nætter", "hello-elementor-child"); ?>)</span>'+
                                '</div>'+
                                '</div>');
                            element.querySelectorAll(".next").forEach((elm) => {
                                elm.innerHTML = "<img src='https://elmirador.dk/wp-content/uploads/2022/07/arrow-right-grey-new.svg'>";
                            });
                            element.querySelectorAll(".prev").forEach((elm) => {
                                elm.innerHTML = "<img src='https://elmirador.dk/wp-content/uploads/2022/07/arrow-left-grey-new.svg'>";
                            });
                        });
                    }

                    parseDate(input) {
                        let parts = input.match(/(\d+)/g);
                        return new Date(parts[2], parts[1]-1, parts[0]);
                    }

                    changeTime(date1, date2){
                        let date1String = date1.getDate()+"."+(parseInt(date1.getMonth())+1)+"."+date1.getFullYear();
                        let date2String = date2.getDate()+"."+(parseInt(date2.getMonth())+1)+"."+date2.getFullYear();
                        document.querySelector("#final-booking-form input.start_date").value = date1String;
                        let diffTime = Math.ceil(Math.abs(date2-date1) / 86400000);
                        document.querySelectorAll(".jet-abaf-field .date-picker-wrapper").forEach((element) => {
                            element.querySelector(".right .nights").textContent = diffTime;
                            element.querySelector(".right .days").textContent = diffTime+1;
                            element.querySelector(".left .dates").textContent = date1String+" - "+date2String;
                        });
                        document.getElementById("form-date-steps").value = date1String+" - "+date2String;
                        setTimeout(function(){
                            $("#desc-nights").html(diffTime + "<? _e(" nætter", "hello-elementor-child"); ?>");
                            $("#desc-price").html(curSym + $(".jet-sm-gb-92461b97-0f3a-4e62-a613-50b17e3b303b input").val());
                        }, 500);

                        if(document.documentElement.lang == "en-US"){
                            window.history.replaceState(null, null, "?lang=en&_dates="+date1String+" - "+date2String);
                        }
                        else{
                            window.history.replaceState(null, null, "?_dates="+date1String+" - "+date2String);
                        }


                        $("#final-booking-form .jet-sm-gb-04b9926d-4317-4d84-91bf-2c1cacbfb219 .field-type-checkbox-field label").each(function(){
                            let value = $(this).find("input").attr("data-original");
                            let id = $(this).find("input").val();
                            let isNight = $("#service-"+id+">div>div:nth-child(2)>div>div:last-child p").html();
                            if(isNight == "/ nat" || isNight == "/ night"){
                                $(this).find("input").attr("data-calculate", parseInt(value) * diffTime);
                                $(this).find(".ydelse-price").text(parseInt(value) * diffTime);
                            }
                        });
                        document.querySelector("#final-booking-form .advanced-price-table").style.display = "flex";
                        flow.locked = false;
                        document.querySelector("#final-booking-form #__hus").value = document.querySelector("#houseNumber .jet-listing-dynamic-field__content").textContent;
                        $("#final-booking-form input[name=nights]").val(diffTime);
                        document.getElementById("bookingDateNotice").style.display = "none";
                    }
                }

                function numberWithDots(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                function leadingZero(d){
                    if(parseInt(d) < 10){
                        return "0"+d;
                    }
                    else{
                        return String(d);
                    }
                }

                function toDateFormat(day, month, year){
                    day = leadingZero(day);
                    month = leadingZero(month);
                    return new Date(month+"/"+day+"/"+year);
                }


            });
		</script>
	<?php } });