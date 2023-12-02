<?php
// Include WordPress
if (!defined('ABSPATH')) {
  define('WP_USE_THEMES', false);
  require_once('../../../wp-load.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['donate-now'])) {
  parse_str($_POST['form_data'], $form_data);

  $first_name = sanitize_text_field($form_data['first-name']);
  $last_name = sanitize_text_field($form_data['last-name']);
  $email = filter_var($form_data['email'], FILTER_SANITIZE_EMAIL);
  $phone = sanitize_text_field($form_data['phone']);
  $donation_amount = intval($form_data['donation-amount']);
  $payment_method = sanitize_text_field($form_data['payment-method']);

  $post_data = array(
    'post_title' => "{$first_name} {$last_name} $donation_amount",
    'post_content'  => "Payment Method: {$payment_method}\nEmail: {$email}\nPhone: {$phone}",
    'post_type'     => 'donation',
    'post_status'   => 'publish',
  );

  $post_id = wp_insert_post($post_data);

  if (is_wp_error($post_id)) {
    wp_send_json(['success' => false, 'error' => $post_id->get_error_message()]);
  } else {
    wp_send_json(['success' => true]);
  }
  wp_die();
} else {
  wp_send_json(['success' => false, 'error' => 'Invalid request']);
  wp_die();
}
