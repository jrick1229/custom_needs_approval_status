<?

/**
 * Plugin Name: WooCommerce Subscriptions - Custom Needs Approval Status
 * Plugin URI:  https://github.com/jrick1229/custom_needs_approval_status
 * Description: Create "Needs Approval" status and apply to orders that contain the specified product IDs.
 * Author:      Prospress Inc.
 * Author URI:  http://prospress.com/
 * Version:     1.1.0
 * License:     GPLv3
 *
 * GitHub Plugin URI: jrick1229/custom_needs_approval_status
 * GitHub Branch: master
 *
 * Copyright 2018 Prospress, Inc.  (email : freedoms@prospress.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package WooCommerce Subscriptions
 * @author  Prospress Inc.
 * @since   1.0.0
 */
 
 

/*
 *
 * Register "Needs Approval" status
 *
 */
 
add_filter( 'woocommerce_register_shop_order_post_statuses', 'register_needs_approval_status' );
 
function register_needs_approval_status( $order_statuses ){
     
    // Status must start with "wc-"
    $order_statuses['wc-needs-approval'] = array(                                            
    'label'                     => _x( 'Needs Approval', 'Order status', 'woocommerce' ),
    'public'                    => false,                                            
    'exclude_from_search'       => false,                                            
    'show_in_admin_all_list'    => true,                                         
    'show_in_admin_status_list' => true,                                         
    'label_count'               => _n_noop( 'Needs Approval <span class="count">(%s)</span>', 'Needs Approval <span class="count">(%s)</span>', 'woocommerce' ),                                       
    );      
    return $order_statuses;
}
 


/*
 *
 * Display "Needs Approval" on the admin single order page
 * &&
 * Display "Needs Approval" in the bulks actions menu on the admin orders page
 *
 */
 
add_filter( 'wc_order_statuses', 'show_needs_approval_status' );
 
function show_needs_approval_status( $order_statuses ) {    
    $order_statuses['wc-needs-approval'] = _x( 'Needs Approval', 'Order status', 'woocommerce' );       
    return $order_statuses;
}
 
add_filter( 'bulk_actions-edit-shop_order', 'show_needs_approval_status_bulk' );
 
function show_needs_approval_status_bulk( $bulk_actions ) {
    $bulk_actions['mark_needs-approval'] = 'Change status to Needs Approval';
    return $bulk_actions;
}
 
 
 


/*
 *
 * Set "Needs Approval" based on product_id
 * Change $product_id variable depending on product
 *
 */
 
add_action( 'woocommerce_thankyou', 'change_to_needs_approval' );
 
function change_to_needs_approval( $order_id ){
    
    if( ! $order_id ) return;
    $order = wc_get_order( $order_id );
    
    $items = $order->get_items();
    
    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
    }
    
    // additional $product_id values should be separate by ||
    if ( $product_id == 696 || $product_id == 678 ) {
        $order->update_status( 'needs-approval' );
    }
   
}