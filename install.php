<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/

function spc_install()
{
    global $wpdb;
    $query1 = "CREATE TABLE $wpdb->spc_contest (
				  contest_id int(11) NOT NULL AUTO_INCREMENT,
  contest_title varchar(255) NOT NULL,
  contest_description longtext NOT NULL,
  contest_startdate date DEFAULT NULL,
  contest_enddate date DEFAULT NULL,
  contest_uploadenddate date DEFAULT NULL,
  contest_maxupload int(11) DEFAULT NULL,
  contest_status varchar(15) DEFAULT NULL,
  contest_posttype varchar(15) DEFAULT NULL,
  contest_comment varchar(15) DEFAULT NULL,
  contest_postparent int(11) DEFAULT NULL,
  contest_author int(11) NOT NULL,
  contest_postid int(11) NOT NULL,
  PRIMARY KEY (contest_id)
				);";   
    $query2= "CREATE TABLE IF NOT EXISTS $wpdb->spc_participant (
 participant_id int(11) NOT NULL AUTO_INCREMENT,
  contest_id int(11) NOT NULL,
  participant_name varchar(100) DEFAULT NULL,
  participant_email varchar(100) NOT NULL,
  participant_photo text,
  participant_photo_description text,
  participant_commentID int(11) NOT NULL,
  PRIMARY KEY (participant_id)
					);";
    $wpdb->query($query1);
    $wpdb->query($query2);
}
/*
CREATE TABLE `wp_spc_contest` (
  `contest_id` int(11) NOT NULL auto_increment,
  `contest_title` varchar(255) NOT NULL,
  `contest_description` longtext NOT NULL,
  `contest_startdate` date default NULL,
  `contest_enddate` date default NULL,
  `contest_uploadenddate` date default NULL,
  `contest_maxupload` int(11) default NULL,
  `contest_status` varchar(15) default NULL,
  `contest_posttype` varchar(15) default NULL,
  `contest_comment` varchar(15) default NULL,
  `contest_postparent` int(11) default NULL,
  `contest_author` int(11) NOT NULL,
  `contest_postid` int(11) NOT NULL,
  `contest_vote` longtext,
  PRIMARY KEY  (`contest_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE `wp_spc_participant` (
  `participant_id` int(11) NOT NULL auto_increment,
  `contest_id` int(11) NOT NULL,
  `participant_name` varchar(100) default NULL,
  `participant_email` varchar(100) NOT NULL,
  `participant_photo` text,
  `participant_photo_description` text,
  `participant_commentID` int(11) NOT NULL,
  `participant_vote` longtext,
  PRIMARY KEY  (`participant_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

*/
register_activation_hook(SPC_FOLDER . '/bootstrap.php', 'spc_install');