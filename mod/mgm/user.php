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
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");
require_once($CFG->dirroot."/mod/mgm/user_form.php");

require_login();

if (!isloggedin() or isguestuser()) {
    error('You need to be logged into the platform!');
}

$id = optional_param('id', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);

// Editions
$editions = get_records('edicion');

// Strings
$strediciones      = get_string('ediciones', 'mgm');
$strperfil         = get_string('profile');
$strname           = get_string('name');
$strlastname       = get_string('lastname');
$strselect         = get_string('select');
$strmatricular     = get_string('mgm:aprobe', 'mgm');
$strheading        = $strperfil.' '.$strediciones.' '.$USER->firstname.' '.$USER->lastname;

$navlinks = array();
$navlinks[] = array('name' => $strediciones, 'type' => 'misc');
$navlinks[] = array('name' => $strperfil, 'type' => 'misc');

$navigation = build_navigation($navlinks);

$selectedespecs = mgm_get_user_especialidades($USER->id);
$allespecs = mgm_get_user_available_especialidades($USER->id);

$aespecs = $sespecs = array();

if (!empty($selectedespecs)) {
    $sespecs = $selectedespecs;
}

if (!empty($allespecs)) {
    $aespecs = $allespecs;
}

$userdata = new stdClass();
$userdata->cc = mgm_get_user_cc($USER->id);
$userdata->sespecs = $sespecs;
$userdata->aespecs = $aespecs;

$mform = new mod_mgm_user_form('user.php', $userdata);
$mform->set_data($userdata);

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/mod/mgm/index.php');
} else if ($data = $mform->get_data()) {
    mgm_set_userdata($USER->id, $data);
    redirect('user.php');
}

print_header($strmatricular, $strmatricular, $navigation);
print_heading($strheading);

$mform->display();

print_footer();