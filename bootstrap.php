<?php
/*
Plugin Name: Slick Photo Contest
Plugin URI: http://www.khalidadisendjaja.web.id/
Description: Its just another Photo Contest hopefull to be slick enough, a new contest will be placed on Page/Post, modified the comment form into photo uploader and also comment list previewing last photos uploaded by participants
Author: Khalid Adisendjaja
Author URI: http://www.khalidadisendjaja.web.id/
Version: 0.1 Beta

Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/  
function spc_bootstrap(){
	
    global $wpdb,$spcIn,$spc,$spc_captcha_activated,$spc_mcsp;
    //LOAD library    
    define('SPC_FOLDER', dirname(plugin_basename(__FILE__)));   
    define('SPC_LIB', dirname(__FILE__) . "/library/");
    define('SPC_FORMS', dirname(__FILE__) . "/templates/");
    define('SPC_TEMPLATES', dirname(__FILE__) . "/templates/");
    define('SPC_UPLOADED', dirname(__FILE__) . "/uploaded/");
    define('SPC_UPLOADED_URL', WP_PLUGIN_URL . "/slick-photocontest/uploaded/");
    ini_set("include_path",SPC_LIB);
    
     require_once ("spc.CI_Input.php");
     require_once ("spc.function.php");
     require_once ("spc.class.php");
     require_once ("spc.hook.function.php");
     require_once ("HTML/QuickForm.php");
     require_once ("spc.form.function.php");
     require_once ("math-comment-spam-protection.classes.php");
     
    $spc_mcsp = new MathCheck; 
     
    $spcIn = new spc_CI_Input; 
    $spc = new spc();
    // ADD TABLE POINTER
    $wpdb->spc_contest = $wpdb->prefix . 'spc_contest';
    $wpdb->spc_participant = $wpdb->prefix . 'spc_participant';
    // INSTALL DATABASE
    require_once (dirname(__FILE__) . "/install.php");
    // INCLUDE REQUIRED MODULE
    //require_once (dirname(__FILE__) . "/functions.php");
    // LOAD ADMIN PANEL
    require_once (dirname(__FILE__) . "/admin/admin.php");
    
    add_action('init','spc_init');
    add_filter('comments_template', 'spc_comments_template');
    add_filter('comment_text', 'spc_comment_text');
    add_filter('the_content', 'spc_the_content');
    

    
}
spc_bootstrap();