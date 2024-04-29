<?php
/*
 * Plugin Name: Tanismo Helcim Payment Gateway
 * Plugin URI: https://github.com/lastralab/tanismo-helcim-plugin/
 * Description: Redirect customers to Helcim Payment Terminal
 * Version: 1.0.0
 * Author: Tanismo
 * Author URI: https://tanismo.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tanismo-helcim
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 5.9.3
 * WC requires at least: 5.0
 * WC tested up to: 6.1.0
 */

if (!defined('WPINC')) {
    die;
}

define('TANISMO_HELCIM_VERSION', '1.0.0');
define('TANISMO_HELCIM_PLUGIN_DIR', plugin_dir_path(__FILE__));

require_once TANISMO_HELCIM_PLUGIN_DIR . 'includes/class-helcim-controller.php';
require_once TANISMO_HELCIM_PLUGIN_DIR . 'includes/class-helcim-cancellation.php';
require_once TANISMO_HELCIM_PLUGIN_DIR . 'admin/class-helcim-gateway-settings.php';

// WooCommerce dependency
if (!function_exists('is_plugin_active')) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if (!is_plugin_active('woocommerce/woocommerce.php')) {
    deactivate_plugins(plugin_basename(__FILE__));
    add_action('admin_notices', 'tanismo_plugin_admin_notice');
}

/**
 * @return void
 */
function tanismo_plugin_admin_notice()
{
    echo '<div class="error"><p>Tanismo Helcim Plugin requires WooCommerce to be installed and active. Please activate WooCommerce or deactivate Tanismo Helcim Plugin.</p></div>';
}

/**
 * Landing Pages for Helcim Gateway
 */
$helcim_controller = new Helcim_Controller();
add_action('rest_api_init', array($helcim_controller, 'register_routes'));

$helcim_cancellation = new Helcim_Cancellation();
add_action('rest_api_init', array($helcim_cancellation, 'register_routes'));


// Register the payment gateway class.
add_filter('woocommerce_payment_gateways', 'helcim_add_gateway_class');
function helcim_add_gateway_class($gateways)
{
    $gateways[] = 'WC_Helcim_Gateway';
    return $gateways;
}

/**
 * Customizations for Woocommerce checkout
 */

add_filter('woocommerce_locate_template', 'override_woocommerce_template', 10, 3);
add_filter('woocommerce_available_payment_gateways', 'helcim_rename_place_order_button_payment_gateway');

/**
 * Checkout submit button text - Custom Helcim Plugin
 * @param $gateways
 * @return mixed
 */
function helcim_rename_place_order_button_payment_gateway($gateways)
{
    if (!empty($gateways['tanismo-helcim'])) {
        $gateways['tanismo-helcim']->order_button_text = 'Pay on Terminal';
    }
    return $gateways;
}

function override_woocommerce_template($template, $template_name, $template_path): string
{
    if ($template_path == 'woocommerce/' && $template_name === 'checkout/review-order.php') {

        $template_directory = plugin_dir_path(__FILE__) . $template_path;
        $path = $template_directory . $template_name;

        return file_exists($path) ? $path : $template;
    }
    return $template;
}


/**
 * Admin Helcim Payment Settings
 */

add_action('plugins_loaded', 'helcim_init_gateway_class');

/**
 * Woocommerce Payment Settings
 * @return void
 */
function helcim_init_gateway_class()
{
    class WC_Helcim_Gateway extends WC_Payment_Gateway
    {
        /**
         * Class constructor
         */
        public function __construct()
        {
            $this->id = 'tanismo-helcim';
            $this->icon = '';
            $this->has_fields = false;
            $this->method_title = 'Custom Helcim Gateway';
            $this->method_description = 'Customer will be redirected to the Helcim Payment Page';
            $this->supports = array(
                'products'
            );
            include_once(plugin_dir_path(__FILE__) . 'admin/class-helcim-gateway-settings.php');
            WC_Helcim_Gateway_Settings::init_form_fields($this);
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            $this->instructions = $this->get_option('instructions');
            $this->enabled = $this->get_option('enabled');
            $this->testmode = 'yes' === $this->get_option('testmode');
            $this->terminalUrl = $this->get_option('terminal_url');
            $this->token = $this->testmode ? $this->get_option('test_token') : $this->get_option('token');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_option('helcim_terminal_url', str_replace('/hosted/', '', $this->terminalUrl));
        }

        /**
         * @param $order_id
         * @return string[]
         */
        public function process_payment($order_id): array
        {
            $order = wc_get_order($order_id);

            $helcimUrl = $this->terminalUrl . '?token=' . $this->token
                . '&amount=' . $order->get_total()
                . '&orderId=' . $order_id
                . '&billingemail=' . $order->get_billing_email();

            return [
                'result' => 'success',
                'redirect' => $helcimUrl
            ];
        }

        /**
         * @return void
         */
        public function payment_scripts()
        {
            // No payment scripts needed
        }
    }
}
