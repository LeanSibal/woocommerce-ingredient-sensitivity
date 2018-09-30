<?php
/*
 * Plugin Name: WooCommerce Ingredient Sentivity
 * Plugin URI: https://github.com/LeanSibal/woocommerce-ingredient-sensitivity
 * Description: Allows Customers to select ingredients and ingredient group sentitivity based on products
 * Author: Lean Sibal
 * Author URI: https://github.com/LeanSibal/
 * Version: 0.0.1
 * Text Domain: woocommerce-ingredient-sensitivity
 * Domain Path: /languages
 */

if( ! defined(  'ABSPATH' ) ) exit;

class WC_Ingredient_Sensitivity {
	private static $instance;

	private static $actions = [
		'register_ingredients_taxonomy' => 'init',
		'ingredient_groups_submenu_page' => 'admin_menu'
	];

	public static function get_instance() {
		if( self:: $instance === null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->setup_filters();
		$this->setup_actions();
	}

	protected function setup_filters() {
	}

	protected function setup_actions() {
		foreach( self::$actions as $function => $hook ) {
			add_action( $hook, [ $this, $function ] );
		}
	}

	public function register_ingredients_taxonomy() {
		$labels = [
			'name'              => _x( 'Ingredients', 'taxonomy general name', 'woocommerce-ingredient-sensitivity' ),
			'singular_name'     => _x( 'Ingredient', 'taxonomy singular name', 'woocommerce-ingredient-sensitivity' ),
			'search_items'      => __( 'Search Ingredients', 'woocommerce-ingredient-sensitivity' ),
			'all_items'         => __( 'All Ingredients', 'woocommerce-ingredient-sensitivity' ),
			'parent_item'       => __( 'Parent Ingredient', 'woocommerce-ingredient-sensitivity' ),
			'parent_item_colon' => __( 'Parent Ingredient:', 'woocommerce-ingredient-sensitivity' ),
			'edit_item'         => __( 'Edit Ingredient', 'woocommerce-ingredient-sensitivity' ),
			'update_item'       => __( 'Update Ingredient', 'woocommerce-ingredient-sensitivity' ),
			'add_new_item'      => __( 'Add New Ingredient', 'woocommerce-ingredient-sensitivity' ),
			'new_item_name'     => __( 'New Ingredient Name', 'woocommerce-ingredient-sensitivity' ),
			'menu_name'         => __( 'Ingredients', 'woocommerce-ingredient-sensitivity' ),
		];
		register_taxonomy(
			'ingredients',
			[ 'product' ], [
				'hierarchical' => true,
				'labels' => $labels,
				'rewrite' => true,
				'query_var' => true
			]
		);
	}

	public function ingredient_groups_submenu_page() {
		add_submenu_page(
			'edit.php?post_type=product',
			'Ingredient Groups',
			'Ingredient Groups',
			'manage_options',
			'ingredient_groups_page',
			[ &$this, 'ingredient_groups_page' ]
		);
	}

	public function ingredient_groups_page() {
		ob_start();
		echo "hello";
		echo ob_get_clean();
	}

}


add_action('plugins_loaded', function(){
	WC_Ingredient_Sensitivity::get_instance();
});