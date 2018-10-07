<?php
	if( ! defined(  'ABSPATH' ) ) exit;
	$ingredients_list = get_categories([
		'taxonomy' => 'ingredients',
		'hide_empty' => false,
		'title_li' => ""
	]);
	echo "<pre>";
	echo "</pre>";
?>
<div class="wrap">
	<h1>Ingredient Sensitivity</h1>
	<div class="manage-menus">
		<label>Select an ingredient sensitivity to edit:</label>
		<select name="ingredient_sensitivity">
			<option value="0">- Select -</option>
			<option value="hello">Hello</option>
		</select>
		<span class="submit-btn">
			<input type="submit" class="button" value="Select"/>
		</span>
		<span>
			or
			<a href="#">create a new ingredient sensitivty</a>.
		</span>
	</div>
	<div id="menu-management-liquid" class="nav-menus-php"  style="margin-top:23px;">
		<div id="menu-management">
			<div class="menu-edit">

				<div id="nav-menu-header">
					<div class="major-publishing-actions wp-clearfix">
						<label class="menu-name-label">Ingredient Sensitivity Name</label>
						<input id="menu-name" name="ingredient_sensitivity_name" type="text" class="regular-text menu-item-textbox menu-name"/>
						<div class="publishing-action">
							<input type="submit" name="save_sensitivity_group" class="button button-primary button-large" value="Save Ingredient Sensitivty"/>
						</div>
					</div>
				</div>

				<div id="post-body">
					<div id="post-body-content" class="wp-clearfix">
						<h3>Ingredients list</h3>
						<div class="post-body-plain">
							<p>Check or uncheck ingredients included in the ingredient sensitivity</p>
						</div>
					</div>
				</div>

				<div id="nav-menu-footer">
					<div class="major-publishing-actions wp-clearfix">
						<span class="delete-action">
							<a class="submitdelete deletion menu-delete" href="#">Delete ingredient sensitivity</a>
						</span>
						<div class="publishing-action">
							<input type="submit" class="button button-primary button-large" value="Save Ingredient Sensitivity"/>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
