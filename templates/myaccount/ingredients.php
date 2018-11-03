<?php
	wp_enqueue_style( 'woocommerce_ingredient_sensitivity_custom_checkboxes' );
	$ingredients = get_terms('ingredients', [
		'hide_empty' => false
	]);
  $term_ids = get_user_meta( get_current_user_id(), 'ingredient_sensitivity', true);
  if( empty( $term_ids ) ) $term_ids = [];
?>
<h1>Ingredient Sensitivity</h1>
<form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
  <input type="hidden" name="action" value="update_ingredient_sensitivity"/>
  <input type="hidden" name="ingredient_sensitivity_nonce" value="<?php echo wp_create_nonce( 'ingredient_sensitivity' ); ?>">
  <?php foreach( $ingredients as $ingredient ) : ?>
  <label class="ingredients-container"><?php echo $ingredient->name; ?>
    <input name="term_ids[]" type="checkbox" <?php echo !in_array( $ingredient->term_id, $term_ids ) ? 'checked="checked"' : ''; ?> value="<?php echo $ingredient->term_id; ?>">
    <span class="checkmark"></span>
  </label>
  <?php endforeach; ?>
  <input type="submit" value="Save" style="margin-top:20px"/>
 </form>
