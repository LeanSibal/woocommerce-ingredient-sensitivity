<?php

if( ! defined(  'ABSPATH' ) ) exit;

define( 'WIS_VERSION' , '0.0.1' );
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


class WC_Ingredient_Sensitivity {
	private static $instance;

	private static $actions = [
		'register_ingredients_taxonomy' => 'init',
		'add_ingredient_senstivity_endpoint_on_myaccount' => 'init',
		'ingredient_sensitivity_submenu_page' => 'admin_menu',
		'register_custom_checkboxes_css' => 'init'
	];

	private static $filters = [
		'add_ingredients_to_my_account_menu_item' => 'woocommerce_account_menu_items',
		'add_ingredient_sensitivitiy_template_to_woocommerce' => [
			'tag' => 'woocommerce_locate_template',
			'priority' => 10,
			'accepted_args' => 5
		],
		'ingredients' => 'woocommerce_account_ingredients_endpoint'
	];

	public function register_custom_checkboxes_css() {
		wp_register_style(
			'woocommerce_ingredient_sensitivity_custom_checkboxes',
			plugins_url( '/woocommerce-ingredient-sensitivity/assets/css/checkboxes.css' ),
			[],
			WIS_VERSION
		);
	}

	public function add_ingredient_senstivity_endpoint_on_myaccount() {
		add_rewrite_endpoint( 'ingredients', EP_PAGES );
		flush_rewrite_rules();
	}

	public function add_ingredients_to_my_account_menu_item( $items ) {
		$index = 1;
		return array_slice( $items, 0, $index, true ) +
			[ 'ingredients' => 'Ingredient Sensitivity' ] +
			array_slice( $items, $index, null, true );
	}

	public function add_ingredient_sensitivitiy_template_to_woocommerce( $template, $template_name, $template_path ) {
		if( $template_name == 'myaccount/ingredients.php' ) {
			$template = plugin_dir_path( __FILE__ ) . 'templates/myaccount/ingredients.php';
		}
		return $template;
	}

	public function ingredients() {
		wc_get_template(
			'myaccount/ingredients.php', []
		);
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

	public function ingredient_sensitivity_submenu_page() {
		add_submenu_page(
			'edit.php?post_type=product',
			'Ingredient Sensitivity',
			'Ingredient Sensitivity',
			'manage_options',
			'ingredient_sensitivty_page',
			[ &$this, 'ingredient_sensitivity_page' ]
		);
	}

	public function ingredient_sensitivity_page() {
		ob_start();
		require plugin_dir_path( __FILE__ ) . 'templates/admin/ingredient_sensitivity.php';
		echo ob_get_clean();
	}

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
		foreach( self::$filters as $function => $filter ) {
			$hook = !empty( $filter['tag'] ) ? $filter['tag'] : $filter;
			$priority = !empty( $filter['priority'] ) ? $filter['priority'] : 10;
			$accepted_args = !empty( $filter['accepted_args'] ) ? $filter['accepted_args'] : 1;
			add_filter( $hook, [ $this, $function ], $priority, $accepted_args );
		}
	}

	protected function setup_actions() {
		foreach( self::$actions as $function => $action ) {
			$tag = !empty( $action['tag'] ) ? $action['tag'] : $action;
			$priority = !empty( $action['priority'] ) ? $action['priority'] : 10;
			$accepted_args = !empty( $action['accepted_args'] ) ? $action['accepted_args'] : 1;
			add_filter( $tag, [ $this, $function ], $priority, $action );
		}
	}

}


add_action('plugins_loaded', function(){
	WC_Ingredient_Sensitivity::get_instance();
});