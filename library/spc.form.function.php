<?php
/*
 * quickform callback
 */
function validateSimpleCaptcha(){
	if($_SESSION['captcha_key'] != $_POST['captcha_input']) return false; else return true;
}

function validateMCSP(){
	global $spc_mcsp;
	$res = $spc_mcsp->InputValidation($_POST['mcsp_result'], $_POST['human_result']);
	if(!$res) return true; else return false;
}

function validateMCSP2(){
	global $spc_mcsp;
	$res = $spc_mcsp->InputValidation($_POST['mcsp_result2'], $_POST['human_result2']);
	if(!$res) return true; else return false;
}

function spc_addedit_contest($contest_id = NULL){
	global $spcIn,$wpdb,$current_user;
	//spc_dg($spcin->get('gil'));
	get_currentuserinfo();
	// Instantiate the HTML_QuickForm object
	$form = new HTML_QuickForm('firstForm','POST','./admin.php?page=spc-addedit');
	
	// Set defaults for the form elements
	if($contest_id){
	 $default = $wpdb->get_row("SELECT * FROM ".$wpdb->spc_contest." WHERE contest_id = ".$contest_id,ARRAY_A);
	 $form->setDefaults($default);
	}else{
		$form->setDefaults(array(
		    'contest_author' => $current_user->data->ID
		));
	}
	// Add some elements to the form
	$form->addElement('hidden', 'contest_author' );
	$form->addElement('hidden', 'contest_id' );
	$form->addElement('header', null, 'Contest Description');
	$form->addElement('text', 'contest_title', 'Contest Title:', array('size' => 50, 'maxlength' => 255));
	$form->addElement('textarea', 'contest_description', 'Describe Contest:', array('cols' => 40, 'rows' => 6));
	
	$form->addElement('header', null, 'Contest Start/End Date');
	$form->addElement('date', 'contest_startdate', 'Start Date:', array('format'=>'dmY','size' => 50, 'maxlength' => 255));
	$form->addElement('date', 'contest_enddate', 'End Date:', array('format'=>'dmY','size' => 50, 'maxlength' => 255));
	
	$form->addElement('header', null, 'Upload End Date');
	$form->addElement('date', 'contest_uploadenddate', 'End Date:', array('format'=>'dmY','size' => 50, 'maxlength' => 255));
	$form->addElement('header', null, 'Option');
	$form->addElement('text', 'contest_maxupload', 'Max Upload/User:', array('size' => 3, 'maxlength' => 3));
	$form->addElement('select', 'contest_status', 'Status:', array('publish' => 'Publish', 'pending' => 'Pending','draft' => 'Draft'));
	$form->addElement('select', 'contest_posttype', 'Page Type:', array('page' => 'Page', 'post' => 'Post'));
	$form->addElement('select', 'contest_comment', 'Comment Status:', array('open' => 'Open', 'closed' => 'Close'));
	
	$page_obj = get_pages(array('post_type'=>'page'));
	$temp = array(0=>'No Parent'); 
	foreach($page_obj as $key)$temp[$key->ID] = $key->post_title;
	$form->addElement('select', 'contest_postparent', 'Parent Page:', $temp);
	$form->addElement('submit', null, 'Send');
	// Define filters and validation rules
	$form->addRule('contest_title', 'Please enter contest title', 'required');
	$form->addRule('contest_description', 'Please enter contest Description', 'required');
	 
	// Try to validate a form 
	if ($form->validate()) {
		$values = $form->exportValues();
		
		$values['contest_startdate'] = $values['contest_startdate']['Y']."-".$values['contest_startdate']['m']."-".$values['contest_startdate']['d'];
	    $values['contest_enddate'] = $values['contest_enddate']['Y']."-".$values['contest_enddate']['m']."-".$values['contest_enddate']['d'];
		$values['contest_uploadenddate'] = $values['contest_uploadenddate']['Y']."-".$values['contest_uploadenddate']['m']."-".$values['contest_uploadenddate']['d'];
	    //spc_dg($values);
	    if($values['contest_id']){
	    	$result = spc_update_post($values);
	    }else{
	    	unset($values['contest_id']);
	    	$result = spc_create_post($values);
	    }
	   
}



// Output the form
$form->display();

 
}



function spc_check_maxupload(){
	global $wpdb,$spc,$post;
	$spc->get_contest(array('contest_postid = '=>$post->ID));
	$sql = "SELECT COUNT(*) as total FROM ".$wpdb->spc_participant." WHERE participant_email = '".$_POST['participant_email']."' AND contest_id = ".$spc->contest_data[0]['contest_id'];
    $tot = $wpdb->get_var($sql);
   // spc_dg($sql,$tot,$spc->contest_data);
    if($tot<$spc->contest_data[0]['contest_maxupload']) return TRUE; else return FALSE;
}

function spc_upload_form(){
  global $spcIn,$post,$wpdb,$spc,$errors,$spc_captcha_activated,$spc_mcsp;
	//spc_dg($spcin->get('gil'));
	
	// Instantiate the HTML_QuickForm object
	$form = new HTML_QuickForm('uploadForm','POST',$post->guid);     
	
	
	$spc_mcsp->GenerateValues();
	$form->setDefaults(array('mcsp_result2'=>$spc_mcsp->info['result']));
	
	// Add some elements to the form
	$form->addElement('header','notification','');
	$form->addElement('header', null, 'Fill Up Detail');
	$form->addElement('hidden', 'post_id', $post->ID);
		$form->addElement('hidden', 'spcform', 'uploadform');
	$form->addElement('text', 'participant_name', 'Your Name:', array('size' => 50, 'maxlength' => 255));
	$form->addElement('text', 'participant_email', 'Your Email:', array('size' => 50, 'maxlength' => 255));
	$file = &$form->addElement('file', 'participant_photo', 'Upload:', array('size' => 35));
	$form->addElement('textarea', 'participant_photo_description', 'Photo About:', array('cols' => 40, 'rows' => 6));
	
	
	
	$form->addElement('text', 'human_result2', $spc_mcsp->info['operand1'].' + '.$spc_mcsp->info['operand2'].' = ', array('size' => 2, 'maxlength' => 2));
	$form->addElement('hidden','mcsp_result2');
	
	
	
	$form->addElement('submit', null, 'Send');
	// Define filters and validation rules
	$form->addRule('participant_name', 'Please enter Your Name', 'required');
	$form->addRule('participant_email', 'Please enter Your Email', 'required');
	$form->addRule('participant_photo', 'Photo is Empty', 'required');
	$form->addRule('participant_photo', 'Photo is Empty', 'uploadedfile');
	$form->addRule('participant_photo_description', 'Photo About is Empty', 'required');
	$form->addRule('participant_photo','File Type Allowed(jpg,bmp,png,jpeg)','mimetype',array('image/png','image/jpg','image/jpeg','image/pjpeg','image/bmp'));
        
	$form->registerRule('mcsp_check2','callback','validateMCSP2');
	$form->addRule('human_result2','Wrong Sum','mcsp_check2',TRUE);  
	
	$form->registerRule('maxupload_check','callback','spc_check_maxupload');
	$form->addRule('participant_email','You have reach maximum upload','maxupload_check',TRUE);  
		
		if($form->getSubmitValue('spcform')=='uploadform'){	
			// Try to validate a form 		
			if ($form->validate()) {
				
				
				
				   $values = $form->exportValues();
					$post_id = $values['post_id'];
					$spc->get_contest(array('where'=>'contest_postid='.$values['post_id']));
				 
					if($file->isUploadedFile()){
				            $fileInfo = $file->getValue();
				            $time = time();
				            $upload_ok = FALSE;
				            //Upload Thumbnail
				            $thumb_uploadTo =  SPC_UPLOADED.'thumb_'.md5($values['participant_email']).'_'.$time.'.jpg';
				            $upload_ok = spc_resizeImage($fileInfo['tmp_name'], $thumb_uploadTo, 100,'width') ? TRUE : FALSE;
				            //Upload Image
				            $image_uploadTo =  SPC_UPLOADED.md5($values['participant_email']).'_'.$time.'.jpg';
				            $upload_ok = spc_resizeImage($fileInfo['tmp_name'], $image_uploadTo, 600,'width') ? TRUE : FALSE;
				            //Insert Database if everything ok
				            if($upload_ok){
				                unset($values['post_id'],$values['MAX_FILE_SIZE'],$values['spcform']);
				                $values['contest_id'] = $spc->contest_data[0]['contest_id'];
				            	$values['participant_photo'] = md5($values['participant_email']).'_'.$time.'.jpg';
				            	
				            	 //Insert New Comment
				            	$commentdata = array(
					            	'comment_post_ID' => $post_id, 
					            	'comment_author' => $values['participant_name'], 
					            	'comment_author_email' => $values['participant_email'], 
					            	'comment_author_url' => '', 
					            	'comment_content' => $values['participant_photo_description']
				            	);
				            	$values['participant_commentID'] = wp_new_comment($commentdata);
				            	
				            	$new_contest_id = $wpdb->insert($wpdb->spc_participant,$values);
				            	
				            	$temp = "<table border='0' style='background-color:white;width:100%;white-space:normal;'><tr><td colspan='2' >Thank you for joining the contest.<Br/>Your photo is awaiting moderation</td></tr><tr>";
								$temp .= "<td valign='top' style='width:105px;'><p><img src='/wp-content/plugins/".SPC_FOLDER."/uploaded/thumb_".md5($values['participant_email'])."_".$time.".jpg'/></p></td>";
							    $temp .= "<td valign='top' ><p >".$values['participant_photo_description']."</p></td>";
							    $temp .= "<tr></table>";	
					           $form->setDefaults(array('notification'=>$temp));
				            }
				     }
				   
				
			}
			//END form validation
		}
	
	
	// Output the form
	$form->display();

}

function spc_ajax_recaptcha($elID){
	global $recaptcha_opt;
	return "<div id=\"".$elID."\"></div></script><script>$(document).ready(function () {
	Recaptcha.create(\"".$recaptcha_opt['pubkey']."\", '".$elID."', {theme: 'red',tabindex: 0,callback: Recaptcha.focus_response_field});});</script>";
}

/*
 * straight simple captcha generator
 */



function spc_like_form(){
	global $spcIn,$post,$wpdb,$spc,$errors,$recaptcha_opt,$spc_captcha_activated,$spc_mcsp;
	//spc_dg($spcin->get('gil'));
	
  
	// Instantiate the HTML_QuickForm object
	$form = new HTML_QuickForm('likeForm','POST',$post->guid."#like");   

	$spc_mcsp->GenerateValues();
	$form->setDefaults(array('mcsp_result'=>$spc_mcsp->info['result']));
	
	// Add some elements to the form
	$form->addElement('header','notification','');
	
	$form->addElement('hidden', 'post_id', $post->ID);
	$form->addElement('hidden', 'comment_id');
		$form->addElement('hidden', 'spcform', 'likeform');
	$form->addElement('text', 'ln', 'Name:', array('size' => 50, 'maxlength' => 255));
	$form->addElement('text', 'le', 'Email:', array('size' => 50, 'maxlength' => 255));
	$form->addElement('header','captcha_notification','');
	
	
	$form->addElement('text', 'human_result', $spc_mcsp->info['operand1'].' + '.$spc_mcsp->info['operand2'].' = ', array('size' => 2, 'maxlength' => 2));
	$form->addElement('hidden','mcsp_result');
	
	
	
	
	$form->addElement('submit', null, 'VOTE');
	// Define filters and validation rules
	$form->addRule('ln', 'Please enter Your Name', 'required');
	$form->addRule('le', 'Please enter Your Email', 'required');
	$form->addRule('comment_id', 'Sorry Failed, Refresh your browser', 'required');
	
	$form->registerRule('mcsp_check','callback','validateMCSP');
	$form->addRule('human_result','Wrong Sum','mcsp_check',TRUE);


		if($form->getSubmitValue('spcform')=='likeform'){	
			// Try to validate a form 		
			if ($form->validate()) {
				
				    $values = $form->exportValues();
					/**/
					
					$post_id = $values['post_id'];
					$comment_id = $values['comment_id'];
					$participant_vote = $wpdb->get_var("SELECT participant_vote FROM ".$wpdb->spc_participant." WHERE participant_commentID = ".$comment_id);
					unset($values['post_id'],$values['comment_id'],$values['MAX_FILE_SIZE'],$values['mcsp_result'],$values['human_result']);
					
					$temp = array();
					$temp = unserialize($participant_vote);
						$temp[] = $values;

					$update_values = array('participant_vote'=>serialize($temp));
					
					if($wpdb->update($wpdb->spc_participant,$update_values,array('participant_commentID'=>$comment_id))){
						 $form->setDefaults(array('notification'=>'<div style="width:100%; background-color:white;">Vote Submitted</div>'));
					}
				    /**/     
					          
			}
			//END form validation
		}
	
	
	// Output the form
	$form->display();
}
?>