<?php
/**
 * Tabs
 *
 * @package           GrandelJayTabs
 * @author            Jay Trees <github.jay@grandel.anonaddy.me>
 *
 * @wordpress-plugin
 * Plugin Name:       Tabs
 * Description:       Creates a shortcode which creates expandable tabs.
 * Version:           0.1.0
 * Requires at least: 6.1
 * Requires PHP:      8.0
 * Author:            Jay Trees
 * Author URI:        https://github.com/grandeljay/
 * Text Domain:       grandeljay-tabs
 */

namespace Grandeljay\Tabs;

/**
 * Tab
 *
 * @param array  $atts
 * @param [type] $content
 *
 * @return string
 */
function shortcode_tab( $atts = array(), $content = null ): string {
	$content = get_clean( $content );

	/**
	 * Attributes
	 */
	$attributes = '';

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $key => $value ) {
			if ( ! in_array( $key, array( 'id', 'class' ), true ) ) {
				continue;
			}

			$attributes .= sprintf(
				'%s="%s" ',
				$key,
				$value
			);
		}
	}

	$attributes = trim( $attributes );

	/**
	 * Tab
	 */
	ob_start();
	?>
	<details <?php echo ( $attributes ); ?>>
		<?php echo \do_shortcode( $content ); ?>
	</details>
	<?php

	$tab = ob_get_clean();

	return $tab;
}

\add_shortcode( 'grandeljay_tab', __NAMESPACE__ . '\shortcode_tab' );

/**
 * Tab: Summary
 *
 * @param array  $atts
 * @param [type] $content
 *
 * @return string
 */
function shortcode_tab_summary( $atts = array(), $content = null ): string {
	$content = get_clean( $content );

	ob_start();
	?>
	<summary>
		<?php echo \do_shortcode( '[wpml-string context="grandeljay-tabs" name="' . hash( 'sha1', $content ) . '"]' . $content . '[/wpml-string]' ); ?>
	</summary>
	<?php

	$summary = ob_get_clean();

	return $summary;
}

\add_shortcode( 'grandeljay_summary', __NAMESPACE__ . '\shortcode_tab_summary' );

/**
 * Tab: Content
 *
 * @param array  $atts
 * @param [type] $content
 *
 * @return string
 */
function shortcode_tab_content( $atts = array(), $content = null ): string {
	$content = get_clean( $content );

	ob_start();
	?>
	<div>
		<?php echo \do_shortcode( '[wpml-string context="grandeljay-tabs" name="' . hash( 'sha1', $content ) . '"]' . $content . '[/wpml-string]' ); ?>
	</div>
	<?php

	$html = ob_get_clean();

	return $html;
}

\add_shortcode( 'grandeljay_content', __NAMESPACE__ . '\shortcode_tab_content' );

/**
 * Enqueue scripts and styles
 *
 * @return void
 */
function enqueue_scripts() {
	$css_tabs = 'src/assets/css/tabs.css';

	wp_enqueue_style(
		'grandeljay-css-tabs',
		plugin_dir_url( __FILE__ ) . $css_tabs,
		array(),
		hash_file( 'crc32c', plugin_dir_path( __FILE__ ) . '/' . $css_tabs )
	);
}

\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );

/**
 * Get clean
 *
 * @param null|string $content
 *
 * @return string
 */
function get_clean( null|string $content ): string {
	if ( null === $content ) {
		$content = '';
	}

	$content = preg_replace( '/\s+/', ' ', $content );
	$content = trim( $content );

	if ( '<p>' === substr( $content, 0, 3 ) || '</p>' === substr( $content, 0, 4 ) ) {
		$content = trim( $content, '</p>' );
	}

	$content = str_replace( '<br />', '', $content );

	return $content;
}
