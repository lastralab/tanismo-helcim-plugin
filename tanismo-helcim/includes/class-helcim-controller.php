<?php
/**
 * Created by PhpStorm
 * User: tanismo
 * Project: Custom Helcim Plugin
 * Date: 04/19/2024
 */

/**
 * Success payment landing page
 */
class Helcim_Controller extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'helcim/v1';
        $this->rest_base = 'approved';
        $this->terminalURL = explode('//', get_option('helcim_terminal_url'))[1];
    }

    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            'methods' => 'GET, POST',
            'callback' => array($this, 'get_controller_approved'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * Logic to validate payment and process order
     * @param $request
     * @return WP_REST_Response
     */
    public function get_controller_approved($request): WP_REST_Response
    {
        $response = new WP_REST_Response();
        $response->set_status(307);
        try {
            sleep(1);
            $customer_id = $this->get_customer_id_from_session();

            if ($customer_id == 0) {
                $response->header('Location', home_url('/my-account'));
            }
            $order = wc_get_customer_last_order($customer_id);
            if ($order) {
                session_reset();
                $order = wc_get_customer_last_order($customer_id);
                if ($order->get_status() === 'pending') {
                    $origin = $request->get_header('referer');
                    if (strpos($origin, $this->terminalURL) !== false) { // Test locally using true
                        $order->update_status('processing');
                        $order->add_order_note('Helcim landing page accessed correctly from: ' . $this->terminalURL);
                        if (class_exists('WooCommerce') && !is_null(WC()->cart)) {
                            if (!WC()->cart->is_empty()) {
                                WC()->cart->empty_cart();
                            }
                        }
                        $response->header('Location', $order->get_checkout_order_received_url());
                    } else {
                        $message = 'The order hasn\'t received a payment on Helcim. Suspicious origin: ' . (empty($origin) ? 'Empty' : $origin);
                        $order->add_order_note($message);
                        $response->header('Location', $order->get_view_order_url());
                    }
                } else {
                    $order->add_order_note('The order was not pending when trying to get controller data');
                    $response->header('Location', home_url());
                }
                $order->save();
            } else {
                $response->header('Location', home_url('/my-account') .wc_get_endpoint_url('orders'));
            }
            return $response;
        } catch (Exception $e) {
            wc_get_logger()->error('[ERROR] Helcim landing page error: ' . $e->getMessage());
        }
        $response->header('Location', home_url('/checkout'));
        return $response;
    }

    /**
     * Get customer_id from logged_in cookie
     * @return string
     */
    private function get_customer_id_from_session(): string
    {
        $id = '0';
        foreach ($_COOKIE as $name => $value) {
            if (strpos($name, 'wordpress_logged_in_') !== false) {
                $matches = explode('%', $value);
                $match = $matches[0];
                $username = explode('|', $match)[0];
                $user = get_user_by('login', $username);
                $id = wp_authenticate_spam_check($user) ? $user->ID : 0;
            }
        }

        return (int) $id;
    }
}
