<?php
/**
 * Script for admin notifications in free WordPress themes
 *
 * @since   1.0.0
 * @package Chained_Admin_Notifications
 */

if ( ! function_exists( 'polygon_notifications_setup' ) ) {

	/**
	 * Configure admin notifications.
	 *
	 * Register and configure admin notifications by adding them as an array.
	 * For detailed usage instructions see the inline documentation from the
	 * 'Polygon_Admin_Notifications' class.
	 *
	 * @since  1.0.0
	 * @param  array $config Array with all notifocations.
	 * @return array         Array of notices with detailed parameters.
	 */
	function polygon_notifications_setup( $config ) {
		/*
		// Example with all parameters.
		$config[] = array(
			'id'                      => 'polygon_notification_one',                                               // Unique notification ID containing the slug ( required ).
			'type'                    => 'info',                                                                   // Notification type: info or error.
			'title'                   => __( 'First Notification', 'polygon' ),                                    // Notification title.
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),    // Notification description.
			'ok-button-label'         => __( 'Do Something', 'polygon' ),                                          // Label for the OK button.
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),                                           // Label for the NO button.
			'internal-url'            => 'edit-tags.php?taxonomy=category',                                        // Internal URL for the OK button ( Relative to the admin url ).
			'external-url'            => 'https://google.com',                                                     // External URL for the OK button.
			'display-after-days'      => 30,                                                                       // Number of days after the notification is displayed.
			'id-next'                 => 'polygon_notification_two',                                               // ID of the next notification to display.
			'display-next-after-days' => 30,                                                                       // Number of days after the next notification is displayed.
			'trigger-callback'        => trigger_logic(),                                                          // Callback function returning true or false to trigger notification on demand.
			'slim-notification'       => true,                                                                     // Remove extra margins for slimmer notifications.
		);
		*/

		$config[] = array(
			'id'                      => 'polygon_notification_one',
			'title'                   => __( 'First Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'External URL', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'external-url'            => 'https://polygonthemes.com',
			'display-after-days'      => 30,
			'id-next'                 => 'polygon_notification_two',
			'display-next-after-days' => 30,
		);

		$config[] = array(
			'id'                      => 'polygon_notification_two',
			'title'                   => __( 'Second Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'Internal URL', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'internal-url'            => 'edit-tags.php?taxonomy=category',
			'id-next'                 => 'polygon_notification_three',
			'display-next-after-days' => 30,
		);

		$config[] = array(
			'id'                      => 'polygon_notification_three',
			'title'                   => __( 'Third Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'ok-button-label'         => __( 'External URL', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'external-url'            => 'https://polygonthemes.com',
		);

		$config[] = array(
			'id'                      => 'polygon_notification_four',
			'type'                    => 'error',
			'title'                   => __( 'Action-Based Notification', 'polygon' ),
			'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),
			'no-button-label'         => __( 'Hide Notice', 'polygon' ),
			'trigger-callback'        => true,
		);

		return $config;
	}
	add_filter( 'polygon_admin_notifications', 'polygon_notifications_setup' );

}














if ( ! class_exists( 'Polygon_Admin_Notifications' ) ) {

	/**
	 * Class for dynamic admin notifications.
	 *
	 * This is a class containing the logic required to create dynamic chained
	 * notifications. To use it, all you need is a function with an array of notifications
	 * hooked on 'polygon_admin_notifications'. Only the 'id' parameter is required.
	 *
	 * function polygon_notifications_setup( $config ) {
	 *     $config[] = array(
	 *         'id'                      => 'polygon_notification_one',                                               // Unique notification ID containing the slug ( required )
	 *         'type'                    => 'info',                                                                   // Notification type: info or error
	 *         'title'                   => __( 'First Notification', 'polygon' ),                                    // Notification title
	 *         'description'             => __( 'Say something nice and useful to your admin users.', 'polygon' ),    // Notification description
	 *         'ok-button-label'         => __( 'Do Something', 'polygon' ),                                          // Label for the OK button
	 *         'no-button-label'         => __( 'Hide Notice', 'polygon' ),                                           // Label for the NO button
	 *         'internal-url'            => 'edit-tags.php?taxonomy=category',                                        // Internal URL for the OK button ( Relative to the admin url )
	 *         'external-url'            => 'https://google.com',                                                     // External URL for the OK button
	 *         'display-after-days'      => 30,                                                                       // Number of days after the notification is displayed
	 *         'id-next'                 => 'polygon_notification_two',                                               // ID of the next notification to display
	 *         'display-next-after-days' => 30,                                                                       // Number of days after the next notification is displayed
	 *         'trigger-callback'        => trigger_logic(),                                                          // Callback function returning true or false to trigger notification on demand
	 *         'slim-notification'       => true,                                                                     // Remove extra margins for slimmer notifications
	 *     );
	 *     return $config;
	 * }
	 * add_filter( 'polygon_admin_notifications', 'polygon_notifications_setup' );
	 *
	 * In order to chain notifications you must define 'id-next' and 'display-next-after-days'
	 * in the parent notification. Do not define 'display-after-days' in the child notification
	 * to avoid triggering the display countdown. The countdown in child notifications starts
	 * when the parent is dismissed.
	 *
	 * @since 1.0.0
	 */
	class Polygon_Admin_Notifications {

		/**
		 * All dynamic notifications.
		 *
		 * @since 1.0.0
		 * @var   array
		 */
		public $notices = null;





		/**
		 * Initialize the class and set its properties.
		 *
		 * Register the hooks required for our class.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// Retreive dynamic notifications.
			$this->notices = apply_filters( 'polygon_admin_notifications', null );

			// Register hooks.
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
		 * @since 1.0.0
		 */
		public function activation_setup() {
			// Variables.
			$notices = $this->notices;
			$users   = get_users();

			foreach ( $users as $user ) {
				foreach ( $notices as $notice ) {

					// Per item variables.
					if ( isset( $notice['id'] ) ) {
						$id = $notice['id'];
					} else {
						continue;
					}

					// User meta key IDs.
					$meta_key_flag      = sanitize_title_with_dashes( $notice['id'] . '_ignore_flag' );
					$meta_key_timestamp = sanitize_title_with_dashes( $notice['id'] . '_timestamp' );

					// Remove old user meta.
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
		 *     - the number of days set in 'display-after-days' has passed ( timed notification )
		 *     - the number of days set in 'display-next-after-days' in parent notification has
		 *       passed ( chained notification )
		 *
		 * When the notification message is displayed the user has the option to go to an
		 * external URL or to permanently dismiss the message.
		 *
		 * @since 1.0.0
		 */
		public function display_notifications() {
			if ( current_user_can( 'manage_options' ) ) {
				// Global variables.
				global $current_user;

				// Variables.
				$notices = $this->notices;

				if ( $notices ) {
					foreach ( $notices as $notice ) {

						// Variables.
						if ( isset( $notice['id'] ) ) {
							$id = $notice['id'];
						} else {
							continue;
						}

						if ( isset( $notice['type'] ) ) {
							if ( 'error' === $notice['type'] ) {
								$type = 'error';
							} else {
								$type = 'updated';
							}
						} else {
							$type = 'updated';
						}

						if ( isset( $notice['title'] ) ) {
							$title = $notice['title'];
						} else {
							$title = false;
						}

						if ( isset( $notice['description'] ) ) {
							$description = $notice['description'];
						} else {
							$description = false;
						}

						if ( isset( $notice['ok-button-label'] ) ) {
							$ok_button_label = $notice['ok-button-label'];
						} else {
							$ok_button_label = false;
						}

						if ( isset( $notice['no-button-label'] ) ) {
							$no_button_label = $notice['no-button-label'];
						} else {
							$no_button_label = false;
						}

						if ( isset( $notice['internal-url'] ) ) {
							$internal_url = $notice['internal-url'];
						} else {
							$internal_url = false;
						}

						if ( isset( $notice['external-url'] ) ) {
							$external_url = $notice['external-url'];
						} else {
							$external_url = false;
						}

						if ( ( isset( $notice['display-after-days'] ) ) && ( is_int( $notice['display-after-days'] ) ) ) {
							$display_after_days = $notice['display-after-days'];
						} else {
							$display_after_days = false;
						}

						if ( isset( $notice['trigger-callback'] ) ) {
							$trigger_callback = $notice['trigger-callback'];
						} else {
							$trigger_callback = false;
						}

						if ( isset( $notice['slim-notification'] ) ) {
							$slim_notification = $notice['slim-notification'];
						} else {
							$slim_notification = false;
						}

						// User meta key IDs.
						$meta_key_flag      = sanitize_title_with_dashes( $notice['id'] . '_ignore_flag' );
						$meta_key_timestamp = sanitize_title_with_dashes( $notice['id'] . '_timestamp' );

						// Manipulate variables.
						if ( $display_after_days ) {
							$display_after_days = $display_after_days * DAY_IN_SECONDS;
						}

						// Set the initial timestamp.
						if ( ! get_user_meta( $current_user->ID, $meta_key_timestamp ) ) {
							if ( $display_after_days ) {
								add_user_meta( $current_user->ID, $meta_key_timestamp, time() + $display_after_days, true );
							} else {
								add_user_meta( $current_user->ID, $meta_key_timestamp, time() + YEAR_IN_SECONDS, true );
							}
						}

						// Retreive the current timestamp.
						$display_after_days_timestamp = get_user_meta( $current_user->ID, $meta_key_timestamp, true );

						// Display notification.
						if ( ( $trigger_callback && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) ) ||
							( ( ! $trigger_callback ) && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) && ( $display_after_days_timestamp < time() ) ) ) {
								?>
									<div class="<?php echo sanitize_html_class( $type ); ?> notice polygon-notice">
										<?php if ( ! $slim_notification ) { ?>
											<p></p>
										<?php } ?>

										<?php if ( $title ) { ?>
											<p style="font-weight: 700;"><?php echo wp_kses_post( $title ); ?></p>
										<?php } ?>

										<?php if ( $description ) { ?>
											<p><?php echo wp_kses_post( $description ); ?></p>
										<?php } ?>

										<?php if ( $ok_button_label || $no_button_label ) { ?>
											<p>
										<?php } ?>

										<?php if ( $ok_button_label ) { ?>
											<?php if ( $internal_url ) { ?>
												<a href="<?php echo esc_url( admin_url( $internal_url ) ); ?>"><b><?php echo esc_html( $ok_button_label ); ?></b></a>
											<?php } ?>

											<?php if ( $external_url ) { ?>
												<a href="<?php echo esc_url( $external_url ); ?>" target="_blank"><b><?php echo esc_html( $ok_button_label ); ?></b></a>
											<?php } ?>
										<?php } ?>

										<?php if ( ( $ok_button_label && $no_button_label && ( $internal_url || $external_url ) ) ) { ?>
											|
										<?php } ?>

										<?php if ( $no_button_label ) { ?>
											<a href="?<?php echo esc_attr( $meta_key_flag ); ?>=0"><b><?php echo esc_html( $no_button_label ); ?></b></a>
										<?php } ?>

										<?php if ( $ok_button_label || $no_button_label ) { ?>
											</p>
										<?php } ?>

										<?php if ( ! $slim_notification ) { ?>
											<p></p>
										<?php } ?>
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
		 * @since 1.0.0
		 */
		public function ignore_notifications() {
			if ( current_user_can( 'manage_options' ) ) {
				// Global variables.
				global $current_user;

				// Variables.
				$notices = $this->notices;

				if ( $notices ) {
					foreach ( $notices as $notice ) {

						// Variables.
						if ( isset( $notice['id'] ) ) {
							$id = $notice['id'];
						} else {
							continue;
						}

						if ( isset( $notice['id-next'] ) ) {
							$id_next = sanitize_title_with_dashes( $notice['id-next'] );
						} else {
							$id_next = false;
						}

						if ( ( isset( $notice['display-next-after-days'] ) ) && ( is_int( $notice['display-next-after-days'] ) ) ) {
							$display_next_after_days = $notice['display-next-after-days'];
						} else {
							$display_next_after_days = false;
						}

						// User meta key IDs.
						$meta_key_flag = sanitize_title_with_dashes( $notice['id'] . '_ignore_flag' );

						if ( $id_next ) {
							$meta_key_timestamp_next = sanitize_title_with_dashes( $notice['id-next'] . '_timestamp' );
						}

						// Manipulate variables.
						if ( $display_next_after_days ) {
							$display_next_after_days = $display_next_after_days * DAY_IN_SECONDS;
						}

						// Update user meta when the NO button is pressed.
						if ( isset( $_GET[ $meta_key_flag ] ) && ( 0 === $_GET[ $meta_key_flag ] ) ) {
							if ( ( $id_next ) && ( $display_next_after_days ) && ( ! get_user_meta( $current_user->ID, $meta_key_flag ) ) ) {
								update_user_meta( $current_user->ID, $meta_key_timestamp_next, time() + $display_next_after_days );
							}
							add_user_meta( $current_user->ID, $meta_key_flag, 'true', true );
						}
					}
				}
			}
		}
	}

}





// Let's do this.
$polygon_admin_notifications = new Polygon_Admin_Notifications;
