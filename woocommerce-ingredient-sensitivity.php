<?php

if( ! defined(  'ABSPATH' ) ) exit;

require_once('plugin.php');

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


class WC_Ingredient_Sensitivity extends WordPressPlugin {

	public $actions = [
		'register_ingredients_taxonomy' => 'init',
		'add_ingredient_senstivity_endpoint_on_myaccount' => 'init',
		'ingredient_sensitivity_submenu_page' => 'admin_menu',
		'show_ingredient_warnings' => 'woocommerce_shop_loop_item_title',
    'update_ingredient_sensitivity' => 'admin_post_update_ingredient_sensitivity'
	];

	public $filters = [
		'add_ingredients_to_my_account_menu_item' => 'woocommerce_account_menu_items',
		'add_ingredient_sensitivitiy_template_to_woocommerce' => [
			'tag' => 'woocommerce_locate_template',
			'priority' => 10,
			'accepted_args' => 5
		],
		'ingredients' => 'woocommerce_account_ingredients_endpoint'
	];

  public $styles = [
    'woocommerce_ingredient_sensitivity_custom_checkboxes' => 'assets/css/checkboxes.css'
  ];

  public $ajax = [
  ];

  public function update_ingredient_sensitivity() {
    $current_user_id = get_current_user_id();
    $redirect_url = home_url() . '/my-account/ingredients';
    if( empty( $current_user_id ) ) wp_safe_redirect( $redirect_url );
    if( empty( $_REQUEST['ingredient_sensitivity_nonce'] ) || !wp_verify_nonce( $_REQUEST['ingredient_sensitivity_nonce'], 'ingredient_sensitivity' ) ) wp_safe_redirect( $redirect_url );
    $ingredients = get_terms('ingredients', [
      'hide_empty' => false
    ]);
    $term_ids = [];
    foreach( $ingredients as $ingredient ) {
      if( !in_array( $ingredient->term_id, $_REQUEST['term_ids'] ) ) {
        $term_ids[] = $ingredient->term_id;
      }
    }
    $ingredient_sensitivity = get_user_meta( $current_user_id, 'ingredient_sensitivity', true );
    update_user_meta( $current_user_id, 'ingredient_sensitivity', $term_ids );
    wp_safe_redirect( home_url() . '/my-account/ingredients' );
    exit;
  }

	public function show_ingredient_warnings(){
		echo '<label style="color:red">Warning: Contains Beef, Eggs and Milk</label><br/>';
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

}


add_action('plugins_loaded', function(){
	new WC_Ingredient_Sensitivity;
});
