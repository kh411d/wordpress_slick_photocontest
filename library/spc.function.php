<?php
function spc_dg(){ 
  echo "<pre style='background-color:yellow;border:1px solid black;'>";	
  $numargs = func_num_args();
	if( $numargs > 0){
		$arg_list = func_get_args();
	    for ($i = 0; $i < $numargs; $i++) {
	        print_r($arg_list[$i]);
	        echo "</br>";
	    }		
	}else{
		print_r('empty');
	}
  echo "</pre>";	
}

function spc_is_postIDsdf(){
	global $wpdb,$post;
	 $results = $wpdb->get_results("SELECT contest_postid FROM ".$wpdb->spc_contest, ARRAY_A);
	 $temp = array();
	 foreach($results as $vals){$temp[] = $vals['contest_postid'];}
	 
	if(in_array($post->ID,$temp)){
      return TRUE;
	}else{
		return FALSE;
	}   
}

function spc_is_postID(){
	global $wpdb,$post;
	 $result = $wpdb->get_var("SELECT contest_id FROM ".$wpdb->spc_contest." WHERE contest_postid = ".$post->ID);
	 if($result){
        return $result;
	}else{
		return FALSE;
	}   
}

function spc_create_post($var){
	global $wpdb;
			$postObj = new stdClass;
			
			$postObj->post_title		= $var['contest_title'];
			$postObj->post_content		= $var['contest_description'];
		
			$postObj->post_status		= $var['contest_status'];
			$postObj->post_author		= $var['contest_author'];
			$postObj->post_name 		= $var['contest_title'];			
			$postObj->post_type			= $var['contest_posttype'];
			$postObj->post_parent		= $var['contest_postparent'];	
			$postObj->comment_status	= $var['contest_comment'];	
				
			$postID = wp_insert_post($postObj);

			//Save the ID			
			//$postObj->ID	= $postID;

			//Update the post
			//wp_update_post($postObj);
			$var['contest_postid'] = $postID;
			$new_contest_id = $wpdb->insert($wpdb->prefix.'spc_contest',$var);	
		     return $new_contest_id;
}

function spc_update_post($var){
	global $wpdb;
	$contest_id = $var['contest_id'];
	unset($var['contest_id']);
			$postObj = new stdClass;
			
			$postObj->post_title		= $var['contest_title'];
			$postObj->post_content		= $var['contest_description'];
		
			$postObj->post_status		= $var['contest_status'];
			$postObj->post_author		= $var['contest_author'];
			$postObj->post_name 		= $var['contest_title'];			
			$postObj->post_type			= $var['contest_posttype'];
			$postObj->post_parent		= $var['contest_postparent'];	
			$postObj->comment_status	= $var['contest_comment'];	
		    $postObj->ID				= $var['contest_postid'];

			//Update the post
			wp_update_post($postObj);
			$result = $wpdb->update( $wpdb->prefix.'spc_contest', $var, array( 'contest_id' => $contest_id ));		
		     return $result;
}




function spc_resizeImage( $file, $thumbpath, $max_side , $fixfor = NULL ) {

		// 1 = GIF, 2 = JPEG, 3 = PNG

	if ( file_exists( $file ) ) {
		$type = getimagesize( $file );

		if (!function_exists( 'imagegif' ) && $type[2] == 1 ) {
			$error = __( 'Filetype not supported. Thumbnail not created.' );
		}
		elseif (!function_exists( 'imagejpeg' ) && $type[2] == 2 ) {
			$error = __( 'Filetype not supported. Thumbnail not created.' );
		}
		elseif (!function_exists( 'imagepng' ) && $type[2] == 3 ) {
			$error = __( 'Filetype not supported. Thumbnail not created.' );
		} else {

			// create the initial copy from the original file
			if ( $type[2] == 1 ) {
				$image = imagecreatefromgif( $file );
			}
			elseif ( $type[2] == 2 ) {
				$image = imagecreatefromjpeg( $file );
			}
			elseif ( $type[2] == 3 ) {
				$image = imagecreatefrompng( $file );
			}

			if ( function_exists( 'imageantialias' ))
				imageantialias( $image, TRUE );

			$image_attr = getimagesize( $file );

			// figure out the longest side
        if($fixfor){
        	    if($fixfor == 'width'){
        	    	$image_width = $image_attr[0];
				$image_height = $image_attr[1];
				$image_new_width = $max_side;

				$image_ratio = $image_width / $image_new_width;
				$image_new_height = $image_height / $image_ratio;
        	    }elseif($fixfor == 'height'){
        	     $image_width = $image_attr[0];
				$image_height = $image_attr[1];
				$image_new_height = $max_side;

				$image_ratio = $image_height / $image_new_height;
				$image_new_width = $image_width / $image_ratio;	
        	    }
        }else{
			if ( $image_attr[0] > $image_attr[1] ) {
				$image_width = $image_attr[0];
				$image_height = $image_attr[1];
				$image_new_width = $max_side;

				$image_ratio = $image_width / $image_new_width;
				$image_new_height = $image_height / $image_ratio;
				//width is > height
			} else {
				$image_width = $image_attr[0];
				$image_height = $image_attr[1];
				$image_new_height = $max_side;

				$image_ratio = $image_height / $image_new_height;
				$image_new_width = $image_width / $image_ratio;
				//height > width
			}
        }	

			$thumbnail = imagecreatetruecolor( $image_new_width, $image_new_height);
			@ imagecopyresampled( $thumbnail, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $image_attr[0], $image_attr[1] );

			// move the thumbnail to its final destination
			if ( $type[2] == 1 ) {
				if (!imagegif( $thumbnail, $thumbpath ) ) {
					$error = 0;
				}
			}
			elseif ( $type[2] == 2 ) {
				if (!imagejpeg( $thumbnail, $thumbpath ) ) {
					$error = 0;
				}
			}
			elseif ( $type[2] == 3 ) {
				if (!imagepng( $thumbnail, $thumbpath ) ) {
					$error = 0;
				}
			}
		}
	} else {
		$error = 0;
	}

	if (!empty ( $error ) ) {
		return $error;
	} else {
		return $thumbpath;
	}
}

function spc_manage_participant($contest_id = 0){
 global $wpdb;	
 
 switch($_POST['submit_task']){
 	case 'Approve' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_update_comment(array('comment_ID'=>$value,'comment_approved'=>1));
	       				}
 					 }
 						break;
 	case 'UnApprove' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_update_comment(array('comment_ID'=>$value,'comment_approved'=>0));
	       				}
 					 }
 		break;
 	case 'Delete' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_trash_comment($value);
	       				}
 					 }
 					break;
 	default : break;
 }
 
 $sql = "SELECT * FROM ".
 		$wpdb->spc_participant." INNER JOIN ".$wpdb->prefix."comments ON ".$wpdb->spc_participant.".participant_commentID = ".$wpdb->prefix."comments.comment_ID ".
 		" WHERE ".$wpdb->prefix."comments.comment_approved != 'trash' ";
 if($contest_id){
 		$sql .= " AND ".$wpdb->spc_participant.".contest_id = ".$contest_id;
 }
 $results =  $wpdb->get_results($sql,ARRAY_A);
 //spc_dg($results);
 ?>
 <form name="listform" id="listform" action="admin.php?page=spc-manage-participant" method="POST">
  <table class="widefat">  
        <thead>  
         <tr>
         <td colspan="6">
         <b>Action :</b> &nbsp;&nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="Approve">
	         &nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="UnApprove">
	         &nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="Delete">
         </td>
         </tr>    
        <tr>
        <th scope="col">Select</th>  
        <th scope="col"><div style="text-align: center">ContestID</div></th>
        <th scope="col">PostID</th>  
		<th scope="col">Desciption</th>
		<th scope="col">Upload Date</th>
		<th scope="col">Approved</th>
		<th scope="col"></th>
        </tr>
        </thead>
        <tbody id="the-list">
       
        <?php
        $cnt = 0;
        foreach ($results as $val) {          
            echo "<tr ". ($cnt%2 == 0 ? ' class="alternate"' : '') . ">\n";
            echo "<th scope=\"row\" style=\"text-align: center\"><input type=\"checkbox\" value=\"".$val['comment_ID']."\" name=\"pid[]\" id=\"cb".$val['participant_id']."\"/></th>";
            echo "<th scope=\"row\" style=\"text-align: center\">" . $val['contest_id'] . "</th>\n";
            echo "<th scope=\"row\" style=\"text-align: left\" >" . $val['comment_post_ID'] . "</th>\n";     
             echo "<th  scope=\"row\">";
        $temp  = "<table border='0'>";
        $temp .= "<tr><th colspan='2'>By ".$val['participant_name']." (".$val['participant_email'].")</th></tr>";
		$temp .= "<tr><th valign='top'><p><img src='/wp-content/plugins/".SPC_FOLDER."/uploaded/thumb_".$val['participant_photo']."'/></p></th>";
	    $temp .= "<th valign='top'><p>".$val['participant_photo_description']."</p></th></tr>";
	    $temp .= "</table>";
	    echo $temp;
             echo "</th>";
             echo "<th  scope=\"row\">".$val['comment_date']."</th>";
             echo "<th  scope=\"row\">".($val['comment_approved'] ? 'Approved' : 'UnAproved')."</th>";
			echo "</tr>\n";

           
        }
        ?>        
        </tbody>
    </table>
    </form>
    <?php 
}

function spc_manage_contest($contest_id = 0){
 global $wpdb;	
 
 switch($_POST['submit_task']){
 	case 'Pubish' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_update_comment(array('comment_ID'=>$value,'comment_approved'=>1));
	       				}
 					 }
 						break;
 	case 'UnPublish' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_update_comment(array('comment_ID'=>$value,'comment_approved'=>0));
	       				}
 					 }
 		break;
 	case 'Delete' : if(count($_POST['pid'])>0){
	       				foreach($_POST['pid'] as $value){
	       					wp_trash_comment($value);
	       				}
 					 }
 					break;
 	default : break;
 }
 
 $sql = "SELECT * FROM ".
 		$wpdb->spc_contest.
 		" WHERE ".$wpdb->spc_contest.".contest_id <> 0 ";
 if($contest_id){
 		$sql .= " AND ".$wpdb->spc_contest.".contest_id = ".$contest_id;
 }
 $results =  $wpdb->get_results($sql,ARRAY_A);
 //spc_dg($results);
 ?>
 <form name="listform" id="listform" action="admin.php?page=spc-manage-participant" method="POST">
  <table class="widefat">  
        <thead>  
         <tr>
         <td colspan="6">
         <b>Action :</b> &nbsp;&nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="Approve">
	         &nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="UnApprove">
	         &nbsp;&nbsp;
	         <input class="button" type="submit" name="submit_task" value="Delete">
         </td>
         </tr>    
        <tr>
        <th scope="col">Select</th>  
        <th scope="col">PostID</th>  
		<th scope="col">Desciption</th>
		<th scope="col">Start-End Date</th>
		<th scope="col">Upload End Date</th>
		<th scope="col">Contest Status</th>
		<th scope="col">Photo MaxUpload/user</th>
		<th scope="col">Action</th>
		<th scope="col"></th>
        </tr>
        </thead>
        <tbody id="the-list">
       
        <?php
        $cnt = 0;
        foreach ($results as $val) {          
            echo "<tr ". ($cnt%2 == 0 ? ' class="alternate"' : '') . ">\n";
            echo "<th scope=\"row\" style=\"text-align: center\"><input type=\"checkbox\" value=\"".$val['contest_id']."\" name=\"pid[]\" id=\"cb".$val['contest_id']."\"/></th>";
            echo "<th scope=\"row\" style=\"text-align: center\">" . $val['contest_postid'] . "</th>\n";
             echo "<th  scope=\"row\">";
        $temp  = "<table border='0'>";
        $temp .= "<tr><th colspan='2'>Title: ".$val['contest_title']."</th></tr>";
		$temp .= "<tr><th valign='top'><p>".$val['contest_description']."</p></th></tr>";
	    $temp .= "</table>";
	    echo $temp;
             echo "</th>";
             echo "<th  scope=\"row\">".$val['contest_startdate']." until ".$val['contest_enddate']."</th>";
             echo "<th  scope=\"row\">".$val['contest_uploadenddate']."</th>";
             echo "<th  scope=\"row\">".$val['contest_status']."</th>";
             echo "<th  scope=\"row\">".$val['contest_maxupload']."</th>";
             echo "<th  scope=\"row\"><button class=\"button\" onclick=\"location.href='./admin.php?page=spc-addedit&contestID=".$val['contest_id']."';\">EDIT</button></th>";
			echo "</tr>\n";

           
        }
        ?>        
        </tbody>
    </table>
    </form>
    <?php 
}