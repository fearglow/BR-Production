<?php
/**
 * Plugin Name: Custom Booking Calendar
 * Description: Custom plugin to display bookings from custom table on a calendar from campsite bookings.
 * Version: 1.0
 * Author: Ryan Pittman
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}



function custom_booking_calendar_enqueue_scripts() {
    global $wpdb; // Ensure you have access to the database

    // First, fetch the bookings to determine the earliest date
    $bookings = fetch_bookings();
    $earliestDate = !empty($bookings) ? $bookings[0]['start'] : date('Y-m-d');

    wp_enqueue_script('jquery');
    wp_enqueue_script('moment', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js', array('jquery'), '2.29.1', true);
    wp_enqueue_script('fullcalendar', "https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js", array('jquery', 'moment'), '3.10.2', true);
    wp_enqueue_style('custom-booking-styles', plugins_url('/css/custom-booking-styles.css', __FILE__), array(), '1.0', 'all');
    wp_enqueue_style('fullcalendar-css', "https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css", array(), '3.10.2', 'all');

    // Enqueue a custom script to initialize the calendar
    wp_enqueue_script('custom-booking-init', plugins_url('/js/init-calendar.js', __FILE__), array('jquery', 'fullcalendar'), '1.0', true);

    // Now localize the script with booking data, ajax URL, and nonce
    wp_localize_script('custom-booking-init', 'bookingData', array(
        'bookings' => $bookings,
        'earliestBookingDate' => $earliestDate,
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('mark_as_paid_nonce'),
		'fetch_updated_bookings_nonce' => wp_create_nonce('fetch_updated_bookings_nonce'), 
    ));
}
add_action('wp_enqueue_scripts', 'custom_booking_calendar_enqueue_scripts');

function fetch_bookings() {
    global $wpdb;

    $query = "
        SELECT 
            oim.user_id, oim.order_item_id, oim.check_in, oim.check_out, oim.st_booking_id,
            p.post_title AS post_name, COALESCE(pm1.meta_value, um.meta_value) as first_name, 
            COALESCE(pm2.meta_value, um2.meta_value) as last_name, oim.adult_number, oim.child_number, 
            oim.infant_number, oim.status, oim.cash_paid,
            pm.meta_value as payment_method_name
        FROM {$wpdb->prefix}st_order_item_meta oim
        INNER JOIN {$wpdb->users} u ON oim.user_id = u.ID
        INNER JOIN {$wpdb->usermeta} um ON u.ID = um.user_id AND um.meta_key = 'first_name'
        INNER JOIN {$wpdb->usermeta} um2 ON u.ID = um2.user_id AND um2.meta_key = 'last_name'
        INNER JOIN {$wpdb->posts} p ON oim.st_booking_id = p.ID
        LEFT JOIN {$wpdb->postmeta} pm ON oim.order_item_id = pm.post_id AND pm.meta_key = 'payment_method_name'		
		LEFT JOIN {$wpdb->postmeta} pm1 ON oim.order_item_id = pm1.post_id AND pm1.meta_key = 'st_first_name'
        LEFT JOIN {$wpdb->postmeta} pm2 ON oim.order_item_id = pm2.post_id AND pm2.meta_key = 'st_last_name'
        WHERE oim.status = 'complete'
    ";

    $bookings = $wpdb->get_results($query, OBJECT);

    $formatted_bookings = array();
    foreach ($bookings as $booking) {
        $isCashPayment = strtolower(trim($booking->payment_method_name)) === 'cash';
        $booking_title = "Site: " . explode(':', $booking->post_name)[0] . "\nCustomer: " . $booking->first_name . ' ' . $booking->last_name;
		$site_parts = explode(':', $booking->post_name);
        $site_name_before_colon = trim($site_parts[0]);
		$adjusted_checkout_date = date('Y-m-d', strtotime($booking->check_out . ' +1 day'));
		$status = $isCashPayment && !$booking->cash_paid ? 'Cash Payment Due' : ($booking->cash_paid ? 'Paid Cash' : $booking->status);
		
        $formatted_bookings[] = array(
            'id' => $booking->order_item_id,
            'title' => $booking_title,
            'site' => $site_name_before_colon,
            'start' => $booking->check_in,
            'end' => $adjusted_checkout_date,
            'display_end' => $booking->check_out,
            'adult_number' => $booking->adult_number,
            'child_number' => $booking->child_number,
            'infant_number' => $booking->infant_number,
            'status' => $status,
			'customer' => $booking->first_name . ' ' . $booking->last_name,
            'stay' => $booking->check_in . ' - ' . $booking->check_out,
            'isCashPayment' => $isCashPayment,
			'payment_method_name' => $booking->payment_method_name,
            'st_booking_id' => $booking->st_booking_id, // Include the booking ID for reference
			'cash_paid' => $booking->cash_paid,
        );
    }

    return $formatted_bookings;
}


add_action('wp_ajax_fetch_updated_bookings', 'fetch_updated_bookings_callback');

function fetch_updated_bookings_callback() {
    // Assuming fetch_bookings() returns an array of bookings
    $updated_bookings = fetch_bookings();

    // Send the updated bookings data back as JSON
    wp_send_json_success($updated_bookings);

    wp_die(); // Terminate AJAX execution
}


add_action('wp_ajax_mark_as_paid', 'mark_as_paid_callback');

function mark_as_paid_callback() {
    global $wpdb; 
    
    // Verify nonce for security
    check_ajax_referer('mark_as_paid_nonce', 'nonce');

    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    
    // Prevent function from proceeding if booking ID is not provided
    if ($booking_id <= 0) {
        wp_send_json_error(array('message' => 'Invalid booking ID.'));
        wp_die();
    }

    // Update the database to mark the booking as paid in cash
    $updated = $wpdb->update(
        "{$wpdb->prefix}st_order_item_meta",
        array('cash_paid' => 1), // Set cash_paid to true
        array('order_item_id' => $booking_id), 
        array('%d'), // Format of the value
        array('%d')  // Format of the where clause value
    );

    // Immediately check for any database errors
    if (!empty($wpdb->last_error)) {
        wp_send_json_error(array('message' => 'Database error: ' . $wpdb->last_error));
        wp_die();
    }

    // Evaluate the result of the update operation
    if ($updated) {
        wp_send_json_success(array('message' => 'Booking marked as paid.'));
    } else {
        // Consider additional logic here to handle the case where $updated is 0 (no rows updated)
        // This might occur if the row was already marked as paid, or if the booking_id does not exist
        wp_send_json_error(array('message' => 'Failed to mark the booking as paid or booking already marked.'));
    }

    wp_die();
}


function custom_booking_calendar_shortcode() {
    return '<div id="calendar"></div>';
}
add_shortcode('custom_booking_calendar', 'custom_booking_calendar_shortcode');



	function custom_booking_calendar_with_debug_info_shortcode() {
		ob_start(); // Start output buffering to capture all outputs

		// The calendar HTML
		$calendar_html = '<div id="calendar"></div>';
		echo $calendar_html;

		// Fetch and format bookings
		$formatted_bookings = fetch_bookings();

		// Print each booking's raw data for debugging
		echo '<h3>Booking Debug Information:</h3><pre>';
		foreach ($formatted_bookings as $booking) {
			print_r($booking); // Use print_r() to output the raw data of each booking
		}
		echo '</pre>';

		return ob_get_clean(); // Return the captured output
	}
	add_shortcode('custom_booking_calendar_debug', 'custom_booking_calendar_with_debug_info_shortcode');

