<?php
/*
  Plugin Name: Random Featured Images
  Description: Tired of no post thumbnails ? or Single default thumbnail for all posts having no featured image sets ? Here is an awesome plugin for Default Random Featured Images.
  Version: 1.0
  Author: Kashif Ali
  Author URI: http://www.kashif-arain.com
 */

define('hmk_url', plugin_dir_url(__FILE__));
define('hmk_path', plugin_dir_path(__FILE__));

require_once hmk_path . 'options-framework/options-framework.php';

function hmk_get_random_featured_img () { 
    
    $hmk_arr = get_option('hmk_admin_options');
    
    if ($hmk_arr['hmk_number_images']) {
        $hmk_num = $hmk_arr['hmk_number_images'] ;
    } else {
        $hmk_num = 3;
    }
    
    $hmk_rand = rand(1, $hmk_num);
    $hmk_rand_index = "defult_featured_image_".$hmk_rand;
    $hmk_random_featured_image =  $hmk_arr["$hmk_rand_index"];
    
    return $hmk_random_featured_image;
}

function hmk_get_random_attachment_id() {
 
	global $wpdb;
	
	$random_image_url = hmk_get_random_featured_img();
	$random_attachment_id = false;
 
	if ( '' == $random_image_url )
		return;
 
	$upload_dir_paths = wp_upload_dir();
 
	if ( false !== strpos( $random_image_url, $upload_dir_paths['baseurl'] ) ) {
 
		$random_image_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $random_image_url );
 
		$random_image_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $random_image_url );
 
		$random_attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $random_image_url ) );
 
	} 
	return $random_attachment_id;
} 

add_action( 'publish_post', 'hmk_set_random_thumbnail' ); 
add_action( 'edit_post', 'hmk_set_random_thumbnail' ); 
add_action( 'transition_post_status', 'hmk_set_random_thumbnail' );

  
function hmk_set_random_thumbnail( $post_id ) {  
     $hmk_random_attachment_id = hmk_get_random_attachment_id();
     $post_thumbnail = get_post_meta( $post_id, $key = '_thumbnail_id', $single = true );  
  
     if ( !wp_is_post_revision( $post_id ) ) {  
        
		   if ( empty( $post_thumbnail ) ) {  
           update_post_meta( $post_id, $meta_key = '_thumbnail_id', $meta_value = $hmk_random_attachment_id );  
        }  
    }  
  
}  
