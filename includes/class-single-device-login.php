<?php

/**
 *
 * @link       https://ulayers.com/
 * @since      1.0.0
 * @author     LAYERS <info@ulayers.com>
 * @package    Single_Device_Login
 */

class Single_Device_Login {
    
    /**
	 * The COOKIE_NAME Variable That saved in the User Browser Cookies
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string
	 */
    const COOKIE_NAME = '_sdl';

    public function __construct() {
        add_action('wp_login', [$this, 'set_user_device_cookie'], 10, 2);
        add_filter('wp_authenticate_user', [$this, 'check_device_cookie'], 10, 2);
        add_action('show_user_profile', [$this, 'add_reset_device_button']);
        add_action('edit_user_profile', [$this, 'add_reset_device_button']);
        add_action('edit_user_profile_update', [$this, 'handle_reset_device_hash']);
        add_action('personal_options_update', [$this, 'handle_reset_device_hash']);
    }

	/**
	 * Set random hash to user device cookie in it's browser & in DB
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function set_user_device_cookie($user_login, $user) {
        if (in_array('administrator', $user->roles)) {
            return;
        }

        $existing_hash = get_user_meta($user->ID, 'sdl_cookie_hash', true);
        
        if (empty($existing_hash)) {
            $hash = hash('sha256', mt_rand());
            setcookie(self::COOKIE_NAME, $hash, time() + (10 * 365 * 24 * 60 * 60), COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
            update_user_meta($user->ID, 'sdl_cookie_hash', $hash);
        }
    }

    /**
	 * Check for hash while user authenticate
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function check_device_cookie($user, $password) {
        if (!is_wp_error($user) && !in_array('administrator', (array) $user->roles)) {
            $existing_hash = get_user_meta($user->ID, 'sdl_cookie_hash', true);
            if (!empty($existing_hash)) {
                if (!isset($_COOKIE[self::COOKIE_NAME]) || $_COOKIE[self::COOKIE_NAME] !== $existing_hash) {
                    wp_logout();
                    $error_message = __('Device Mismatch - You can only log in from your registered device.', 'sdl-trans');
                    return new WP_Error('device_mismatch', $error_message);
                }
            }
        }
        return $user;
    }

    /**
	 * Add reset hash button in user profile page
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function add_reset_device_button($user) {
        ?>
        <h3>Device Management</h3>
        <table class="form-table">
            <tr>
                <th><label for="reset-device">Reset Device Hash</label></th>
                <td>
                    <form method="post">
                        <?php
                        wp_nonce_field('reset_device_hash', 'reset_device_hash_nonce');
                        ?>
                        <input type="hidden" name="reset_hash_user_id" value="<?php echo esc_attr($user->ID); ?>">
                        <input type="submit" class="button button-secondary" value="Reset Device" name="reset_device_hash">
                    </form>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
	 * Handle the reset hash button
	 *
	 * @since    1.0.0
     * @access   public
	 */
    public function handle_reset_device_hash() {
        if (isset($_POST['reset_device_hash']) && isset($_POST['reset_hash_user_id']) && isset($_POST['reset_device_hash_nonce']) && wp_verify_nonce($_POST['reset_device_hash_nonce'], 'reset_device_hash')) {
            $user_id = intval($_POST['reset_hash_user_id']);
            if (current_user_can('edit_user', $user_id)) {
                delete_user_meta($user_id, 'sdl_cookie_hash');
                $this->destroy_all_sessions_for_user($user_id);
            } else {
                wp_die('You do not have permission to edit this user.');
            }
        }
    }

    /**
	 * Destroy sessions while button reset clicked
	 *
	 * @since    1.0.0
     * @access   public
	 */
    private function destroy_all_sessions_for_user($user_id) {
        $user = get_user_by('id', $user_id);
        if ($user) {
            $session_tokens = WP_Session_Tokens::get_instance($user_id);
            $session_tokens->destroy_all();
        }
    }
}