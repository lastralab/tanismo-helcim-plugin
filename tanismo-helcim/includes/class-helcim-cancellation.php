<?php
/**
 * Created by PhpStorm
 * User: tanismo
 * Project: Custom Helcim Plugin
 * Date: 04/25/2024
 */

class Helcim_Cancellation extends WP_REST_Controller
{
    public function __construct()
    {
        $this->namespace = 'helcim/v1';
        $this->rest_base = 'cancelled';
    }
    public function register_routes()
    {
        register_rest_route($this->namespace, '/' . $this->rest_base, array(
            'methods' => 'GET, POST',
            'callback' => array($this, 'helcim_cancellation'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function helcim_cancellation(WP_REST_Request $request): WP_REST_Response
    {
        $response = new WP_REST_Response();
        $response->set_status(307);
        $response->header('Location', home_url('/checkout'));
        $user_id = $this->get_customer_id_from_session();
        if ($user_id == 0) {
            $response->header('Location', home_url('/my-account'));
        }
        $order = $user_id != 0 ? wc_get_customer_last_order($user_id) : false;
        if ($order && ($order->get_status() == 'processing' || $order->get_status() == 'pending')) {
            $order->set_status('cancelled');
            $order->save();
            $response->header('Location', $order->get_view_order_url());
        } else {
            $response->header('Location', home_url('/my-account') .wc_get_endpoint_url('orders'));
        }
        return $response;
    }

    /**
     * Get customer_id from logged_in cookie
     * @return string
     */
    private function get_customer_id_from_session(): string
    {
        $id = 0;
        foreach ($_COOKIE as $name => $value) {
            $name = sanitize_text_field($name);
            if (strpos($name, 'wordpress_logged_in_') !== false) {
                $value = sanitize_text_field($value);
                $matches = explode('%', $value);
                $match = isset($matches[0]) ? sanitize_text_field($matches[0]) : '';
                $username = sanitize_user(explode('|', $match)[0]);
                $user = get_user_by('login', $username);

                $matches = explode('%', $value);
                $match = $matches[0];
                $username = explode('|', $match)[0];
                $user = get_user_by('login', $username);
                $id = ($user && wp_authenticate_spam_check($user)) ? absint($user->ID) : 0;
            }
        }

        return (string) $id;
    }
}
