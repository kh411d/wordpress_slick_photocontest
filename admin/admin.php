<?php
/*
Copyright (c) 2008 Khalid Adisendjaja (kh411d@yahoo.com)
WordPress Slick Quiz Plugin is released under the GNU General Public
License (GPL) http://www.gnu.org/licenses/gpl.txt
*/
// add to menu
add_action('admin_menu', 'spc_addmenu');

function spc_addmenu()
{
    add_menu_page('Slick Photo Contest', 'Slick Photo Contest', 8, SPC_FOLDER, 'spc_display_menu_content');
    add_submenu_page(SPC_FOLDER, 'Add Contest', 'Add Contest', 8, 'spc-addedit', 'spc_display_menu_content');
    add_submenu_page(SPC_FOLDER, 'Manage Participant', 'Manage Participant', 8, 'spc-manage-participant', 'spc_display_menu_content');   
}

function spc_display_menu_content()
{
    switch ($_GET["page"]) {
        case 'spc-addedit':          
        	spc_addedit_contest($_GET['contestID']);
            break;
        case 'slick-photo-contest':
          spc_manage_contest();
          break;
        case 'spc-manage-participant':
         spc_manage_participant();
          break;    
        default:
          spc_manage_contest();
          break;
    }
}


?>