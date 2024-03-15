<?php
/**
 * Created by PhpStorm.
 * User: MSI
 * Date: 21/08/2015
 * Time: 9:45 SA
 */
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles', 20 );

function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}


function meks_which_template_is_loaded() {
	if ( is_super_admin() ) {
		global $template;
		print_r( $template );
	}
}
 

function check_and_send_departure_date_notices() {
    $log_path = WP_CONTENT_DIR . '/debug-cron.log';
    $timestamp = current_time('mysql'); // Get the current time, according to your WordPress timezone settings.
    
    file_put_contents($log_path, "Cron Job Started - " . $timestamp . "\n", FILE_APPEND);

    global $wpdb;
    $table = $wpdb->prefix . 'st_order_item_meta';
    
    // Use WordPress's current_time function to get the timestamp for 2 days from now in the site's timezone
    $two_days_from_now = strtotime('+2 days', current_time('timestamp'));
    
    // Convert these to the start and end of the day in the site's timezone
    $start_of_day = strtotime(date('Y-m-d 00:00:00', $two_days_from_now));
    $end_of_day = strtotime(date('Y-m-d 23:59:59', $two_days_from_now));

    // Adjust your query as needed
    $query = $wpdb->prepare("SELECT order_item_id FROM {$table} WHERE check_in_timestamp >= %d AND check_in_timestamp <= %d", $start_of_day, $end_of_day);
    $bookings = $wpdb->get_results($query);

    // Log the query and its result
    file_put_contents($log_path, "Query executed: {$query}\n", FILE_APPEND);
    file_put_contents($log_path, "Query returned " . count($bookings) . " bookings\n", FILE_APPEND);

    if (!empty($bookings)) {
        foreach ($bookings as $booking) {
            file_put_contents($log_path, "[$timestamp] Processing booking ID: {$booking->order_item_id}\n", FILE_APPEND);

            if (class_exists('STUser_f')) {
                $st_user = new STUser_f();
                $result = $st_user->_send_partner_notice_departure_date($booking->order_item_id);
                file_put_contents($log_path, "[$timestamp] Attempted to send email for booking ID {$booking->order_item_id}: " . ($result ? "Success" : "Fail") . "\n", FILE_APPEND);
            } else {
                file_put_contents($log_path, "[$timestamp] STUser_f class not found.\n", FILE_APPEND);
            }
        }
    } else {
        file_put_contents($log_path, "[$timestamp] No bookings found for the specified period.\n", FILE_APPEND);
    }
}


add_filter('cron_schedules', 'add_custom_cron_schedule');
function add_custom_cron_schedule($schedules) {
    if (!isset($schedules['daily'])) {
        $schedules['daily'] = [
            'interval' => 86400,
            'display'  => __('Once Daily'),
        ];
    }
    return $schedules;
}

add_action('wp', 'register_daily_order_check_event');
function register_daily_order_check_event() {
    if (!wp_next_scheduled('send_departure_date_notice')) {
        wp_schedule_event(time(), 'daily', 'send_departure_date_notice');
    }
}
add_action('send_departure_date_notice', 'check_and_send_departure_date_notices');






function st_add_custom_admin_page() {
    add_menu_page(
        'Test Email Send', // Page title
        'Test Email Send', // Menu title
        'manage_options', // Capability
        'st-test-email-send', // Menu slug
        'st_custom_admin_page_content', // Function to display the admin page
        'dashicons-email-alt', // Icon URL
        6 // Position
    );
}



add_action('admin_menu', 'st_add_custom_admin_page');




function st_custom_admin_page_content() {
    // Check if the user has submitted the form
    if (isset($_POST['st_test_order_id'])) {
        $order_id = sanitize_text_field($_POST['st_test_order_id']);

        // Make sure the class exists before using it
        if (class_exists('STUser_f')) {
            // Instantiate the STUser_f class
            $st_user = new STUser_f();

            // Use the method to send the partner notice
            $result = $st_user->_send_partner_notice_departure_date($order_id);

            // Display the result
            echo '<div>Triggered email send for Order ID: ' . esc_html($order_id) . '. Result: ' . ($result ? 'Success' : 'Fail') . '</div>';
        } else {
            echo '<div>Error: STUser_f class not found.</div>';
        }
    }

    // Display the form
    ?>
    <div class="wrap">
        <h2>Test Email Send</h2>
        <form method="post">
            <label for="st_test_order_id">Order ID:</label>
            <input type="text" id="st_test_order_id" name="st_test_order_id">
            <input type="submit" value="Send Test Email">
        </form>
    </div>
    <?php
}

function st_send_email_by_order_id($order_id) {
    $log_path = WP_CONTENT_DIR . '/debug-cron-send.log';
    $timestamp = current_time('mysql'); // Current time for logging
    
    if (class_exists('STUser_f')) {
        $user_info = get_userdata(5);
        if ($user_info) {
            file_put_contents($log_path, "[$timestamp] Switching to User ID 5.\n", FILE_APPEND);
            wp_set_current_user(5);

            file_put_contents($log_path, "[$timestamp] Prepared email headers.\n", FILE_APPEND);

             try {
                // Attempt to send the email
                $result = STUser_f::_send_partner_notice_departure_date($order_id);
                
                // Log the result of the email send attempt
                file_put_contents($log_path, "[$timestamp] Email send attempt for order ID $order_id returned: " . var_export($result, true) . "\n", FILE_APPEND);
            } catch (Exception $e) {
                // Log any exceptions thrown during the email send attempt
                file_put_contents($log_path, "[$timestamp] Exception caught while sending email for order ID $order_id: " . $e->getMessage() . "\n", FILE_APPEND);
                $result = false;
            }

            wp_set_current_user(0); // Revert to previous user
            return $result;
        } else {
            file_put_contents($log_path, "[$timestamp] User ID 5 not found.\n", FILE_APPEND);
            return false;
        }
    } else {
        file_put_contents($log_path, "[$timestamp] STUser_f class not found.\n", FILE_APPEND);
        return false;
    }
}


function st_trigger_cron_job_admin_page() {
    add_menu_page('Trigger Cron Job', 'Trigger Cron Job', 'manage_options', 'st-trigger-cron', 'st_trigger_cron_job_page_content', 'dashicons-update');
}
add_action('admin_menu', 'st_trigger_cron_job_admin_page');

function st_trigger_cron_job_page_content() {
    echo '<div class="wrap"><h1>Trigger Cron Job</h1>';
    if (isset($_GET['trigger_cron']) && current_user_can('manage_options')) {
        do_action('send_departure_date_notice');
        echo '<p>Cron job triggered!</p>';
    }
    echo '<a href="'.admin_url('admin.php?page=st-trigger-cron&trigger_cron=true').'" class="button button-primary">Trigger Cron Job</a>';
    echo '</div>';
}

add_action('wp_footer', 'show_included_files');
function show_included_files() {
    if (current_user_can('administrator')) { // Check if user is an administrator.
        $included_files = get_included_files();
        $theme_directories = array(
            get_template_directory(), // Main theme directory.
            get_stylesheet_directory() // Child theme directory.
        );

        ?>
        <div id="includedFilesContainer" style="margin-top: 20px; clear: both; background-color: #fff; color: #000; padding: 10px; position: fixed; bottom: 0; left: 0; height: 200px; overflow: auto; z-index: 9999;">
            <strong>Included Theme Files:</strong>
            <button onclick="toggleFilesVisibility();" id="toggleFilesBtn" style="margin-bottom: 10px;">Hide</button><br>
            <div id="filesList">
                <?php 
                foreach ($included_files as $filename) {
                    foreach ($theme_directories as $theme_directory) {
                        if (strpos($filename, $theme_directory) !== false) {
                            echo $filename . '<br>';
                            break;
                        }
                    }
                } ?>
            </div>
        </div>
        <script>
        function toggleFilesVisibility() {
            var filesList = document.getElementById('filesList');
            var btnText = document.getElementById('toggleFilesBtn');
            if (filesList.style.display === "none") {
                filesList.style.display = "block";
                btnText.innerHTML = "Hide";
            } else {
                filesList.style.display = "none";
                btnText.innerHTML = "Show";
            }
        }
        </script>
        <?php
    }
}


