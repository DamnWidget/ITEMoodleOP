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
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

$editionedit = optional_param('editionedit', -1, PARAM_BOOL);

if ($CFG->forcelogin) {
    require_login();
}

if (!$site = get_site()) {
    error('Site isn\'t defined!');
}

$systemcontext = get_context_instance(CONTEXT_SYSTEM);

add_to_log(0, 'mgm', 'view ediciones', "index.php", '');

// Strings
$strmgm            = get_string('mgm', 'mgm');
$stredicion        = get_string('edicion', 'mgm');
$strediciones      = get_string('ediciones', 'mgm');
$stredicionesmgm   = get_string('edicionesmgmt', 'mgm');
$strplazas         = get_string('plazas', 'mgm');
$strfechainicio    = get_string('fechainicio', 'mgm');
$strfechafin       = get_string('fechafin', 'mgm');
$straddedicion     = get_string('addedicion', 'mgm');
$stradministration = get_string('administration');
$strdescription    = get_string('description');
$strcourses        = get_string('courses');
$stredit           = get_string('edit');
$stryes            = get_string('yes');
$strno             = get_string('no');


// Editions
$editions = get_records('edicion');

if (mgm_update_edition_button()) {
    if($editionedit !== -1) {
        $USER->editionediting = $editionedit;
    }
    $adminediting = !empty($USER->editionediting);
} else {
    $adminediting = false;
}

if (!$adminediting) {
    $navlinks = array();
    $navlinks[] = array('name' => $strediciones, 'type' => 'misc');

    print_header($site->shortname.': '.$strmgm, $strediciones, build_navigation($navlinks),
             '', '', true, mgm_update_edition_button());
    print_heading($strediciones);
    echo skip_main_destination();
    print_box_start('edicionesbox');
    mgm_print_whole_ediciones_list();
    print_box_end();
    print_course_search();
    print_footer();
    exit;
}

if (isset($editions) && is_array($editions)) {
    foreach($editions as $edition) {
        // Check if user can see the edition.
        if (!mgm_can_do_view()) {
            continue;
        }

        $editiontable->data[] = array(
            mgm_get_edition_link($edition),
            date('d/m/Y', $edition->inicio),
            date('d/m/Y', $edition->fin),
            mgm_count_courses($edition),
            mgm_get_edition_plazas($edition),
            mgm_get_edition_menu($edition)
        );
    }
}

// Navigation links
$navlinks = array();
$navlinks[] = array('name' => $stradministration, 'link' => '', 'type' => 'misc');
$navlinks[] = array('name' => $strediciones, 'link' => '', 'type' => 'misc');
$navlinks[] = array('name' => $stredicionesmgm, 'link' => '', 'type' => 'activity');

// Table header
$editiontable->head  = array($stredicion, $strfechainicio, $strfechafin, $strcourses, $strplazas, $stredit);
$editiontable->align = array('left', 'left', 'left', 'center', 'center', 'center');

print_edition_edit_header();
// Output the page
print_heading($strediciones);
print_table($editiontable);

echo '<div class="mod-mgm buttons">';
// Print button for creating new editions
if (mgm_can_do_create()) {
    print_single_button('edicionedit.php', '', $straddedicion, 'get');
}
echo '</div>';

admin_externalpage_print_footer();

function print_edition_edit_header() {
    global $CFG;
    require_once($CFG->libdir.'/adminlib.php');

    admin_externalpage_setup('edicionesmgmt', mgm_update_edition_button());
    admin_externalpage_print_header();
}