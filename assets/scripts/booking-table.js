jQuery(document).ready(function ($) {
    let request = null;
    let bookingContainer = document.getElementById('booking-container');
    window.addEventListener('applyFilters', applyFilters);

    applyFilters();

    function applyFilters() {
        if(request !== null){
            request.abort();
            request = null;
        }
        let data = {
            action: 'get_bookings_custom'
        };
        getBookings(data);
    }

    function getBookings(data) {
        /**
         * @param ajax_object.ajaxurl {object}
         */
        return $.post(ajax_object.ajaxurl, data, function (response) {
            bookingContainer.innerHTML = "";
            let apartments = response.data;
            if(apartments.length === 0){
                bookingContainer.innerHTML = "Ingen bookings fundet";
                return;
            }
            else if(apartments.length > 1){
                bookingContainer.classList.add("multiple")
            }
            for (const apartment of apartments) {
                createTable(apartment);
            }
        });
    }

    /**
     * @param apartment
     * @param apartment.bookings[]
     * @param apartment.total
     * @param apartment.totalShare
     */
    function createTable(apartment){
        let table = document.createElement('div');
        table.classList.add('apartmentTable');
        table.append(
            createHeaderElement('Husnr.'),
            createHeaderElement('Bookingdato fra/til'),
            createHeaderElement('Status'),
            createHeaderElement('Beløb'),
            createHeaderElement('Omsætning'),
            createHeaderElement('Booking oprettet')
        );

        let odd = true;

        /**
         * @param booking.number {string}
         * @param booking.dates {string}
         * @param booking.status {string}
         * @param booking.price {string}
         * @param booking.share {string}
         * @param booking.creation {string}
         */
        for (const booking of apartment.bookings) {
            table.append(
                createBookingElm('house', booking.number, odd),
                createBookingElm('bookingDate', booking.dates, odd),
                createBookingElm('status', booking.status, odd),
                createBookingElm('total', booking.price, odd),
                createBookingElm('share', booking.share, odd),
                createBookingElm('date', booking.creation, odd)
            );
            odd = !odd;
        }

        table.append(createFooter(apartment.bookings.length, apartment.total, apartment.totalShare));

        bookingContainer.append(table);
    }

    function createHeaderElement(text){
        let headerElm = document.createElement('div');
        headerElm.classList.add('headerElm');
        headerElm.innerHTML = text;
        return headerElm;
    }

    /**
     * @param className {string}
     * @param text {string}
     * @param odd {boolean}
     * @returns {HTMLDivElement}
     */
    function createBookingElm(className, text, odd){
        let bodyElm = document.createElement('div');
        bodyElm.classList.add('bodyElm');
        bodyElm.classList.add(className);
        if(odd){
            bodyElm.classList.add('odd');
        }
        bodyElm.innerHTML = text;
        return bodyElm;
    }


    function createFooter(numberOfBookings, total, totalShare){
        let footer = document.createElement('div');
        footer.classList.add('footerElm');
        footer.append(
            createFooterElement('Antal bookings: ' + numberOfBookings),
            createFooterElement('Total: € ' + total),
            createFooterElement('Total omsætning: € ' + totalShare)
        );
        return footer;
    }

    function createFooterElement(text){
        let footerElm = document.createElement('div');
        footerElm.textContent = text;
        return footerElm;
    }
});