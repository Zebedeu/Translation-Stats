<?php
/**
 * Class file for registering Translation Stats Debug.
 *
 * @package Translation Stats
 *
 * @since 0.8.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'TStats_Debug' ) ) {

	/**
	 * Class TStats_Debug.
	 */
	class TStats_Debug {

		/**
		 * Constructor.
		 */
		public function __construct() {

			// Instantiate Translation Stats Transients.
			$this->tstats_transients = new TStats_Transients();

		}


		/**
		 * Display debug formated message with plugin options.
		 *
		 * Usage of notice types:
		 * notice-error – error message displayed with a red border.
		 * notice-warning – warning message displayed with a yellow border.
		 * notice-success – success message displayed with a green border.
		 * notice-info - info message displayed with a blue border.
		 *
		 * @since 0.8.0
		 *
		 * @param string $type    WordPress core notice types ( 'error', 'warning', 'success' and 'info' ).
		 * @param string $inline  True show message inline, false show message on top.
		 * @param string $debug   True or false value to activate debug message.
		 */
		public function tstats_debug( $type, $inline, $debug ) {
			if ( TSTATS_DEBUG || $debug ) {
				$inline = $inline ? 'inline' : '';
				?>
				<br/>
				<div class="tstats-debug-block notice notice-alt <?php echo esc_html( $inline ); ?> notice-<?php echo esc_html( $type ); ?>">
					<?php
					// Show server info.
					$this->tstats_debug_server();
					// Show the site settings debug info.
					$this->tstats_debug_settings();
					// Show the site transients debug info.
					$this->tstats_debug_transients();
					?>
					<br/>
				</div>
				<?php
			}
		}


		/**
		 * Show the site settings debug info.
		 *
		 * @since 0.8.0
		 */
		public function tstats_debug_server() {
			?>
			<h3>
				<?php esc_html_e( 'Server', 'translation-stats' ); ?>
			</h3>



			<p>
				<?php
				printf(
					/* translators: %s Version Number. */
					esc_html__( 'PHP Version: %s', 'translation-stats' ),
					'<code>' . esc_html( phpversion() ) . '</code>'
				);
				?>
			</p>
			<p>
				<?php esc_html_e( 'PHP Functions Check:', 'translation-stats' ); ?>
			</p>
			<code class="tstats-code-block">
				<?php

				// Test important functions.
				$test_functions = array(
					'array_column',
					'array_map',
				);
				if ( ! empty( $test_functions ) ) {
					foreach ( $test_functions as $test_function ) {
						$dashicon = function_exists( $test_function ) ? 'dashicons-yes' : 'dashicons-no';
						?>
						<span class="dashicons <?php echo esc_html( $dashicon ); ?>"></span><?php echo esc_html( $test_function . '()' ); ?><br/>
						<?php
					}
				} else {
					esc_html_e( 'No functions to test.', 'translation-stats' );
				}
				?>
			</code>
			<?php
		}


		/**
		 * Show the site settings debug info.
		 *
		 * @since 0.8.0
		 */
		public function tstats_debug_settings() {
			?>
			<h3>
				<?php esc_html_e( 'Settings', 'translation-stats' ); ?>
			</h3>
			<p>
				<?php
				printf(
					/* translators: %s Page Name. */
					esc_html__( 'Settings Page: %s', 'translation-stats' ),
					'<code>' . esc_html( TSTATS_SETTINGS_PAGE ) . '</code>'
				);
				?>
			</p>
			<p>
				<?php
				printf(
					/* translators: %s Option Name. */
					esc_html__( 'WordPress Option: %s', 'translation-stats' ),
					'<code>' . esc_html( TSTATS_WP_OPTION ) . '</code>'
				);
				?>
			</p>
			<p>
				<?php esc_html_e( 'Settings List:', 'translation-stats' ); ?>
			</p>
			<div>
				<?php
				$tstats_options = get_option( TSTATS_WP_OPTION );
				if ( $tstats_options ) {
					?>
					<pre><code class="tstats-code-block"><?php echo esc_html( print_r( $tstats_options, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r ?></code></pre>
					<?php
				} else {
					?>
					<code><?php esc_html_e( 'No settings found.', 'translation-stats' ); ?></code>
					<?php
				}
				?>
			</div>
			<?php
		}


		/**
		 * Show the site transients debug info.
		 *
		 * @since 0.8.0
		 */
		public function tstats_debug_transients() {
			?>
			<h3>
				<?php esc_html_e( 'Transients', 'translation-stats' ); ?>
			</h3>
			<p>
				<?php
				printf(
					/* translators: %s Prefix Name. */
					esc_html__( 'Transients Prefix: %s', 'translation-stats' ),
					'<code>' . esc_html( TSTATS_TRANSIENTS_PREFIX ) . '</code>'
				);
				?>
			</p>
			<p>
				<?php esc_html_e( 'Transients List:', 'translation-stats' ); ?>
			</p>
			<div>
				<code class="tstats-code-block">
					<?php
					$tstats_transients = $this->tstats_transients->tstats_get_transients( TSTATS_TRANSIENTS_PREFIX );
					if ( ! empty( $tstats_transients ) ) {
						foreach ( $tstats_transients as $tstats_transient ) {
							echo esc_html( substr( $tstats_transient, strlen( '_transient_' ) ) );
							?>
							<br/>
							<?php
						}
					} else {
						esc_html_e( 'No transients found.', 'translation-stats' );
					}
					?>
				</code>
			</div>
			<?php
		}


		/**
		 * Display debug formated message with plugin options.
		 *
		 * Usage of notice types:
		 * notice-error – error message displayed with a red border.
		 * notice-warning – warning message displayed with a yellow border.
		 * notice-success – success message displayed with a green border.
		 * notice-info - info message displayed with a blue border.
		 *
		 * @since 0.8.0
		 *
		 * @param string $field_id  Setting ID.
		 * @param string $value     Setting Value.
		 * @param string $default   Setting Default.
		 * @param string $type      WordPress core notice types ( 'error', 'warning', 'success' and 'info' ).
		 * @param string $debug     True or false value to activate debug message.
		 */
		public function tstats_debug_setting_field( $field_id, $value, $default, $type, $debug ) {
			if ( TSTATS_DEBUG || $debug ) {
				?>
				<div class="tstats-debug-block notice notice-alt inline notice-<?php echo esc_html( $type ); ?>">
					<p>
						<?php
						printf(
							/* translators: %s Setting ID. */
							esc_html__( 'ID: %s', 'translation-stats' ),
							'<code>' . esc_html( $field_id ) . '</code>'
						);
						?>
					</p>
					<p>
						<?php
						printf(
							/* translators: %s Setting Value. */
							esc_html__( 'Value: %s', 'translation-stats' ),
							'<code>' . esc_html( $value ) . '</code>'
						);
						?>
					</p>
					<p>
						<?php
						printf(
							/* translators: %s Setting Default. */
							esc_html__( 'Default: %s', 'translation-stats' ),
							'<code>' . esc_html( $default ) . '</code>'
						);
						?>
					</p>
				</div>
				<?php
			}
		}

	}

}
