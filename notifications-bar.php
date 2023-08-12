<?php
/*
Plugin Name: Notification Bar Plugin
Description: Plugin that add a personalized notification in your website
Version: 1.0
Author: Caleb Buffleben
*/

// Enqueue CSS styles
function nbp_enqueue_styles() {
    wp_enqueue_style('notification-bar-css', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'nbp_enqueue_styles');

// Add the notification bar
function nbp_add_notification_bar() {
    $notification_text = get_option('nbp_notification_text', 'This is an important message.');
    
    echo '<div id="notification-bar">' . esc_html($notification_text) . '<button id="notification-close-btn">&times;</button> </div>';
}
add_action('wp_footer', 'nbp_add_notification_bar');

// Automatically hide notification bar after 10 seconds using JavaScript
function nbp_auto_hide_notification_bar() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                var notificationBar = document.getElementById("notification-bar");
                if (notificationBar) {
                    notificationBar.style.display = "none";
                }
            }, 10000); // 10 seconds
            
            var closeButton = document.getElementById("notification-close-btn");
            if (closeButton) {
                closeButton.addEventListener("click", function() {
                    var notificationBar = document.getElementById("notification-bar");
                    if (notificationBar) {
                        notificationBar.style.display = "none";
                    }
                });
            }
        });
    </script>';
}
add_action('wp_footer', 'nbp_auto_hide_notification_bar');

// Add settings page to customize notification bar text
function nbp_notification_settings_page() {
    add_options_page('Notification Bar Settings', 'Notification Bar', 'manage_options', 'nbp-settings', 'nbp_render_settings_page');
}
add_action('admin_menu', 'nbp_notification_settings_page');

// Render settings page
function nbp_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Save settings when form is submitted
    if (isset($_POST['nbp_notification_text'])) {
        update_option('nbp_notification_text', sanitize_text_field($_POST['nbp_notification_text']));
    }
    
    $notification_text = get_option('nbp_notification_text', 'This is an important message.');
    ?>
    <div class="wrap">
        <h2>Notification Bar Settings</h2>
        <form method="post" action="">
            <label for="nbp_notification_text">Notification Text:</label>
            <input type="text" id="nbp_notification_text" name="nbp_notification_text" value="<?php echo esc_attr($notification_text); ?>" style="width: 100%;" />
            <p class="description">Enter the text you want to display in the notification bar.</p>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}

// Load CSS for the notification bar
function nbp_load_custom_css() {
    wp_enqueue_style('notification-bar-css', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'nbp_load_custom_css');
