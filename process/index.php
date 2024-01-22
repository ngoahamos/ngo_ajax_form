<?php

$path = preg_replace('/wp-content.*$/','',__DIR__);
require_once($path . 'wp-load.php');
$response = [];
$response["message"] = "Invalid Data Provided";
$response["status"] = false;
$response["cards"] = [];




function get_customer_id($email) {

    $users = get_users(array( "meta_key"=>"billing_email", "meta_value"=> $email));
 
    if(!empty($users)) {
        return $users[0]->ID;
    }
 
    $user = get_user_by('email', $email);

   

    if ($user) {
        return $user->ID;
    }

    return "";
}

function wc_get_customer_orders_($current_user_id) {
 

   return  get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $current_user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => array_keys( wc_get_order_statuses() ),
    ) );
}

function get_customer_cards($customer_id) {
   
  
    $_cards = [];
    $customer_orders = wc_get_customer_orders_($customer_id);
  
 

  
    

    // Loop through the orders
    $key = 0;
    foreach ($customer_orders as $partial) {
        global $woocommerce;
        global $post;

        if ($partial->post_status == 'wc-completed') {
           
            $order = new WC_Order($partial->ID);
    
            // Get the order ID
            $order_id = $order->get_id();
        
            // Get order details
            $order_date = $order->get_date_created()->format('Y-m-d H:i:s');
  

            // Get order items
            $order_items = $order->get_items();

            $composite = '';
    
            $serial = "";
       
            foreach ($order_items as $item_id=> $item) {

                $product_id = $item->get_product_id();

                $serial = ngo_ajax_form_display_order_item_meta($item_id,$item,$product_id);
                
            }
            if ($serial) {
                $_cards[] = ['order_id' => $order_id, 'date' => $order_date, 'serial' => $serial];
            }
            


            $key++;

        } else {
            $key++;
            continue;
        }
    
    }

    return $_cards;
}

function ngo_ajax_form_get_customer_cards_by_billing_email( $customer_email ) {
    $_cards = [];
    $customer_orders = wc_get_orders(array('billing_email' => $customer_email));
    

    // Loop through the orders
    $key = 0;
    foreach ($customer_orders as $partial) {
   

        if ($partial->post_status == 'wc-completed') {
           
            $order = new WC_Order($partial->ID);
    
            // Get the order ID
            $order_id = $order->get_id();
        
            // Get order details
            $order_date = $order->get_date_created()->format('Y-m-d H:i:s');
  

            // Get order items
            $order_items = $order->get_items();

            $composite = '';
    
            $serial = "";
       
            foreach ($order_items as $item_id=> $item) {

                $product_id = $item->get_product_id();

                $serial = ngo_ajax_form_display_order_item_meta($item_id,$item,$product_id);
                
            }
            if ($serial) {
                $_cards[] = ['order_id' => $order_id, 'date' => $order_date, 'serial' => $serial];
            }
            


            $key++;

        } else {
            $key++;
            continue;
        }
    
    }

    return $_cards;
}
function ngo_ajax_form_display_order_item_meta( $item_id, $item, $product_id ) {
    try {
        $order_id = wc_get_order_id_by_order_item_id( $item_id );

        $keys = wcsn_get_keys(
            array(
                'order_id'   => $order_id,
                'product_id' => $product_id,
                'limit'      => - 1,
            )
        );

  
    
        if ( empty( $keys ) ) {
            return;
        }
    
        $data = [];
        $serial_numers = "";
        foreach ( $keys as $index => $key ) {
            $data = array(
                'key'              => array(
                    'label' => __( 'Key', 'wc-serial-numbers' ),
                    'value' => '<code>' . $key->get_key() . '</code>',
                ),
                'expire_date'      => array(
                    'label' => __( 'Expire date', 'wc-serial-numbers' ),
                    'value' => $key->get_expire_date() ? $key->get_expire_date() : __( 'Lifetime', 'wc-serial-numbers' ),
                ),
                'activation_limit' => array(
                    'label' => __( 'Activation limit', 'wc-serial-numbers' ),
                    'value' => $key->get_activation_limit() ? $key->get_activation_limit() : __( 'Unlimited', 'wc-serial-numbers' ),
                ),
                'status'           => array(
                    'label' => __( 'Status', 'wc-serial-numbers' ),
                    'value' => $key->get_status_label(),
                ),
            );
            $serial_numers .= $data['key']['value'];
    
        }
    } catch (\Throwable $th) {
        //throw $th;
        return "";
    }

    return $serial_numers;
}


if (isset($_POST['submit_id']) && $_POST['submit_id'] == 1) {
    $email = sanitize_email($_POST['email']);

    $customer_id = get_customer_id($email);

    $cards = [];

   
    if ($customer_id != "") {
  
        $cards = get_customer_cards($customer_id);
    } else {
        $cards = ngo_ajax_form_get_customer_cards_by_billing_email($email);
    }
    
    if (count($cards) > 0) {
        $response['message'] = 'Card Information Retrieved Successfully';
        $response['cards'] = $cards;
    } else {
        $response['message'] = 'No Data Found';
    }
    
    $response['status'] = true;

}


echo json_encode($response);



?>