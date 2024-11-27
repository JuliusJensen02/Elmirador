<?php

add_shortcode("filtersLink", "filtersLink");
function filtersLink(): void {
	?>
    <div class="apartmentFiltersContainer">
        <div id="apartmentFilterLabelBox">
            <div class="content">
                <h3>Start booking</h3>
                <div class="info">
                    <p id="chosenDates"><?php _e("Vælg dato", "Hefa_thema") ?></p>
                    <span class="sep"></span>
                    <p id="chosenGuests"><?php _e("2 gæster", "Hefa_thema") ?></p>
                </div>
            </div>
            <div class="icon">
                <svg id="search_6_" data-name="search (6)" xmlns="http://www.w3.org/2000/svg" width="22.892" height="22.892" viewBox="0 0 22.892 22.892">
                    <path id="Path_92" data-name="Path 92" d="M22.581,21.234,16.9,15.548A9.541,9.541,0,1,0,15.548,16.9l5.686,5.686a.953.953,0,1,0,1.347-1.347ZM9.524,17.145a7.621,7.621,0,1,1,7.621-7.621,7.621,7.621,0,0,1-7.621,7.621Z" transform="translate(0.032 0.032)" fill="#fbfbfb"/>
                </svg>
            </div>
        </div>
        <div id="apartmentFilters">
            <div class="filter">
                <label><?php _e("Gæster", "Hefa_theme") ?></label>
                <input class="guestsFilter" type="number" min="1" max="12" step="1" value="2">
            </div>
            <div class="filter calendarFilter">
                <div class="inputs">
                    <div class="input">
                        <label><?php _e("Ankomst", "Hefa_theme") ?></label>
                        <input type="text" value="" placeholder="<?php _e("Vælg dato", "Hefa_theme") ?>" class="calendarInput1" readonly>
                    </div>
                    <div class="input">
                        <label><?php _e("Afrejse", "Hefa_theme") ?></label>
                        <input type="text" value="" placeholder="<?php _e("Vælg dato", "Hefa_theme") ?>" class="calendarInput2" readonly>
                    </div>
                </div>
                <button class="clearDatesBtn"><?php _e("Ryd datoer", "Hefa_theme") ?></button>
                <div class="calendar">
                    <div class="calendar-controls">
                        <button class="calendar-control prevMonth"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M18 15.5a1 1 0 0 1-.71-.29l-4.58-4.59a1 1 0 0 0-1.42 0l-4.58 4.59a1 1 0 0 1-1.42-1.42l4.59-4.58a3.06 3.06 0 0 1 4.24 0l4.59 4.58a1 1 0 0 1 0 1.42 1 1 0 0 1-.71.29Z" fill="#d59f54" opacity="1" data-original="#000000"/></g></svg></button>
                        <div class="currentMonth"></div>
                        <button class="calendar-control nextMonth"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M18 15.5a1 1 0 0 1-.71-.29l-4.58-4.59a1 1 0 0 0-1.42 0l-4.58 4.59a1 1 0 0 1-1.42-1.42l4.59-4.58a3.06 3.06 0 0 1 4.24 0l4.59 4.58a1 1 0 0 1 0 1.42 1 1 0 0 1-.71.29Z" fill="#d59f54" opacity="1" data-original="#000000"/></g></svg></button>
                    </div>
                    <div class="calendarDays"></div>
                </div>
            </div>
            <div class="searchCtn">
                <button class="searchBtn">Søg
                    <svg id="search_6_" data-name="search (6)" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="Path_92" data-name="Path 92" d="M15.773,14.831,11.8,10.857a6.668,6.668,0,1,0-.941.941l3.974,3.974a.666.666,0,1,0,.941-.941ZM6.647,11.973a5.326,5.326,0,1,1,5.326-5.326,5.326,5.326,0,0,1-5.326,5.326Z" transform="translate(0.032 0.032)" fill="#fbfbfb"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
	<?php
}