<?php
/**
 * Script for admin notifications in free WordPress themes
 *
 * @since      1.0.0
 * @package    Admin_Notifications
 */





if ( ! function_exists( 'polygon_notifications_setup' ) ) {

	/**
	 * Configure admin notifications.
	 *
	 * Register and configure admin notifications by adding arrays like the one below
	 * to the $config variable. All parameters are required.
	 *
	 * $config[] = array(
	 *     'id'                      => 'polygon_notification_one',                                               // Unique notification ID containing the slug
	 *     'title'                   => __( 'First Notification', 'polygon' ),                                    // Notification title
	 *     'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),    // Notification description
	 *     'ok-button-label'         => __( 'Do Something', 'polygon' ),                                          // Label for the OK button
	 *     'no-button-label'         => __( 'Hide Notice', 'polygon' ),                                           // Label for the NO button
	 *     'ok-button-url'           => 'https://polygonthemes.com',                                              // External URL for the OK button
	 *     'id-next'                 => 'polygon_notification_two',                                               // ID of the next notification to display ( false or string )
	 *     'display-after-days'      => 30,                                                                       // Number of days after the notification is displayed ( false or int )
	 *     'display-after-days-next' => 30,                                                                       // Number of days after the next notification is displayed ( false or int )
	 *     'manual-trigger'          => false,                                                                    // Manual trigger of the notification - action based
	 * );
	 *
	 * The function must be added using the folowing filter:
	 *     - 'polygon_activation_setup'
	 *     - 'polygon_display_notifications'
	 *     - 'polygon_ignore_notifications'.
	 *
	 * @since     1.0.0
	 * @return    array    Array of notices with detailed parameters.
	 */
	function polygon_notifications_setup( $config ) {

		$config[] = array(
			'id'                      => 'polygon_notification_one',
			'title'                   => __( 'First Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'Do Something', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'ok-button-url'           => 'https://polygonthemes.com',
			'id-next'                 => 'polygon_notification_two',
			'display-after-days'      => 30,
			'display-after-days-next' => 30,
			'manual-trigger'          => false,
		);

		$config[] = array(
			'id'                      => 'polygon_notification_two',
			'title'                   => __( 'Second Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'Do Something', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'ok-button-url'           => 'https://polygonthemes.com',
			'id-next'                 => 'polygon_notification_three',
			'display-after-days'      => false,
			'display-after-days-next' => 60,
			'manual-trigger'          => false,
		);

		$config[] = array(
			'id'                      => 'polygon_notification_three',
			'title'                   => __( 'Third Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'Do Something', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'ok-button-url'           => 'https://polygonthemes.com',
			'id-next'                 => false,
			'display-after-days'      => false,
			'display-after-days-next' => false,
			'manual-trigger'          => false,
		);

		return $config;
	}
	add_filter( 'polygon_activation_setup', 'polygon_notifications_setup' );
	add_filter( 'polygon_display_notifications', 'polygon_notifications_setup' );
	add_filter( 'polygon_ignore_notifications', 'polygon_notifications_setup' );

}














class Polygon_Admin_Notifications {

	/**
	 * Initialize the class and set its properties.
	 *
	 * Register the hooks required for our class.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Register hooks
		add_action( 'after_switch_theme', array( $this, 'activation_setup' ) );
		add_action( 'admin_notices', array( $this, 'display_notifications' ) );
		add_action( 'admin_init', array( $this, 'ignore_notifications' ) );

	}





	/**
	 * Activation setup for bottle messages.
	 *
	 * Cycle through the existing users and clean their meta after switching themes
	 * to prevent conflicts with old data. By deactivating / reactivating the theme
	 * all counters are reset.
	 *
	 * @since     1.0.0
	 */
	public function activation_setup() {
		// Variables
		$notices = apply_filters( 'polygon_activation_setup', null );
		$users   = get_users();

		foreach ( $users as $user ) {
			foreach ( $notices as $notice ) {
				// Variables ( Per Item )
				$meta_key_flag      = $notice['id'] . '_ignore_flag';
				$meta_key_timestamp = $notice['id'] . '_timestamp';

				// Remove old user meta
				delete_user_meta( $user->ID, $meta_key_flag );
				delete_user_meta( $user->ID, $meta_key_timestamp );
			}
		}
	}





	/**
	 * Display notifications.
	 *
	 * If the current user has admin privileges cycle through all notices and display
	 * them when the following conditions are met.
	 *     - the 'manual-trigger' variable is set
	 *     - the number of days set in 'display-after-days' has passed
	 *
	 * When the notification message is displayed the user has the option to go to an
	 * external URL or to permanently dismiss the message.
	 *
	 * @since     1.0.0
	 */
	public function display_notifications() {
		if ( current_user_can( 'manage_options' ) ) {
			// Global variables
			global $current_user;

			// Variables
			$notices = apply_filters( 'polygon_display_notifications', null );

			if ( $notices ) {
				foreach( $notices as $notice ) {

					// Variables ( Per Item )
					$id                      = $notice['id'];
					$title                   = $notice['title'];
					$description             = $notice['description'];
					$ok_button_label         = $notice['ok-button-label'];
					$no_button_label         = $notice['no-button-label'];
					$ok_button_url           = $notice['ok-button-url'];
					$id_next                 = $notice['id-next'];
					$display_after_days      = $notice['display-after-days'];
					$display_after_days_next = $notice['display-after-days-next'];
					$manual_trigger          = $notice['manual-trigger'];

					$meta_key_flag           = $notice['id'] . '_ignore_flag';
					$meta_key_timestamp      = $notice['id'] . '_timestamp';



					// Manipulate variables
					if ( $display_after_days ) {
						$display_after_days = $display_after_days * DAY_IN_SECONDS;
					}

					if ( $display_after_days_next ) {
						$display_after_days_next = $display_after_days_next * DAY_IN_SECONDS;
					}



					// Set the initial timestamp
					if ( ! get_user_meta( $current_user->ID, $meta_key_timestamp ) ) {
						if ( $display_after_days ) {
							add_user_meta( $current_user->ID, $meta_key_timestamp, time() + $display_after_days, true );
						} else {
							add_user_meta( $current_user->ID, $meta_key_timestamp, time() + YEAR_IN_SECONDS, true );
						}
					}



					// Retreive the current timestamp
					$display_after_days_timestamp = get_user_meta( $current_user->ID, $meta_key_timestamp, true );



					// Display notification
					if ( ( $manual_trigger ) && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) ||
						( ( ! $manual_trigger ) && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) && ( $display_after_days_timestamp < time() ) ) ) {
							?>
								<div class="updated polygon-info">
									<p></p>
									<p><b><?php echo wp_kses_post( $title ); ?></b></p>
									<p><?php echo wp_kses_post( $description ); ?></p>
									<p>
										<a href="<?php echo esc_url( $ok_button_url ); ?>" target="_blank"><b><?php echo esc_html( $ok_button_label ); ?></b></a>
										|
										<a href="?<?php echo esc_attr( $meta_key_flag ); ?>=0"><b><?php echo esc_html( $no_button_label ); ?></b></a>
									</p>
									<p></p>
								</div>
							<?php
					}

				}

			}
		}
	}





	/**
	 * Hide notifications.
	 *
	 * Check if the notifications are dismissed update the user meta accordingly.
	 *
	 * @since     1.0.0
	 */
	public function ignore_notifications() {
		if ( current_user_can( 'manage_options' ) ) {
			// Global variables
			global $current_user;

			// Variables
			$notices = apply_filters( 'polygon_ignore_notifications', null );

			if ( $notices ) {
				foreach( $notices as $notice ) {

					// Variables ( Per Item )
					$id_next                  = $notice['id-next'];
					$display_after_days_next  = $notice['display-after-days-next'];

					$meta_key_flag            = $notice['id'] . '_ignore_flag';
					$meta_key_timestamp_next  = $notice['id-next'] . '_timestamp';



					// Manipulate variables
					$display_after_days_next = $display_after_days_next * DAY_IN_SECONDS;



					// Update user meta when the NO button is pressed
					if ( isset( $_GET[$meta_key_flag] ) && ( $_GET[$meta_key_flag] == 0 ) ) {
						if ( ( $id_next ) && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) ) {
							update_user_meta( $current_user->ID, $meta_key_timestamp_next, time() + $display_after_days_next );
						}
						add_user_meta( $current_user->ID, $meta_key_flag, 'true', true );
					}

				}
			}
		}
	}

}





// Let's do this
$polygon_admin_notifications = new Polygon_Admin_Notifications;
