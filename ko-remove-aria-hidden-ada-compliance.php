<?php
/**
 * Plugin Name: KO - Remove aria-hidden (for ADA compliance)
 * Plugin URI: https://kevinoneill.us/
 * Description: Removes inappropriate aria-hidden attributes from visible WooCommerce price elements and common Divi image wrappers to improve accessibility in Divi/WooCommerce builds.
 * Version: 1.0.0
 * Author: Kevin O'Neill
 * Author URI: https://kevinoneill.us/
 * License: GPL2+
 * Text Domain: ko-remove-aria-hidden
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output a small front-end script late in the footer so it can clean up
 * markup added by theme, plugins, or front-end renderers.
 */
function ko_remove_aria_hidden_footer_script() {
	if ( is_admin() ) {
		return;
	}
	?>
	<script>
	(function () {
		function koRemoveBadAriaHidden() {
			var selectors = [
				'.woocommerce-Price-amount[aria-hidden="true"]',
				'del[aria-hidden="true"]',
				'ins[aria-hidden="true"]',
				'.et_pb_image_wrap[aria-hidden="true"]',
				'.et_pb_module.et_pb_image[aria-hidden="true"]'
			];

			document.querySelectorAll(selectors.join(',')).forEach(function (el) {
				el.removeAttribute('aria-hidden');
			});
		}

		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', koRemoveBadAriaHidden);
		} else {
			koRemoveBadAriaHidden();
		}

		// Re-run in case WooCommerce fragments or front-end scripts replace markup.
		window.addEventListener('load', koRemoveBadAriaHidden);

		if ('MutationObserver' in window) {
			var observer = new MutationObserver(function () {
				koRemoveBadAriaHidden();
			});

			observer.observe(document.body, {
				childList: true,
				subtree: true
			});
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'ko_remove_aria_hidden_footer_script', 100 );
