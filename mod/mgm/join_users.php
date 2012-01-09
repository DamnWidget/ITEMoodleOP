<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * User Certifications Review
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Jesús Jaén <jesus.jaen@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot."/mod/mgm/user_form2.php");
require_once($CFG->dirroot.'/'.$CFG->admin.'/user/lib.php');
require_once($CFG->dirroot."/user/filters/lib.php");

$strstep=get_string('selectsourceuser', 'mgm');
admin_externalpage_setup('joinusers');
$ufiltering = new user_filtering();
$user_form = new user_mgm_form2(null, get_selection_data($ufiltering));
if ($data = $user_form->get_data(false)) {
    if 	(!empty($data->cancel)){
    	unset($_POST);
    	unset($SESSION->sourceuser);
    	unset($SESSION->destinationuser);
    	redirect("$CFG->wwwroot".'/index.php');
    }
    else if (!empty($data->next) && !isset($SESSION->sourceuser)) {
        if (!empty($data->ausers)) {
            $SESSION->sourceuser=$data->ausers;
            $strstep=get_string('selectdestinationuser', 'mgm');
        }
    }else if (!empty($data->next) && isset($SESSION->sourceuser) && !isset($SESSION->destinationuser)){
    		if (!empty($data->ausers)) {
            $SESSION->destinationuser=$data->ausers;
        }
    }
		if (isset($SESSION->sourceuser)&& isset($SESSION->destinationuser)){
        	redirect("$CFG->wwwroot".'/mod/mgm/join_users_act.php');
    }
    // reset the form selection
    unset($_POST);
    $user_form = new user_mgm_form2(null, get_selection_data($ufiltering));
}else{
 	unset($_POST);
  unset($SESSION->sourceuser);
  unset($SESSION->destinationuser);
  unset($SESSION->joinusers);
}


// do output
admin_externalpage_print_header();
print_heading($strstep);
$ufiltering->display_add();
$ufiltering->display_active();
$user_form->display();
admin_externalpage_print_footer();
