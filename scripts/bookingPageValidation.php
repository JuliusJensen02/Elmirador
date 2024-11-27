<?php
function checkDiscountCode() {
	$data = sanitize_text_field($_POST['discountCode']);
	global $wpdb;
	$table_name = $wpdb->prefix . "jet_cct_booking_rabatkoder";
	$discountCode = $wpdb->get_row("SELECT * FROM $table_name WHERE (discount_code='$data' AND activated='true')", ARRAY_A);
    $discount = ["discount" => $discountCode["rabat"], "discountType" => $discountCode["type"], "description" => $discountCode["description"]];
    wp_send_json_success($discount);
    wp_die();
}

function enqueue_jquery() {
	wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery');

add_action('wp_ajax_check_discount_code', 'checkDiscountCode');
add_action('wp_ajax_nopriv_check_discount_code', 'checkDiscountCode');


function localize_ajax_url() {
	wp_localize_script('jquery', 'ajaxurl', admin_url('admin-ajax.php'));
}
add_action('wp_enqueue_scripts', 'localize_ajax_url');


add_action( 'wp_footer', function () {
	$post = get_post();
	if($post->post_type == "boliger" && is_single()){?>
        <style>
            .discountNotice{
                position: absolute;
                right: 25px;
                top: 13px;
            }
        </style>
		<script>
            jQuery(document).ready(function($){
                new class FormValidation{
                    constructor(){
                        this.fields = {
                            firstname: {
                                input: document.querySelector("#form-field-bestilling_firstname"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_firstname]")
                            },
                            lastname: {
                                input: document.querySelector("#form-field-bestilling_lastname"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_lastname]")
                            },
                            address: {
                                input: document.querySelector("#form-field-bestilling_address"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_address]")
                            },
                            postal: {
                                input: document.querySelector("#form-field-bestilling_postal"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_postal]")
                            },
                            city: {
                                input: document.querySelector("#form-field-bestilling_city"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_city]")
                            },
                            country: {
                                input: document.querySelector("#form-field-bestilling_country"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_country]")
                            },
                            phone: {
                                input: document.querySelector("#form-field-bestilling_phone"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_phone]")
                            },
                            landcode: {
                                input: document.querySelector("#form-field-bestilling_landcode"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_landcode]")
                            },
                            comments: {
                                input: document.querySelector("#form-field-bestilling_comments"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_comments]")
                            },
                            rabatkode: {
                                input: document.querySelector("#form-field-bestilling_rabatkode"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_rabatkode]")
                            },
                            email: {
                                input: document.querySelector("#form-field-bestilling_email"),
                                inputRepeat: document.querySelector("#form-field-bestilling_confirm_email"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_email]")
                            },
                            conditions: {
                                input: document.querySelector("#form-field-field_d90cefd-0"),
                                form: document.querySelector("#final-booking-form .jet-sm-gb-9558bab3-6260-4659-a75a-bdcc6e111277 input")
                            },
                            adults: {
                                input: document.querySelector("#form-field-bestilling_guest_adults"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_adults]")
                            },
                            children: {
                                input: document.querySelector("#form-field-bestilling_guests_children"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_children]")
                            },
                            guests: {
                                form: document.querySelector("#final-booking-form input[name=bestilling_guests]")
                            },
                            discount: {
                                input: document.getElementById("form-field-bestilling_rabatkode"),
                                form: document.querySelector("#final-booking-form input[name=bestilling_rabatkode]")
                            },
                            ads: {
                                input: document.getElementById("form-field-adsConfirm-0"),
                                form: document.querySelector("#final-booking-form input[name=receiveEmails]")
                            },
                            expectedArrival: {
                                input: document.querySelector("#form-field-field_572ed73"),
                                form: document.querySelector("#final-booking-form input[name=expected_arrival]")
                            },
                        };

                        this.fields.expectedArrival.form.value = this.fields.expectedArrival.input.value;

                        document.addEventListener("datesChangedAfterSeasonalPriceChange", ()=>{
                            document.querySelector("#final-booking-form input[name='adv-price-table']").value = JSON.stringify(window.selectedDatesPrice);
                        })

                        this.fields.ads.input.addEventListener("click", function(){
                            this.fields.ads.form.click();
                        }.bind(this));

                        if(this.fields.discount.input){
                            this.fields.discount.input.addEventListener("input", () => {
                                let value = this.fields.discount.input.value;
                                setTimeout(() => {
                                    if(value == this.fields.discount.input.value){
                                        $.ajax({
                                            type: 'POST',
                                            url: ajaxurl,
                                            data: {
                                                action: 'check_discount_code',
                                                discountCode: value
                                            },
                                            success: function(response) {
                                                if(this.fields.discount.input.parentElement.querySelector(".discountNotice")){
                                                    this.fields.discount.input.parentElement.querySelector(".discountNotice").remove();
                                                }
                                                if(this.fields.discount.input.parentElement.querySelector(".discountMsg")){
                                                    this.fields.discount.input.parentElement.querySelector(".discountMsg").remove();
                                                }
                                                if(document.querySelector(".advanced-price-table .discount-row")){
                                                    document.querySelectorAll(".advanced-price-table .discount-row").forEach((element) => element.remove());
                                                }
                                                if(response.data.discount && response.data.discountType){
                                                    $(this.fields.discount.input.parentElement).append('<svg class="discountNotice" xmlns="http://www.w3.org/2000/svg" width="19.134" height="13.077" viewBox="0 0 19.134 13.077"><path data-name="Flueben orange" d="M17.8,4.371,6.782,15.387a.8.8,0,0,1-1.13,0l-4.26-4.264a.8.8,0,0,0-1.13,0h0a.8.8,0,0,0,0,1.13l4.262,4.261a2.4,2.4,0,0,0,3.388,0L18.927,5.5a.8.8,0,0,0,0-1.129h0a.8.8,0,0,0-1.13,0Z" transform="translate(-0.028 -4.137)" fill="#008000"/></svg><span class="discountMsg"><? _e( "Rabatkoden er nu anvendt", "hello-elementor-child" ); ?></span>');
                                                }
                                                else{
                                                    $(this.fields.discount.input.parentElement).append('<svg class="discountNotice" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20.696 20.696"><path data-name="close (3)" d="M12.244,10.484,20.3,2.425a1.341,1.341,0,0,0-1.9-1.9L10.348,8.588,2.289.529a1.341,1.341,0,0,0-1.9,1.9l8.059,8.059L.393,18.543a1.341,1.341,0,1,0,1.9,1.9l8.059-8.059,8.059,8.059a1.341,1.341,0,0,0,1.9-1.9Zm0,0" transform="translate(0 -0.136)" fill="#ff0000"/></svg><span class="discountMsg"><? _e( "Rabatkoden blev ikke godkendt", "hello-elementor-child" ); ?></span>');
                                                }
                                                window.JetPlugins.hooks.addFilter("jet-booking.apartment-price", "jetBooking", customDiscountCode, 10 );
                                                $(window.jetBookingState.bookingCalendars[1][0]).data('dateRangePicker').open();
                                                $(window.jetBookingState.bookingCalendars[1][0]).data('dateRangePicker').close();
                                                window.JetPlugins.hooks.filters["jet-booking.apartment-price"].handlers.pop();
                                                function customDiscountCode(price, field, discount=response){
                                                    let newPrice = price;
                                                    if(discount.data.discount){
                                                        if(discount.data["discountType"] === "Procent"){
                                                            newPrice = price - (price * (discount.data["discount"]/100));
                                                        }
                                                        else{
                                                            newPrice = price - discount.data["discount"];
                                                        }
                                                        let discountField = document.getElementById("form-field-bestilling_rabatkode");
                                                        let discountDifference = Math.round(price - newPrice);
                                                        let discountRow = document.createElement("span");
                                                        discountRow.innerHTML = "<div><? _e( "Kode: ", "hello-elementor-child" ); ?>"+discountField.value+"</div><div>- €" + discountDifference.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + "</div>";
                                                        discountRow.classList.add("discount-row");
                                                        if(discount.data["description"] != null){
                                                            let discountRowDescription = document.createElement("span");
                                                            discountRowDescription.innerHTML = "<div>"+response.data["description"]+"</div>";
                                                            discountRowDescription.classList.add("discount-row");
                                                            document.querySelector("#final-booking-form .advanced-price-table").append(discountRow, discountRowDescription);
                                                        }
                                                        else{
                                                            document.querySelector("#final-booking-form .advanced-price-table").append(discountRow);
                                                        }
                                                    }
                                                    return newPrice;
                                                }
                                                this.fields.discount.form.value = value;
                                            }.bind(this)
                                        });
                                    }
                                }, 1500);
                            });
                        }

                        Object.entries(this.fields).forEach(([key, object]) => {
                            if(key !== "confirmEmail" && key !== "adults" && key !== "children" && key !== "guests"){
                                object.input.addEventListener("input", (event) => {
                                    let field = event.target;
                                    this.checkEmpty(field);
                                    object.form.value = field.value;
                                });
                                if(key == "country" || key == "landcode"){
                                    object.input.dispatchEvent(new Event("input"));
                                }
                            }
                        });
                        this.fields.email.input.addEventListener("input", this.validateEmail.bind(this));
                        this.fields.email.inputRepeat.addEventListener("input", this.validateEmail.bind(this));
                        this.fields.adults.input.addEventListener("input", this.validateAdults.bind(this));
                        this.fields.children.input.addEventListener("input", this.validateChildren.bind(this));
                        this.fields.conditions.input.addEventListener("change", function(event){
                            this.fields.conditions.form.checked = !!event.target.checked;
                        }.bind(this));

                        $("#step-3-page form").each((index, form) => {
                            form.querySelectorAll("input[type=hidden]").forEach(input => input.remove());
                            let cnt = $(form).contents();
                            $(form).replaceWith(cnt);
                        });

                        this.fields.adults.input.value = 1;
                        this.fields.children.input.value = 0;

                        //Adults
                        this.fields.adults.input.addEventListener("input", function(){
                            this.transferPeople();
                        }.bind(this));

                        //Children
                        this.fields.children.input.addEventListener("input", function(){
                            this.transferPeople();
                        }.bind(this));

                        this.fields.adults.input.dispatchEvent(new Event("input"));
                        this.fields.children.input.dispatchEvent(new Event("input"));

                        //Submit form / check
                        document.getElementById("confirm-form").addEventListener("click", () => {
                            document.querySelector("#final-booking-form .jet-form-builder__submit").click();
                        });

                        document.querySelector("#final-booking-form .jet-form-builder__submit").addEventListener("click", function(event){
                            if(!this.isRequiredInputsFilled() || !this.areEmailsSame() || !this.isEmailCorrectFormat() || !this.isConditionsAccepted() || this.fields.adults.form.value < 1 || this.fields.children.form.value < 0 || !this.validateAdults() || !this.validateChildren() || !this.validateGuests()){
                                event.preventDefault();
                                window.scrollTo(0, 0);
                                $("#booking-notice").show();
                            }
                            let bookingNoticeMsg = document.querySelector("#booking-notice p");
                            if(!this.isRequiredInputsFilled()){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Du mangler at udfylde et eller flere felt(er)';
                            }
                            else if(!this.areEmailsSame()){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Begge e-mails er ikke ens';
                            }
                            else if(!this.isEmailCorrectFormat()){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> E-mails er forkert format';
                            }
                            else if(parseInt(this.fields.adults.form.value) < 1){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Mindst én voksen skal deltage';
                            }
                            else if(parseInt(this.fields.adults.form.value) < 0 || !this.validateAdults()/* || !this.validateChildren()*/){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Indtast venligst et gyldigt antal gæster';
                            }
                            else if(!this.validateGuests()){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Du har indtastet for mange gæster til denne bolig';
                            }
                            else if(!this.isConditionsAccepted()){
                                bookingNoticeMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M11.5,14.5V6.5c0-.28,.22-.5,.5-.5s.5,.22,.5,.5V14.5c0,.28-.22,.5-.5,.5s-.5-.22-.5-.5Zm.5,2.5c-.55,0-1,.45-1,1s.45,1,1,1,1-.45,1-1-.45-1-1-1Zm11.61,3.07c-.64,1.23-1.99,1.93-3.71,1.93H4.1c-1.71,0-3.07-.7-3.71-1.93-.65-1.24-.47-2.87,.48-4.24L9.3,2.43c.62-.9,1.63-1.43,2.7-1.43s2.08,.53,2.69,1.41l8.44,13.43c.95,1.37,1.13,2.99,.48,4.23Zm-1.31-3.67s0-.01-.01-.02L13.86,2.96c-.42-.61-1.1-.96-1.86-.96s-1.44,.36-1.87,.98L1.71,16.38c-.75,1.08-.91,2.31-.43,3.23,.47,.9,1.47,1.39,2.82,1.39h15.81c1.35,0,2.35-.49,2.82-1.39,.48-.91,.32-2.14-.42-3.21Z"></path></svg> Du mangler at acceptere handelsbetingelserne';
                            }
                        }.bind(this));
                    }

                    checkEmpty(field){
                        if(field.value !== ""){
                            field.style.borderColor = "green";
                        }
                        else{
                            field.style.borderColor = "red";
                        }
                    }

                    isConditionsAccepted(){
                        return !!this.fields.conditions.form.checked;
                    }

                    isRequiredInputsFilled(){
                        let valid = true;
                        document.querySelectorAll("#step-3-page input[required]").forEach((field) => {
                            if(field.value == null || field.value == ""){
                                valid = false;
                            }
                        });
                        return valid;
                    }

                    areEmailsSame(){
                        if(this.fields.email.input.value == this.fields.email.inputRepeat.value){
                            return true;
                        }
                        else{
                            return false;
                        }
                    }

                    isEmailCorrectFormat(){
                        return String(this.fields.email.input.value).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
                    }

                    validateEmail(){
                        if(this.areEmailsSame() && this.isEmailCorrectFormat()){
                            this.fields.email.form.value = this.fields.email.input.value;
                            this.fields.email.input.style.borderColor = "green";
                            this.fields.email.inputRepeat.style.borderColor = "green";
                        }
                        else{
                            this.fields.email.form.value = "";
                            this.fields.email.input.style.borderColor = "red";
                            this.fields.email.inputRepeat.style.borderColor = "red";
                        }
                    }

                    validateAdults(){
                        if(!isNaN(parseInt(this.fields.adults.input.value))){
                            this.fields.adults.input.style.borderColor = "green";
                            return true;
                        }
                        else{
                            this.fields.adults.input.style.borderColor = "red";
                            return false;
                        }
                    }

                    validateChildren(){
                        if(!isNaN(parseInt(this.fields.children.input.value))){
                            this.fields.children.input.style.borderColor = "green";
                            return true;
                        }
                        else{
                            this.fields.children.input.style.borderColor = "red";
                            return false;
                        }
                    }

                    validateGuests(){
                        let total = parseInt(this.fields.adults.form.value) + parseInt(this.fields.children.form.value);
                        return total <= <? echo get_post_field("max_guests"); ?>
                    }

                    transferPeople(){
                        if(this.fields.adults.input.value == ""){
                            this.fields.adults.form.value = 0;
                        }
                        else{
                            this.fields.adults.form.value = this.fields.adults.input.value;
                        }

                        if(this.fields.children.input.value == ""){
                            this.fields.children.form.value = 0;
                        }
                        else{
                            this.fields.children.form.value = this.fields.children.input.value;
                        }
                        this.fields.guests.form.value = parseInt(this.fields.children.form.value) + parseInt(this.fields.adults.form.value);
                    }
                }
            });
		</script>
	<?php } });