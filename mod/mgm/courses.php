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
 * Course assignments
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/adodb/adodb-csvlib.inc.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/course_edit_form.php');

require_login();
require_capability('mod/mgm:editedicion', get_context_instance(CONTEXT_SYSTEM));

$id = optional_param('id', 0, PARAM_INT);    // Criteria id
$courseid = optional_param('courseid', 0, PARAM_INT);
$edicionid = optional_param('edicionid', 0, PARAM_INT);

if ($courseid) {
    if (!$course = get_record('course', 'id', $courseid)) {
        error('Course not known');
    }
}

if ($edicionid) {
    if (!$edition = get_record('edicion', 'id', $edicionid)) {
        error('Edicion not known');
    }
}

if ($id) {
    if (!$criteria = get_record('edicion_criterios', 'id', $id)) {
        error('Criteria not known!');
    }
}

if (isset($course) && isset($edition)) {
    $selectedespecs = mgm_get_course_especialidades($course->id, $edition->id);
    $allespecs = mgm_get_course_available_especialidades($course->id, $edition->id);
} else {
    print_edition_edit_header();
    print_heading(get_string('edicioncriteria', 'mgm'));
    echo skip_main_destination();
    print_box_start('edicionesbox');
    mgm_print_ediciones_list();
    print_box_end();
    admin_externalpage_print_footer();
    die();
}

$aespecs = $sespecs = array();

if (!empty($selectedespecs)) {
    $sespecs = $selectedespecs;
}

if (!empty($allespecs)) {
    $aespecs = $allespecs;
}

$dependencias = mgm_get_courses($course);

$criteria = mgm_get_edition_course_criteria($edicionid, $courseid);
if (!isset($criteria->depends)) {
    $criteria->depends = 0;
}
$criteria->courseid = $courseid;
$criteria->edicionid = $edicionid;
$criteria->sespecs = $sespecs;
$criteria->aespecs = $aespecs;
$criteria->dependencias = $dependencias;

$mform = new mgm_course_edit_form('courses.php', $criteria);
$mform->set_data($criteria);

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot.'/mod/mgm/courses.php');
} else if ($data = $mform->get_data()) {
    if ($data->opcion1 == $data->opcion2 && $data->opcion1 != 'ninguna') {
        error(get_string('opcionesduplicadas', 'mgm'), 'courses.php?courseid='.$courseid.'&edicionid='.$edicionid);
    }
    mgm_set_edition_course_criteria($data);
    redirect('courses.php?courseid='.$data->courseid.'&edicionid='.$data->edicionid);
}

// Print the form
print_edition_edit_header();
print_heading(get_string('edicioncriteria', 'mgm').' - '.$course->fullname);
echo skip_main_destination();
$mform->display();
?>
<script type="text/javascript">
var dcheck = document.getElementById('id_dcheck');
var dlist = document.getElementById('id_dpendsgroup_dlist');
<?php if ($criteria->depends) {
    echo "dcheck.checked = true;";
    echo "dlist.selectedIndex = ".mgm_get_check_index($criteria);
} else {
    echo "dcheck.checked = false;";
}
?>
</script>
<?php
admin_externalpage_print_footer();

function print_edition_edit_header() {
    global $CFG;
    require_once($CFG->libdir.'/adminlib.php');

    admin_externalpage_setup('edicionescoursemgmt');
    admin_externalpage_print_header();
}