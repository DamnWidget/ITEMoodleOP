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
 * Edit editions settings
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/edit_form.php');

require_login();

$id = optional_param('id', 0, PARAM_INT);    // Edition id

if ($id) {
    if (!$edition = get_record('edicion', 'id', $id)) {
        error('Edition not known!');
    }

    require_capability('mod/mgm:editedicion', get_context_instance(CONTEXT_SYSTEM));
    $strtitle = get_string('editeditionsettings', 'mgm');
} else {
    $strtitle = get_string('newedition', 'mgm');
}

if (isset($edition)) {
    $selectedcourses = mgm_get_edition_courses($edition);
    $allcourses = mgm_get_edition_available_courses($edition);
}

$acourses = $scourses = array();

if (!empty($selectedcourses)) {
    foreach($selectedcourses as $selectedcourse) {
        $scourses[$selectedcourse->id] = $selectedcourse->fullname;
    }
}

if (!empty($allcourses)) {
    foreach ($allcourses as $allcourse) {
        $acourses[$allcourse->id] = $allcourse->fullname;
    }
}

if (isset($edition) && mgm_edition_is_active($edition)) {
    redirect('index.php', get_string('edicionactiva', 'mgm'), 5);
}

$edition->scourses = $scourses;
$edition->acourses = $acourses;

$mform = new mod_mgm_edit_form('edicionedit.php', $edition);
$mform->set_data($edition);

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/mod/mgm/index.php?editionedit=on');
} else if ($data = $mform->get_data()) {
    $newedition = new stdClass();
    $newedition->name = $data->name;
    $newedition->description = $data->description;
    $newedition->plazas = $data->plazas;
    $newedition->inicio = $data->inicio;
    $newedition->fin = $data->fin;

    if (isset($data->theme) && !empty($CFG->allowcategorythemes)) {
        $newcategory->theme = $data->theme;
    }

    if (isset($data->id)) {
        $newedition->id = $data->id;

        // Update an existing edition
        if (isset($data->acourses)) {
            // Add courses to edition
            foreach($data->acourses as $courseid) {
                mgm_add_course($edition, $courseid);
            }
        }

        if (isset($data->scourses)) {
            // Remove courses from edition
            foreach($data->scourses as $courseid) {
                mgm_remove_course($edition, $courseid);
            }
        }

        mgm_update_edition($newedition);
    } else {
        // Create a new Edition
        mgm_create_edition($newedition);
    }
    redirect('index.php');
}

// Print the form
$straddnewedition = get_string('addedicion', 'mgm');
$stradministration = get_string('administration');
$streditions = get_string('editions', 'mgm');

require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('edicionesmgmt', mgm_update_edition_button());
admin_externalpage_print_header();
print_heading($strtitle);
$mform->display();
admin_externalpage_print_footer();