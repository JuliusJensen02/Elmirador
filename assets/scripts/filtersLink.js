class Calendar {
    constructor(calIn1, calIn2, calendar, filter) {
        this.calendar = calendar;
        this.calendarDays = this.calendar.querySelector('.calendarDays');
        this.calendarInput1 = calIn1;
        this.calendarInput2 = calIn2;
        this.currentMonthDisplay = this.calendar.querySelector('.currentMonth');
        this.today = new Date();
        this.tomorrow = new Date(this.today);
        this.tomorrow.setDate(this.tomorrow.getDate() + 1);
        this.selectedMonth = new Date(this.today);
        this.activeInput = null;
        this.chosenStartDate = null;
        this.chosenEndDate = null;
        this.clearDatesBtn = filter.querySelector('.clearDatesBtn');

        this.calendarInput1.addEventListener("focusin", this.show.bind(this));
        this.calendarInput2.addEventListener("focusin", this.show.bind(this));

        document.body.addEventListener("click", function(e){
            if(e.target.closest(".calendarFilter") === null){
                this.hide();
            }
        }.bind(this));

        this.changeMonth(0);
        this.insertMonthDays();

        this.calendar.querySelector('.nextMonth').addEventListener('click', function(){
            this.changeMonth(1);
        }.bind(this));

        this.calendar.querySelector('.prevMonth').addEventListener('click', function(){
            this.changeMonth(-1);
        }.bind(this));

        this.clearDatesBtn.addEventListener('click', function(){
            this.chosenStartDate = null;
            this.chosenEndDate = null;
            this.calendarInput1.value = "";
            this.calendarInput2.value = "";
            this.insertMonthDays();
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
    }

    insertMonthDay(i) {
        let day = document.createElement("div");
        day.textContent = i.toString();
        this.calendarDays.appendChild(day);
        if (this.isStartDate(i)) {
            day.classList.add("startdate");
        }
        if (this.isEndDate(i)) {
            day.classList.add("enddate");
        }
        if(this.isBetweenDates(i)){
            day.classList.add("betweenDates");
        }
        if (this.isDayBeforeToday(i) || (this.activeInput === this.calendarInput2 && (this.isDayBeforeStartDate(i) || this.is3DaysFromStartDate(i)))) {
            day.classList.add("disabled");
        }
        else {
            day.addEventListener('click', function () {
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
        return dayDate < this.tomorrow;
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
        this.activeInput.dispatchEvent(new Event('change'));
        this.hide();
    }
}
let filter = document.querySelector(".apartmentFiltersContainer #apartmentFilters");
if(filter != null) {
    let calendarInput1 = filter.querySelector('.calendarInput1');
    let calendarInput2 = filter.querySelector('.calendarInput2');
    let guestsFilter = filter.querySelector('.guestsFilter');

    guestsFilter.addEventListener("input", function () {
        if(parseInt(guestsFilter.value) < 1 || guestsFilter.value === "") {
            guestsFilter.value = "";
        }
        else if(parseInt(guestsFilter.value) > 12) {
            guestsFilter.value = 12;
        }
        else if(!(/^\d+$/.test(guestsFilter.value))) {
            guestsFilter.value = 1;
        }
        if(guestsFilter.value === ""){
            document.getElementById("chosenGuests").textContent = "1 gæst";
        }
        else if(parseInt(guestsFilter.value) === 1){
            document.getElementById("chosenGuests").textContent = guestsFilter.value + " gæst";
        }
        else {
            document.getElementById("chosenGuests").textContent = guestsFilter.value + " gæster";
        }
    });

    let calendar = new Calendar(calendarInput1, calendarInput2, filter.querySelector('.calendar'), filter);

    calendarInput1.addEventListener("change", function(){
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            let date1 = calendarInput1.value.replaceAll("-", ".");
            let date2 = calendarInput2.value.replaceAll("-", ".");
            document.getElementById("chosenDates").textContent = date1 + " - " + date2;
        }
    });

    calendarInput2.addEventListener("change", function(){
        if(calendarInput1.value !== "" && calendarInput2.value !== ""){
            let date1 = calendarInput1.value.replaceAll("-", ".");
            let date2 = calendarInput2.value.replaceAll("-", ".");
            document.getElementById("chosenDates").textContent = date1 + " - " + date2;
        }
    });

    filter.querySelector('.searchBtn').addEventListener("click", function () {
        let date1 = calendar.chosenStartDate.getFullYear() + "-" + (calendar.chosenStartDate.getMonth() + 1).toLocaleString('en-US', {
            minimumIntegerDigits: 2,
            useGrouping: false
        }) + "-" + calendar.chosenStartDate.getDate().toLocaleString('en-US', {
            minimumIntegerDigits: 2,
            useGrouping: false
        });
        let date2 = calendar.chosenEndDate.getFullYear() + "-" + (calendar.chosenEndDate.getMonth() + 1).toLocaleString('en-US', {
            minimumIntegerDigits: 2,
            useGrouping: false
        }) + "-" + calendar.chosenEndDate.getDate().toLocaleString('en-US', {
            minimumIntegerDigits: 2,
            useGrouping: false
        });
        if(guestsFilter.value === ""){
            guestsFilter.value = 1;
        }
        window.open("https://elmirador.dk/boliger/?startDate=" + date1 + "&endDate=" + date2 + "&guests=" + guestsFilter.value, "_self");
    });

    document.getElementById("apartmentFilterLabelBox").addEventListener("click", function () {
        document.querySelector(".apartmentFiltersContainer").classList.toggle("active");
    });

    window.addEventListener("click", function(e){
        (e.target.closest("#apartmentFilterLabelBox") === null && e.target.closest("#apartmentFilters") === null && e.target.parentNode != null) ? document.querySelector(".apartmentFiltersContainer").classList.remove("active") : null;
    });

    window.addEventListener("scroll", function(){
        if(window.pageYOffset <= 70){
            document.querySelector(".elementor-element-59b2ad1").style.top = 170-window.pageYOffset + "px";
        }
        else{
            document.querySelector(".elementor-element-59b2ad1").style.top = 100 + "px";
        }
    });
}