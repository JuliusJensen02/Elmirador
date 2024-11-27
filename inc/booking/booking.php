<?php

use booking\classes\BookingMail;
use booking\classes\BookingOrder;

define('BOOKING_DIR', INC_DIR . '/booking');

/**
 * Booking classes
 */
require_once BOOKING_DIR . '/classes/BookingInvoice.php';
require_once BOOKING_DIR . '/classes/BookingOrder.php';
require_once BOOKING_DIR . '/classes/BookingMail.php';
require_once BOOKING_DIR . '/classes/BookingDiscount.php';

/**
 * Booking shortcodes
 */
require_once BOOKING_DIR . '/shortcodes/boligArkivAdvancedPrice.php';
require_once BOOKING_DIR . '/shortcodes/thankYouBooking.php';
require_once BOOKING_DIR . '/shortcodes/bookingTable.php';

/**
 * Booking functions
 */
require_once BOOKING_DIR . '/updateBookingOrder.php';
require_once BOOKING_DIR . '/deleteBookingOrder.php';


