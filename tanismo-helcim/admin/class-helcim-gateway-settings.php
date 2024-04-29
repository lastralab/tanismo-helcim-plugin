<?php
/**
 * Created by PhpStorm
 * User: tanismo
 * Project: Custom Helcim Plugin
 * Date: 04/29/2024
 */

class WC_Helcim_Gateway_Settings
{
    /**
     * Plugin options
     */
    public static function init_form_fields(WC_Helcim_Gateway $gateway): void
    {
        $gateway->form_fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable Custom Helcim Gateway',
                'type' => 'checkbox',
                'description' => '',
                'default' => 'yes'
            ),
            'title' => array(
                'title' => __('Title', 'tanismo-helcim'),
                'type' => 'text',
                'description' => '',
                'default' => __('You will be redirected to our secure Helcim terminal to pay with a Credit Card. A 3% convenience fee will be added.'),
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => __('Description', 'tanismo-helcim'),
                'type' => 'textarea',
                'description' => __('Payment method description that the customer will see on your website.', 'tanismo-helcim'),
                'default' => __('You will be redirected to our secure Helcim terminal to pay with a Credit Card. A 3% convenience fee will be added.', 'tanismo-helcim'),
            ),
            'instructions' => array(
                'title' => __('Instructions', 'tanismo-helcim'),
                'type' => 'textarea',
                'description' => __('Instructions that will be added to the thank you page.', 'tanismo-helcim'),
                'default' => __('You will receive an email confirmation.', 'tanismo-helcim'),
                'desc_tip' => true,
            ),
            'terminal_url' => array(
                'title' => __('Terminal URL', 'tanismo-helcim'),
                'type' => 'text',
                'description' => 'Provided when creating a payment page on Helcim',
                'default' => '',
                'desc_tip' => true,
            ),
            'testmode' => array(
                'title' => 'Test mode',
                'label' => 'Enable Test Mode',
                'type' => 'checkbox',
                'description' => 'Place the payment gateway in test mode.',
                'default' => 'yes',
                'desc_tip' => true,
            ),
            'test_token' => array(
                'title' => 'Helcim Payment Page - Test Token',
                'type' => 'text'
            ),
            'token' => array(
                'title' => 'Helcim Payment Page - Token',
                'type' => 'text'
            )
        );
    }
}
