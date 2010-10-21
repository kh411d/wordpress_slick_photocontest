<?php
Class spc {
	var $contest_data = array();
	var $db;
	var $notify_error;
	
	function spc(){
		global $wpdb;
		$this->db = & $wpdb;
	}
	
	function show_photo_gallery($contest_id){
		
	}
	
	function get_photo_list($param){
	 global $wpdb;
	  $sql = "SELECT * FROM ".
 			 $wpdb->spc_participant." INNER JOIN ".$wpdb->prefix."comments ON ".$wpdb->spc_participant.".participant_commentID = ".$wpdb->prefix."comments.comment_ID ".
 			 " WHERE ".$wpdb->prefix."comments.comment_approved = 1 ";
 		if(count($param)>0){
 		   $sql .= " AND ";
		   foreach($param as $key => $value)$sql .= " ".$key." ".$value." ";
		}
		
	  return $this->db->get_results($sql,ARRAY_A);	
	}
	
	function get_contest($param = array()){
		global $wpdb;
		$sql = "SELECT * FROM ".$wpdb->spc_contest." WHERE contest_status = 'publish' ";
		if(count($param)>0){
		   $sql .= " AND ";
		   foreach($param as $key => $value){
		   	 $sql .= $key." ".$value." ";
		   }
		}
		//$this->db->show_errors();
		$this->contest_data = $this->db->get_results($sql,ARRAY_A);
		
		
		return $this->contest_data;
	} 
	
	
	function is_contest_available($contest_id){
	  global $wpdb;
	  $err = '';
	  $sql = "SELECT contest_id FROM ".$wpdb->spc_contest." WHERE contest_enddate > NOW() AND contest_startdate < NOW() AND contest_id  = ".$contest_id;
	  $result = $this->db->get_var($sql);
	  return $result ? '' : 'Sorry for your inconvinience, The Contest is Closed<Br> Please Wait for The Winner';
	  
	  
	  $sql = "SELECT contest_id FROM ".$wpdb->spc_contest." WHERE contest_uploadenddate > NOW() AND contest_id  = ".$contest_id;
	  $result = $this->db->get_var($sql);
	  return $result ? '' : 'Sorry for your inconvinience, Upload time has Passed<Br> Please takes time to vote others';
	  
	}
	
	function show_contest_result(){
		
	}
	
	function show(){
		
	}
	
	
}