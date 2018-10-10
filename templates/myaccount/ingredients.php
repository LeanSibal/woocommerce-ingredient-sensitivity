<?php
	wp_enqueue_style( 'woocommerce_ingredient_sensitivity_custom_checkboxes' );
	$ingredients = get_terms('ingredients', [
		'hide_empty' => false
	]);
?>
<h1>Ingredient Sensitivity</h1>
<?php foreach( $ingredients as $ingredient ) : ?>
<label class="container"><?php echo $ingredient->name; ?>
  <input type="checkbox" checked="checked">
  <span class="checkmark"></span>
</label>
<?php endforeach; ?>
