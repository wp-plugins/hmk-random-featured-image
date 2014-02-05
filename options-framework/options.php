<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function optionsframework_option_name() {

	$optionsframework_settings = get_option( 'optionsframework' );
	$optionsframework_settings['id'] = 'hmk_admin_options';
	update_option( 'optionsframework', $optionsframework_settings );
}



function optionsframework_options() {

	$options = array();
	$hmk_arr = get_option('hmk_admin_options');
	
	if ($hmk_arr['hmk_number_images']) {  $hmk_num = $hmk_arr['hmk_number_images'] ; } else {  $hmk_num = 3;  }
	
	$options[] = array(
		'name' => __('Number of Images', 'options_framework_theme'),
		'desc' => __('Number of random featured Images.', 'options_framework_theme'),
		'id' => 'hmk_number_images',
		'std' => 3,
		'class' => 'mini',
		'type' => 'text');	

	
	for ( $i=1 ; $i <=$hmk_num; $i++) {
	$options[] = array(
		'name' => __('Default Featured Image '.$i, 'options_framework_theme'),
		'id' => 'defult_featured_image_'.$i,
		'type' => 'upload');

	} 
	
	 
	
	return $options;
}