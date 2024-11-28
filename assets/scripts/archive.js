jQuery(document).ready(function ($) {
    /*Accordion*/
    let accordion = document.querySelector("#archive .infoBar .included");
    let accordionBar = document.querySelector("#archive .infoBar .included .bar");
    accordionBar.addEventListener('click', function(){
        accordion.classList.toggle("active");
    });

    let accordion2 = document.querySelector("#archive .infoBar .filters");
    let accordionBar2 = document.querySelector("#archive .infoBar .filters .bar");
    accordionBar2.addEventListener('click', function(){
        accordion2.classList.toggle("active");
    });


    /*Filters*/
    let calendarInput1 = document.getElementById('calendarInput1');
    let calendarInput2 = document.getElementById('calendarInput2');
    let houseFilter = document.getElementById('house');
    let guestsFilter = document.getElementById('guests');
    let bedroomsFilter = document.getElementById('bedrooms');
    let jacuzziFilter = document.getElementById('jacuzzi');
    let poolFilter = document.getElementById('pool');
    let elevatorFilter = document.getElementById('elevator');
    let electricCarFilter = document.getElementById('electricCar');
    let request = null;
    let clearFiltersBtn = document.getElementById('clearFilters');

    clearFiltersBtn.addEventListener('click', function(){
        document.getElementById('clearDates').click();
        houseFilter.value = "";
        guestsFilter.value = 1;
        bedroomsFilter.value = 3;
        jacuzziFilter.checked = false;
        poolFilter.checked = false;
        elevatorFilter.checked = false;
        electricCarFilter.checked = false;
        document.getElementById('guestsNumber').textContent = guestsFilter.value;
        document.getElementById('bedroomsNumber').textContent = "Min. "+bedroomsFilter.value;
        applyFilters();
    });


    calendarInput1.addEventListener('input', function () {
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            applyFilters();
        }
    });

    calendarInput2.addEventListener('input', function () {
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            applyFilters();
        }
    });

    houseFilter.addEventListener('change', function () {
        applyFilters();
    });

    document.getElementById('guestsNumber').textContent = guestsFilter.value;
    guestsFilter.addEventListener('input', function () {
        document.getElementById('guestsNumber').textContent = guestsFilter.value;
    });

    document.getElementById('bedroomsNumber').textContent = "Min. "+bedroomsFilter.value;
    bedroomsFilter.addEventListener('input', function () {
        document.getElementById('bedroomsNumber').textContent = "Min. "+bedroomsFilter.value;
    });

    guestsFilter.addEventListener('change', function () {
        applyFilters();
        document.getElementById('guestsNumber').textContent = guestsFilter.value;
    });

    bedroomsFilter.addEventListener('change', function () {
        applyFilters();
        document.getElementById('bedroomsNumber').textContent = "Min. "+bedroomsFilter.value;
    });

    jacuzziFilter.addEventListener('change', function () {
        applyFilters();
    });

    poolFilter.addEventListener('change', function () {
        applyFilters();
    });

    elevatorFilter.addEventListener('change', function () {
        applyFilters();
    });

    electricCarFilter.addEventListener('change', function () {
        applyFilters();
    });

    let calendar = new Calendar();

    function confirmGuests(guests){
        if(guests === undefined) {
            guests = parseInt(guestsFilter.value);
        }
        else{
            guests = parseInt(guests);
        }

        if(guests < 1){
            guests = 1;
        }
        if(guests > 12){
            guests = 12;
        }
        return guests;
    }

    function confirmDates(date1, date2){
        date1 = new Date(date1);
        date2 = new Date(date2);
        let dateTomorrow = new Date();
        dateTomorrow.setDate(dateTomorrow.getDate() + 1);
        dateTomorrow.setHours(0,0,0,0);
        if(date1 instanceof Date && !isNaN(date1) && date2 instanceof Date && !isNaN(date2)){
            return (date1 >= dateTomorrow && date2 >= dateTomorrow && date1 <= date2 && isDatesAtLeast3DaysApart(date1, date2));
        }
        return false;
    }

    function isDatesAtLeast3DaysApart(date1, date2){
        let date3 = new Date(date1);
        date3.setDate(date3.getDate() + 2);
        return date3 < date2
    }

    window.addEventListener('applyFilters', applyFilters);

    function applyFilters() {
        if(request !== null){
            request.abort();
            request = null;
        }
        let data = {
            action: 'get_apartments_custom',
            startDate: calendarInput1.value,
            endDate: calendarInput2.value,
            lang: ajax_object.lang,
            house: houseFilter.value,
            guests: guestsFilter.value,
            bedrooms: bedroomsFilter.value,
            jacuzzi: jacuzziFilter.checked,
            pool: poolFilter.checked,
            elevator: elevatorFilter.checked,
            electricCar: electricCarFilter.checked
        };
        request = getApartments(data);

        //Active filters
        let activeFiltersContainer = document.querySelector("#archive .activeFilters");
        activeFiltersContainer.innerHTML = "";
        if(calendarInput1.value && calendarInput2.value || guestsFilter.value){
            let text = document.createElement("p");
            text.textContent = "Viser tilgængelige boliger for:";
            activeFiltersContainer.appendChild(text);
        }
        if(calendarInput1.value && calendarInput2.value){
            let date1 = calendarInput1.value.replaceAll("-", ".");
            let date2 = calendarInput2.value.replaceAll("-", ".");
            let activeFilter = document.createElement("div");
            let icon = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14"><path id="calendar-day" d="M4.667,7H3.5A1.168,1.168,0,0,0,2.333,8.167V9.333A1.168,1.168,0,0,0,3.5,10.5H4.667A1.168,1.168,0,0,0,5.833,9.333V8.167A1.168,1.168,0,0,0,4.667,7ZM3.5,9.333V8.167H4.667V9.333Zm7.583-8.167H10.5V.583a.583.583,0,0,0-1.167,0v.583H4.667V.583A.583.583,0,1,0,3.5.583v.583H2.917A2.92,2.92,0,0,0,0,4.083v7A2.92,2.92,0,0,0,2.917,14h8.167A2.92,2.92,0,0,0,14,11.083v-7A2.92,2.92,0,0,0,11.083,1.167ZM2.917,2.333h8.167a1.752,1.752,0,0,1,1.75,1.75v.583H1.167V4.083A1.752,1.752,0,0,1,2.917,2.333Zm8.167,10.5H2.917a1.752,1.752,0,0,1-1.75-1.75V5.833H12.833v5.25A1.752,1.752,0,0,1,11.083,12.833Z" fill="#d59f54"/></svg>';

            let text = document.createElement("p");
            text.textContent = date1 + " - " + date2;

            activeFilter.innerHTML = icon;
            activeFilter.appendChild(text);
            activeFilter.classList.add("activeFilter");
            activeFiltersContainer.appendChild(activeFilter);
        }
        if(guestsFilter.value){
            let activeFilter = document.createElement("div");
            let icon = '<svg xmlns="http://www.w3.org/2000/svg" width="10.524" height="14.033" viewBox="0 0 10.524 14.033"> <g id="user" transform="translate(-3)"> <path id="Path_93" data-name="Path 93" d="M9.508,7.016A3.508,3.508,0,1,0,6,3.508,3.508,3.508,0,0,0,9.508,7.016Zm0-5.847A2.339,2.339,0,1,1,7.169,3.508,2.339,2.339,0,0,1,9.508,1.169Z" transform="translate(-1.246 0)" fill="#d59f54"/> <path id="Path_94" data-name="Path 94" d="M8.262,14A5.268,5.268,0,0,0,3,19.262a.585.585,0,1,0,1.169,0,4.093,4.093,0,1,1,8.186,0,.585.585,0,1,0,1.169,0A5.268,5.268,0,0,0,8.262,14Z" transform="translate(0 -5.814)" fill="#d59f54"/> </g> </svg>';

            let text = document.createElement("p");
            if(parseInt(guestsFilter.value) > 1){
                text.textContent = guestsFilter.value + " gæster";
            }
            else{
                text.textContent = guestsFilter.value + " gæst";
            }

            activeFilter.innerHTML = icon;
            activeFilter.appendChild(text);
            activeFilter.classList.add("activeFilter");
            activeFiltersContainer.appendChild(activeFilter);
        }
    }


    let apartmentsContainer = document.getElementById('apartsments');
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if(urlParams.has('startDate') && urlParams.has('endDate') && urlParams.has("guests") && confirmDates(urlParams.get('startDate'), urlParams.get('endDate'))){
        let date1 = new Date(urlParams.get('startDate'));
        let date2 = new Date(urlParams.get('endDate'));
        calendar.chosenStartDate = date1;
        calendar.chosenEndDate = date2;
        calendarInput1.value = date1.getDate() + "-" + (date1.getMonth()+1).toLocaleString('en-US', { minimumIntegerDigits: 2, useGrouping: false }) + "-" + date1.getFullYear();
        calendarInput2.value = date2.getDate() + "-" + (date2.getMonth()+1).toLocaleString('en-US', { minimumIntegerDigits: 2, useGrouping: false }) + "-" + date2.getFullYear();

        if(calendar.chosenStartDate !== null || calendar.chosenEndDate !== null){
            calendar.clearDatesBtn.classList.add("active");
        }

        let guests = confirmGuests(urlParams.get('guests'));
        guestsFilter.value = guests;
        document.getElementById('guestsNumber').textContent = guests;
        applyFilters();
    }
    else{
        let data = {
            action: 'get_apartments_custom',
            lang: ajax_object.lang
        };
        getApartments(data);
    }




    function getApartments(data) {
        apartmentsContainer.classList.add('loading');
        document.querySelector("#archive .filters").classList.add('loading');
        return $.post(ajax_object.ajaxurl, data, function (response) {
            console.log(response);
            apartmentsContainer.innerHTML = "";
            let apartments = response.data;
            if(!Array.isArray(apartments) || apartments.length === 0){
                if(ajax_object.lang === 'da') {
                    apartmentsContainer.textContent = "Ingen boliger matcher dine kriterier";
                }
                else{
                    apartmentsContainer.textContent = "No apartments match your criteria";
                }
                apartmentsContainer.style.display = "flex";
                apartmentsContainer.style.justifyContent = "center";
                apartmentsContainer.style.fontSize = "24px";
                apartmentsContainer.style.textAlign = "center";

            }
            else {
                apartments.forEach(function (apartment) {
                    apartmentsContainer.appendChild(createApartmentCard(apartment));

                });
                apartmentsContainer.style.display = "grid";
                apartmentsContainer.style.textAlign = "left";
            }
            apartmentsContainer.classList.remove('loading');
            document.querySelector("#archive .filters").classList.remove('loading');
        });
    }

    function createApartmentCard(apartment){
        let apartmentCtn = document.createElement("div");

        apartmentCtn.appendChild(createGallery(apartment));

        let title = document.createElement("h2");
        let titleLink = document.createElement("a");
        titleLink.href = apartment.url;
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            let date1 = new Date(calendar.chosenStartDate);
            let date2 = new Date(calendar.chosenEndDate);
            titleLink.href += "?_dates="+date1.getDate()+"."+(date1.getMonth()+1)+"."+date1.getFullYear()+" - "+date2.getDate()+"."+(date2.getMonth()+1)+"."+date2.getFullYear();
        }
        titleLink.textContent = apartment.title;
        title.appendChild(titleLink);
        apartmentCtn.appendChild(title);


        let details = document.createElement("p");
        if(apartment.extras !== ""){
            apartment.extras = " ∙ " + apartment.extras
        }
        details.innerHTML = "<span>"+apartment.guests+"</span>" + " ∙ " + "<span>"+apartment.bedrooms+"</span>" + " ∙ " + "<span>"+apartment.beds+"</span>" + " ∙ " + "<span>"+apartment.bathrooms+"</span>" + "<span>"+apartment.extras+"</span>";
        apartmentCtn.appendChild(details);

        /*Popup*/
        apartmentCtn.appendChild(createPopup(apartment, apartmentCtn));

        /*Price*/
        let price = document.createElement("div");
        price.classList.add('price');
        price.innerHTML = apartment.lowestPrice;
        apartmentCtn.appendChild(price);

        return apartmentCtn;
    }
    function createGallery(apartment){
        let gallery = document.createElement("div");
        gallery.classList.add('gallery');

        gallery.addEventListener("touchstart", function(ev){
            let startPosX = ev.touches[0].clientX;
            gallery.addEventListener("touchmove", function(e){
                let endPosX = e.touches[0].clientX;
                let galleryNav = gallery.querySelector(".gallery-nav");
                if(startPosX !== null) {
                    let diff = startPosX - endPosX;
                    if (diff > 50) {
                        galleryNav.querySelector(".next").click();
                        startPosX = null;
                        endPosX = null;
                    }
                    else if(diff < -50){
                        galleryNav.querySelector(".prev").click();
                        startPosX = null;
                        endPosX = null;
                    }
                }
            });
        });



        let galleryNav = document.createElement("div");
        galleryNav.classList.add('gallery-nav');
        gallery.appendChild(galleryNav);

        let galleryNavPrev = document.createElement("button");
        galleryNavPrev.classList.add('prev');
        galleryNavPrev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" xml:space="preserve" class=""><g><path d="M18 15.5a1 1 0 0 1-.71-.29l-4.58-4.59a1 1 0 0 0-1.42 0l-4.58 4.59a1 1 0 0 1-1.42-1.42l4.59-4.58a3.06 3.06 0 0 1 4.24 0l4.59 4.58a1 1 0 0 1 0 1.42 1 1 0 0 1-.71.29Z" fill="#d59f54" opacity="1" data-original="#000000"/></g></svg>';
        let galleryNavNext = document.createElement("button");
        galleryNavNext.classList.add('next');
        galleryNavNext.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" xml:space="preserve" class=""><g><path d="M18 15.5a1 1 0 0 1-.71-.29l-4.58-4.59a1 1 0 0 0-1.42 0l-4.58 4.59a1 1 0 0 1-1.42-1.42l4.59-4.58a3.06 3.06 0 0 1 4.24 0l4.59 4.58a1 1 0 0 1 0 1.42 1 1 0 0 1-.71.29Z" fill="#d59f54" opacity="1" data-original="#000000"/></g></svg>';
        galleryNav.append(galleryNavPrev, galleryNavNext);

        let galleryPosition = 0;

        let gallerySlider = document.createElement("a");
        gallerySlider.href = apartment.url;
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            let date1 = new Date(calendar.chosenStartDate);
            let date2 = new Date(calendar.chosenEndDate);
            gallerySlider.href += "?_dates="+date1.getDate()+"."+(date1.getMonth()+1)+"."+date1.getFullYear()+" - "+date2.getDate()+"."+(date2.getMonth()+1)+"."+date2.getFullYear();
        }
        gallerySlider.classList.add('gallery-slider');
        gallerySlider.style.width = apartment.images.length * 100 + "%";
        gallery.appendChild(gallerySlider);

        galleryNavPrev.addEventListener('click', function(){
            galleryPosition--;
            if(galleryPosition < 0){
                galleryPosition = apartment.images.length-1;
            }
            gallerySlider.style.transform = "translateX(-"+galleryPosition * 100/apartment.images.length+"%)";
        });

        galleryNavNext.addEventListener('click', function(){
            galleryPosition++;
            if(galleryPosition === apartment.images.length){
                galleryPosition = 0;
            }
            gallerySlider.style.transform = "translateX(-"+galleryPosition * 100/apartment.images.length+"%)";
        });

        apartment.images.forEach(function (image) {
            let galleryImg = document.createElement("div");
            galleryImg.innerHTML = image;
            gallerySlider.appendChild(galleryImg);
        });
        return gallery;
    }

    function createPopup(apartment, apartmentCtn){
        let popup = document.createElement("div");
        popup.classList.add('popup');

        let popupContent = document.createElement("div");
        popup.appendChild(popupContent);

        let popupTop = document.createElement("div");
        popupTop.classList.add('popup-top');
        popupContent.appendChild(popupTop);

        let popupTitle = document.createElement("h2");
        popupTitle.textContent = apartment.seasonPricesBtn;
        popupTop.appendChild(popupTitle);

        popupContent.classList.add('popup-content');
        apartment.seasonPrices = Object.keys(apartment.seasonPrices).map((key) => apartment.seasonPrices[key]);
        apartment.seasonPrices.forEach(function (season) {
            let seasonCtn = document.createElement("div");
            seasonCtn.classList.add('season');

            let seasonTitle = document.createElement("p");
            seasonTitle.innerHTML = season.period;
            seasonCtn.appendChild(seasonTitle);

            let seasonPrice = document.createElement("p");
            seasonPrice.innerHTML = season.price;
            seasonCtn.appendChild(seasonPrice);

            popupContent.appendChild(seasonCtn);
        });

        apartment.discounts.forEach(function (discount) {
            let discountCtn = document.createElement("div");
            discountCtn.classList.add('discount');

            let discountTitle = document.createElement("p");
            discountTitle.innerHTML = discount.title;
            discountCtn.appendChild(discountTitle);

            let discountPrice = document.createElement("p");
            discountPrice.innerHTML = discount.value;
            discountCtn.appendChild(discountPrice);

            popupContent.appendChild(discountCtn);
        });

        let popupBtn = document.createElement("button");
        popupBtn.textContent = apartment.seasonPricesBtn;
        popupBtn.addEventListener('click', function () {
            popup.classList.add("active");
        });
        apartmentCtn.appendChild(popupBtn);

        let closeBtn = document.createElement("button");
        closeBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20.696" height="20.696" viewBox="0 0 20.696 20.696"><path id="close_3_" data-name="close (3)" d="M12.244,10.484,20.3,2.425a1.341,1.341,0,0,0-1.9-1.9L10.348,8.588,2.289.529a1.341,1.341,0,0,0-1.9,1.9l8.059,8.059L.393,18.543a1.341,1.341,0,1,0,1.9,1.9l8.059-8.059,8.059,8.059a1.341,1.341,0,0,0,1.9-1.9Zm0,0" transform="translate(0 -0.136)" fill="#1A242F"></path></svg>';
        closeBtn.addEventListener('click', function () {
            popup.classList.remove("active");
        });
        popupTop.appendChild(closeBtn);

        popup.addEventListener('click', function (e) {
            if(e.target === popup){
                popup.classList.remove("active");
            }
        });

        return popup;
    }
});

class Calendar {
    constructor() {
        this.calendar = document.getElementById('calendar');
        this.calendarDays = document.getElementById('calendarDays');
        this.calendarInput1 = document.getElementById('calendarInput1');
        this.calendarInput2 = document.getElementById('calendarInput2');
        this.currentMonthDisplay = document.getElementById('currentMonth');
        this.clearDatesBtn = document.getElementById('clearDates');
        this.today = new Date();
        this.selectedMonth = new Date(this.today);
        this.activeInput = null;
        this.chosenStartDate = null;
        this.chosenEndDate = null;

        this.calendarInput1.addEventListener("focusin", this.show.bind(this));
        this.calendarInput2.addEventListener("focusin", this.show.bind(this));

        document.body.addEventListener("click", function(e){
            if(e.target.closest("#calendarFilter") === null){
                this.hide();
            }
        }.bind(this));

        this.changeMonth(0);
        this.insertMonthDays();

        document.getElementById('nextMonth').addEventListener('click', function(){
            this.changeMonth(1);
        }.bind(this));

        document.getElementById('prevMonth').addEventListener('click', function(){
            this.changeMonth(-1);
        }.bind(this));

        this.clearDatesBtn.addEventListener('click', function(){
            this.chosenStartDate = null;
            this.chosenEndDate = null;
            this.calendarInput1.value = "";
            this.calendarInput2.value = "";
            this.insertMonthDays();
            window.dispatchEvent(new Event('applyFilters'));
        }.bind(this));
    }

    hide(){
        this.activeInput = null;
        this.calendar.classList.remove("active");
        this.insertMonthDays();
    }

    show(e){
        this.activeInput = e.target;
        this.calendar.classList.add("active");
        this.insertMonthDays();
    }

    changeMonth(direction){
        this.selectedMonth = new Date(this.selectedMonth.setMonth(this.selectedMonth.getMonth() + direction));

        if(ajax_object.lang === 'da') {
            this.currentMonthDisplay.textContent = this.selectedMonth.toLocaleString('da-DK', {month: 'long'})+" "+this.selectedMonth.getFullYear();
        }
        else{
            this.currentMonthDisplay.textContent = this.selectedMonth.toLocaleString('en-US', {month: 'long'})+" "+this.selectedMonth.getFullYear();
        }
        this.insertMonthDays();
    }

    insertMonthDays(){
        let dummyDate = new Date(this.selectedMonth);
        dummyDate.setDate(1);
        let firstDay = dummyDate.getDay();
        if(firstDay === 0){
            firstDay = 7;
        }

        dummyDate.setMonth(dummyDate.getMonth() + 1);
        dummyDate.setDate(0);
        let monthDays = dummyDate.getDate();
        let lastDay = dummyDate.getDay();
        let weekDays = ["MA", "TI", "ON", "TO", "FR", "LØ", "SØ"];

        this.clearCalendar();
        for (const weekDay of weekDays) {
            let day = document.createElement("div");
            day.textContent = weekDay;
            day.classList.add("weekDay");
            this.calendarDays.appendChild(day);
        }
        for(let i=1; i<firstDay; i++){
            let day = document.createElement("div");
            day.classList.add("disabled", "initial");
            this.calendarDays.appendChild(day);
        }
        for(let i=1; i<=monthDays; i++){
            this.insertMonthDay(i);
        }
        for(let i=lastDay; i<6; i++){
            let day = document.createElement("div");
            day.classList.add("disabled", "initial");
            this.calendarDays.appendChild(day);
        }
        if(this.chosenStartDate !== null || this.chosenEndDate !== null){
            this.clearDatesBtn.classList.add("active");
        }
        else{
            this.clearDatesBtn.classList.remove("active");
        }
    }

    insertMonthDay(i){
        let day = document.createElement("div");
        day.textContent = i.toString();
        this.calendarDays.appendChild(day);
        if(this.isStartDate(i)){
            day.classList.add("startdate");
        }
        if(this.isEndDate(i)){
            day.classList.add("enddate");
        }
        if(this.isBetweenDates(i)){
            day.classList.add("betweenDates");
        }
        if(this.isDayBeforeToday(i) || (this.activeInput === this.calendarInput2 && (this.isDayBeforeStartDate(i) || this.is3DaysFromStartDate(i)))){
            day.classList.add("disabled");
        }
        else{
            day.addEventListener('click', function(){
                this.chooseDay(day, i);
            }.bind(this));
        }
    }

    isBetweenDates(i){
        if(this.chosenStartDate === null || this.chosenEndDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate > this.chosenStartDate && dayDate < this.chosenEndDate;
    }

    isDayBeforeToday(i){
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate < this.today;
    }

    isDayBeforeStartDate(i){
        if(this.chosenStartDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate < this.chosenStartDate;
    }

    isDayAfterEndDate(i){
        if(this.chosenEndDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate > this.chosenEndDate;
    }

    isEndDate(i){
        if(this.chosenEndDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate.getDate() === this.chosenEndDate.getDate() && dayDate.getMonth() === this.chosenEndDate.getMonth() && dayDate.getFullYear() === this.chosenEndDate.getFullYear();
    }

    isStartDate(i){
        if(this.chosenStartDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        return dayDate.getDate() === this.chosenStartDate.getDate() && dayDate.getMonth() === this.chosenStartDate.getMonth() && dayDate.getFullYear() === this.chosenStartDate.getFullYear();
    }

    is3DaysFromStartDate(i){
        if(this.chosenStartDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        let startDate = new Date(this.chosenStartDate);
        startDate.setDate(startDate.getDate() + 2);
        return dayDate <= startDate
    }

    is3DaysFromEndDate(i){
        if(this.chosenEndDate === null){
            return false;
        }
        let dayDate = new Date(this.selectedMonth);
        dayDate.setDate(i);
        let endDate = new Date(this.chosenEndDate);
        endDate.setDate(endDate.getDate() - 2);
        return dayDate >= endDate
    }

    clearCalendar(){
        this.calendarDays.innerHTML = "";
    }

    chooseDay(dayHTML, i){
        let selectedDate = new Date(this.selectedMonth);
        selectedDate.setDate(i);
        if(this.activeInput === this.calendarInput1){
            this.chosenStartDate = selectedDate;
            if(this.is3DaysFromEndDate(i)){
                this.chosenEndDate = null;
                this.calendarInput2.value = "";
            }
        }
        else{
            this.chosenEndDate = selectedDate;
        }
        this.insertMonthDays();

        this.activeInput.value = i + "-" + (this.selectedMonth.getMonth()+1).toLocaleString('en-US', { minimumIntegerDigits: 2, useGrouping: false }) + "-" + this.selectedMonth.getFullYear();
        this.activeInput.dispatchEvent(new Event('input'));
        this.hide();
    }
}