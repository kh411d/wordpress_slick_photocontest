<?php
function spc_the_content($content){
	global $spc_captcha_activated;
	$spc_captcha_activated = function_exists(recaptcha_wp_get_html) ? TRUE : FALSE;
	
   if(spc_is_postID()){
   	ob_start();
   	require_once SPC_TEMPLATES.'gallery.php';
   	$temp = ob_get_clean();
   	  $temp .= 'Photo List <BR>';
   	  $temp .= $content;
   	  
   	  $content = $temp;
	}
	return $content;
}

function spc_init(){
	wp_deregister_script( 'jquery' );
    wp_register_script(   'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js');
    wp_enqueue_script('jquery');
    
/*
    $jsLightboxUrl = WP_PLUGIN_URL . '/slick-photocontest/js/lightbox/jquery.lightbox-0.5.pack.js';
   $jsLightboxFile = WP_PLUGIN_DIR . '/slick-photocontest/js/lightbox/jquery.lightbox-0.5.pack.js';
   $styleLightboxUrl = WP_PLUGIN_URL.'/slick-photocontest/js/lightbox/css/jquery.lightbox-0.5.css';
   if ( file_exists($jsLightboxFile) ) {
			wp_register_script('slick-photocontest-lightbox-script', $jsLightboxUrl);
			wp_enqueue_script( 'slick-photocontest-lightbox-script');	
			wp_enqueue_style( 'slick-photocontest-lightbox-style',$styleLightboxUrl);	
		}	
	*/

}

function spc_comments_template($path)
{  
	if(spc_is_postID()){
	  $path =  SPC_FORMS.'upload_form.php';
	 return $path;	
	}
}

function spc_comment_text($content)
{  
	global $wpdb,$comment;

	if(spc_is_postID()){
		$photo = $wpdb->get_var("SELECT participant_photo FROM ".$wpdb->spc_participant." WHERE participant_commentID = ".$comment->comment_ID);
        
        $temp = "<table border='0'><tr>";
		$temp .= "<td valign='top'><p><img src='/wp-content/plugins/".SPC_FOLDER."/uploaded/thumb_".$photo."'/></p></td>";
	    $temp .= "<td valign='top'><p>".$content."</p></td>";
	    $temp .= "<tr></table>";
	    $content = $temp;
	}	
	return $content;
}
?>