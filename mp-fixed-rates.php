<?php
/*
MarketPress Fixed Rates Shipping Plugin
Author: Jon Wilson (Inspired Agency)
Version: 1.0
*/

class MP_Fixed_Rates_Delivery extends MP_Shipping_API {

	public $build		=	2;
	
	//private shipping method name. Lowercase alpha (a-z) and dashes (-) only please!
	var $plugin_name	=	'fixed-rates';
	
	//public name of your method, for lists and such.
	var $public_name	=	'';
	
	//set to true if you need to use the shipping_metabox() method to add per-product shipping options
	var $use_metabox	=	false;
	
	//set to true if you want to add per-product extra shipping cost field
	var $use_extra		=	false;
	
	//set to true if you want to add per-product weight shipping field
	var $use_weight		=	false;
	
	/*
	 * Runs when your class is instantiated. Use to setup your plugin instead of __construct()
	 */
	
	function on_creation() {
		
		//declare here for translation
		$this->public_name = __( 'Fixed Rates', 'mp' );
		
	}
	
	/*
	 * Echo anything you want to add to the top of the shipping screen
	 */
	
	function before_shipping_form( $content ) {
		
		return $content;
		
	}
	
	/*
	 * Echo anything you want to add to the bottom of the shipping screen
	 */
	
	function after_shipping_form( $content ) {
		
		return $content;
		
	}
	
	/**
	 * Initialize the settings metabox
	 *
	 * @since 3.0
	 * @access public
	 */
	 
	public function init_settings_metabox() {
		
		$metabox = new WPMUDEV_Metabox( array(
		
			'id' => $this->generate_metabox_id(),
			
			'page_slugs' => array(
			
				'store-settings-shipping',
				
				'store-settings_page_store-settings-shipping',
				
				'store-setup-wizard'
				
			),
			
			'title' => sprintf( __( '%s Settings', 'mp' ), $this->public_name ),
						
			'option_name' => 'mp_settings',
			
			'conditional' => array(

				'operator' => 'AND',

				'action'   => 'show',

				array(

					'name'  => 'shipping[method]',

					'value' => 'calculated',

				),

				array(

					'name'  => 'shipping[calc_methods][fixed-rates]',

					'value' => 'fixed-rates',

				),

			),
			
		));
		
		$layers = $metabox->add_field( 'repeater', array(
		
			'name' => $this->get_field_name( 'rates' ),
			
			'sortable' => true,
			
		) );
		
		if ( $layers instanceof WPMUDEV_Field ) {
			
			$layers->add_sub_field( 'text', array(
			
				'name' => 'ship_type',
				
				'label' => array( 'text' => __( 'Shipping Type', 'mp' ) ),
				
				'desc' => __( 'This is displayed to the customer as the shipping option.', 'mp' ),

				'validation' => array(

					'required' => true,

				),
				
			) );
			
			// ADD LAYERS

			$layers->add_sub_field( 'text', array(

				'name' => 'ship_price',

				'label' => array( 'text' => __( 'Shipping Price', 'mp' ) ),

				'validation' => array(

					'required' => true,

					'number' => true,

					'min' => 0,

				),

			) );
			
		}

	}
	
	/*
	 * Add additional shipping fields
	 */
	
	public function extra_shipping_field( $fields, $type ) {
		
		return $fields;
		
	}
	
	/**
	 * Filters posted data from your form. Do anything you need to the $settings['shipping']['plugin_name'] array. Don't forget to return!
	 */

	function process_shipping_settings( $settings ) {

		return $settings;

	}
	
	/**
	 * Echo any per-product shipping fields you need to add to the product edit screen shipping metabox
	 */

	function shipping_metabox( $shipping_meta, $settings ) {

		

	}
	
	/**
	 * Save any per-product shipping fields from the shipping metabox using update_post_meta
	 */

	function save_shipping_metabox( $shipping_meta ) {

		return $shipping_meta;

	}

	/**
	 * Use this function to return your calculated price as an integer or float
	 */

	public function calculate_shipping( $price, $total, $cart, $address1, $address2, $city, $state, $zip, $country, $selected_option ) {
		
		return (float) mp_get_session_value( 'mp_shipping_info->shipping_sub_option', 0 );

	}
	
	/**
	 * For calculated shipping modules, use this method to return an associative array of the sub-options
	 */
	
	function shipping_options( $cart, $address1, $address2, $city, $state, $zip, $country ) {
		
		$shipping_options	=	array();
		
		$settings	=	mp_get_setting('shipping');
		
		foreach ( $settings['fixed-rates']['rates'] as $key => $val ) {
			
			$shipping_options[ $val['ship_price'] ]		=	$val['ship_type'];
			
		}

		return $shipping_options;

	}

}

MP_Shipping_API::register_plugin( 'MP_Fixed_Rates_Delivery', 'fixed-rates', __( 'Fixed Rates', 'mp' ), true );
