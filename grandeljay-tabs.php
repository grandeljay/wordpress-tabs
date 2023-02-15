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

function shortcode_tab( $atts = array(), $content = null ) {
	ob_start();

	$attributes = '';

	if ( is_string( $content ) ) {
		$content = trim( $content, '<p></p>' );
		$content = trim( $content );
	}

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

	if ( isset( $atts['summary'] ) ) {
		$atts['summary'] = trim( $atts['summary'], '<p></p>' );
		$atts['summary'] = trim( $atts['summary'] );
	}

	$attributes = trim( $attributes );
	?>
	<details <?php echo ( $attributes ); ?>>
		<summary>
			<?php echo $atts['summary'] ?? ''; ?>
		</summary>
		<div>
			<?php echo $content ?? ''; ?>
		</div>
	</details>
	<?php
	$tab = ob_get_clean();

	return $tab;
}

\add_shortcode( 'grandeljay_tab', __NAMESPACE__ . '\shortcode_tab' );

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
