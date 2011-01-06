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
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = required_param('id', PARAM_INT);
$active = optional_param('active', '', PARAM_ALPHANUM);

require_login();

if (!mgm_can_do_edit()) {
    error('You do not have the permission to active this edition.');
}

if (!$site = get_site()) {
    error('Site not found!');
}

$stractiveedition = get_string('activaedicion', 'mgm');
$strdeactiveedition = get_string('desactivaedicion', 'mgm');
$stradministration = get_string('administration');
$streditions = get_string('ediciones', 'mgm');

if (!$edition = get_record('edicion', 'id', $id)) {
    error('Edition ID was incorrect (can\'t find it)');
}

$edition->shortname = $edition->name;

$navlinks = array();

if (!$active) {
    $stractivecheck = get_string('activar', 'mgm');
    $strdeactivecheck = get_string('desactivar', 'mgm');

    $navlinks[] = array('name' => $stradministration, 'link' => "../$CFG->admin/index.php", 'type' => 'misc');
    $navlinks[] = array('name' => $streditions, 'link' => "index.php", 'type' => 'misc');

    if (!$edition->active) {
        $navlinks[] = array('name' => $stractivecheck, 'link' => null, 'type' => 'misc');
        $strcheck = $stractivecheck;
        $stredition = $stractiveedition;
    } else {
        $navlinks[] = array('name' => $strdeactivecheck, 'link' => null, 'type' => 'misc');
        $strcheck = $strdeactivecheck;
        $stredition = $strdeactiveedition;
    }

    $navigation = build_navigation($navlinks);

    print_header("$site->shortname: $strcheck", $site->fullname, $navigation);

    notice_yesno($stredition."<br /><br />" . format_string($edition->name),
                 "active.php?id=$edition->id&amp;active=".md5($edition->timemodified)."&amp;sesskey=$USER->sesskey",
                 "index.php");

    print_footer();
    exit;
}

if ($active != md5($edition->timemodified)) {
    error('The check variable was wrong - try again');
}

if (!confirm_sesskey()) {
    print_error('confirmsesskeybad', 'error');
}

$stractivingedition = get_string('activing', 'mgm', format_string($edition->name));
$strdeactivingedition = get_string('deactiving', 'mgm', format_string($edition->name));

$navlinks[] = array('name' => $stradministration, 'link' => "../$CFG->admin/index.php", 'type' => 'misc');
$navlinks[] = array('name' => $streditions, 'link' => "index.php", 'type' => 'misc');
$navlinks[] = array('name' => ($edition->active) ? $strdeactivingedition : $stractivingedition, 'link' => null, 'type' => 'misc');
$navigation = build_navigation($navlinks);

print_header($site->shortname.": ".($edition->active) ? $stractivingedition : $strdeactivingedition, $site->fullname, $navigation);

print_heading((!$edition->active) ? $stractivingedition : $strdeactivingedition);

if ($edition->active) {
    mgm_deactive_edition($edition);
    print_heading( get_string("deactivededicion", "mgm", format_string($edition->name)) );
} else {
    mgm_active_edition($edition);
    print_heading( get_string("activededicion", "mgm", format_string($edition->name)) );
}

print_continue("index.php");

print_footer();