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
 * Code to delete an edition
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = required_param('id', PARAM_INT);
$delete = optional_param('delete', '', PARAM_ALPHANUM);

require_login();

if (!mgm_can_do_edit()) {
    error('You do not have the permission to delete this edition.');
}

if (!$site = get_site()) {
    error('Site not found!');
}

$strdeleteedition = get_string('deletedicion', 'mgm');
$stradministration = get_string('administration');
$streditions = get_string('ediciones', 'mgm');

if (!$edition = get_record('edicion', 'id', $id)) {
    error('Edition ID was incorrect (can\'t find it)');
}

$edition->shortname = $edition->name;

$navlinks = array();

if (!$delete) {
    $strdeletecheck = get_string('deletecheck', '', $edition->name);
    $strdeleteeditioncheck = get_string('deleteedicioncheck', 'mgm');

    $navlinks[] = array('name' => $stradministration, 'link' => "../$CFG->admin/index.php", 'type' => 'misc');
    $navlinks[] = array('name' => $streditions, 'link' => "index.php", 'type' => 'misc');
    $navlinks[] = array('name' => $strdeletecheck, 'link' => null, 'type' => 'misc');
    $navigation = build_navigation($navlinks);

    print_header("$site->shortname: $strdeletecheck", $site->fullname, $navigation);

    notice_yesno($strdeleteeditioncheck."<br /><br />" . format_string($edition->name),
                 "delete.php?id=$edition->id&amp;delete=".md5($edition->timemodified)."&amp;sesskey=$USER->sesskey",
                 "index.php");

    print_footer();
    exit;
}

if ($delete != md5($edition->timemodified)) {
    error('The check variable was wrong - try again');
}

if (!confirm_sesskey()) {
    print_error('confirmsesskeybad', 'error');
}

// OK checks done, delete the edition now.

add_to_log(SITEID, "edition", "delete", "view.php?id=$edition->id", "$edition->name (ID $edition->id)");

$strdeletingedition = get_string("deletingedition", "mgm", format_string($edition->name));

$navlinks[] = array('name' => $stradministration, 'link' => "../$CFG->admin/index.php", 'type' => 'misc');
$navlinks[] = array('name' => $streditions, 'link' => "index.php", 'type' => 'misc');
$navlinks[] = array('name' => $strdeletingedition, 'link' => null, 'type' => 'misc');
$navigation = build_navigation($navlinks);

print_header("$site->shortname: $strdeletingedition", $site->fullname, $navigation);

print_heading($strdeletingedition);

mgm_delete_edition($edition);

print_heading( get_string("deletededicion", "mgm", format_string($edition->name)) );

print_continue("index.php");

print_footer();