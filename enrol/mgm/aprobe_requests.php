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
 * @package    enrol
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");

require_login();

require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

if (!$preinscripcion = get_records('edicion_preinscripcion')) {
    error(get_string('nohaydatos', 'mgm'));
}

$id = optional_param('id', 0, PARAM_INT);
$courseid = optional_param('courseid', 0, PARAM_INT);
$inscribe = optional_param('inscribe', false, PARAM_BOOL);
$force = optional_param('force', false, PARAM_BOOL);

// Editions
$editions = get_records('edicion');

if (isset($editions) && is_array($editions)) {
    foreach($editions as $edition) {
        // Check if user can see the edition.
        if (!mgm_can_do_view()) {
            continue;
        }

        $editiontable->data[] = array(
            '<a href="aprobe_requests.php?id='.$edition->id.'">'.$edition->name.'</a>',
            date('d/m/Y', $edition->inicio),
            date('d/m/Y', $edition->fin),
            mgm_count_courses($edition),
            mgm_get_edition_plazas($edition)
        );
    }
}

// Strings
$strmgm            = get_string('mgm', 'mgm');
$stredicion        = get_string('edicion', 'mgm');
$strediciones      = get_string('ediciones', 'mgm');
$stredicionesmgm   = get_string('edicionesmgmt', 'mgm');
$strplazas         = get_string('plazas', 'mgm');
$strfechainicio    = get_string('fechainicio', 'mgm');
$strfechafin       = get_string('fechafin', 'mgm');
$straddedicion     = get_string('addedicion', 'mgm');
$strasignado       = get_string('asignado', 'mgm');
$strsolicitudes    = get_string('solicitudes', 'mgm');
$strinscripcion    = get_string('fechainscripcion', 'mgm');
$strcc             = get_string('cc', 'mgm');
$strespecialidades = get_string('especialidades', 'mgm');
$stradministration = get_string('administration');
$strdescription    = get_string('description');
$strcourses        = get_string('courses');
$strname           = get_string('name');
$strlastname       = get_string('lastname');
$strselect         = get_string('select');
$strcourse         = get_string('course');
$stryes            = get_string('yes');
$strno             = get_string('no');
$strmatricular     = get_string('mgm:aprobe', 'mgm');
$strheading        = $strmatricular;
$strposalumno       = get_string('posalumno','mgm');

$navlinks = array();
$navlinks[] = array('name' => $strediciones, 'type' => 'misc');
$navlinks[] = array('name' => $strmatricular, 'link' => 'aprobe_requests.php', 'type' => 'misc');

// Table header
$editiontable->head  = array($stredicion, $strfechainicio, $strfechafin, $strcourses, $strplazas);
$editiontable->align = array('left', 'left', 'left', 'center', 'center', 'center');

if ($inscribe) {
    if (!$courseid || !$id) {
        error(get_string('nodata', 'mgm'));
        die();
    }

    if (!$force) {
        $users = array_keys($_REQUEST['users']);
    } else {
        $users = explode(',', $_REQUEST['users']);
    }

    // Check if users are <= than places
    $plazas = mgm_get_edition_course_criteria($id, $courseid)->plazas;
    if (count($users) > $plazas && !$force) {
        $a = new stdClass();
        $a->alumnos = count($users);
        $a->plazas = $plazas;

        $navlinks[] = array('name' => $edition->name, 'link' => 'aprobe_requests.php?id='.$edition->id, 'type' => 'misc');
        $navigation = build_navigation($navlinks);
        print_header($strmatricular, $strmatricular, $navigation);
        print_heading($strheading);

        notice_yesno(get_string('noplaces', 'mgm', $a),
                     '?id='.$id.'&courseid='.$courseid.'&inscribe=1&force=1&users='.implode(',', $users),
                     '?id='.$id.'&courseid='.$courseid);

        print_footer();
        die();
    }

    foreach($users as $user) {
        mgm_inscribe_user_in_edition($id, $user, $courseid);
    }

    mgm_enrol_edition_course($id, $courseid);

    redirect('aprobe_requests.php');
}

if ($id) {
    if ($edition = get_record('edicion', 'id', $id)) {
        $navlinks[] = array('name' => $edition->name, 'link' => 'aprobe_requests.php?id='.$edition->id, 'type' => 'misc');
        $strheading = $strheading.' '.$edition->name;

        // Table header
        $editiontable->head = array($strcourse, $strasignado, $strplazas, $strsolicitudes);
        $editiontable->align = array('left', 'center', 'center', 'center');

        // Table data
        unset($editiontable->data);
        foreach (mgm_get_edition_courses($edition) as $course) {
            $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
            	    WHERE edicionid='".$id."' AND value='".$course->id."'";
            if ($inscripcion = get_records_sql($sql)) {
                $asignado = $stryes;
                $link = '<b>'.$course->fullname.'</b>';
            } else {
                $asignado = $strno;
                $link = '<a href="aprobe_requests.php?id='.$edition->id.'&courseid='.$course->id.'">'.$course->fullname.'</a>';
            }
            if (!$inscripcion) {
                $plazas = mgm_get_edition_course_criteria($edition->id, $course->id)->plazas;
            } else {
                $plazas = count($inscripcion).'/'.mgm_get_edition_course_criteria($edition->id, $course->id)->plazas;
            }
            $editiontable->data[] = array(
            	$link,
                $asignado,
                $plazas,
                mgm_edition_get_solicitudes($edition, $course)
            );
        }
    }
}

if ($courseid) {
    if ($course = get_record('course', 'id', $courseid)) {
        $navlinks[] = array('name' => $course->fullname, 'type' => 'misc');
        $strheading = $strheading.' - '.$course->fullname;

        // Table header
        $editiontable->head = array($strposalumno,$strselect, $strname, $strlastname, $strinscripcion, $strcc, $strespecialidades, $strcourses);
        $editiontable->align = array('left','left', 'left', 'left', 'left', 'left', 'left', 'left');

        // Table data
        unset($editiontable->data);
        $editiontable->data = mgm_get_edition_course_preinscripcion_data($edition, $course);
    }
}

$navigation = build_navigation($navlinks);

print_header($strmatricular, $strmatricular, $navigation);
print_heading($strheading);

if ($courseid) {
    echo '<form name="perico" action="?id='.$id.'&courseid='.$courseid.'&inscribe=1" method="POST">';
    print_table($editiontable);
    echo '<br /><center><input type="submit" value="'.get_string('inscribe', 'mgm').'"/></center>';
    echo '</form>';
} else {
    print_table($editiontable);
}

print_footer();