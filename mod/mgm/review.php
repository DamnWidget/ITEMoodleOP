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
 * @copyright  2010 - 2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");

require_login();

require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

$id = optional_param('id', 0, PARAM_INT);    // Edition id

if (!$site = get_site()) {
    error('Site isn\'t defined!');
}

// Strings
$strmgm            = get_string('mgm', 'mgm');
$stredicion        = get_string('edicion', 'mgm');
$strediciones      = get_string('ediciones', 'mgm');
$stralumnos        = get_string('alumnos', 'mgm');
$stredicionesmgm   = get_string('reviewnotaprobed', 'mgm');
$strplazas         = get_string('plazas', 'mgm');
$strfechainicio    = get_string('fechainicio', 'mgm');
$strfechafin       = get_string('fechafin', 'mgm');
$straddedicion     = get_string('addedicion', 'mgm');
$stradministration = get_string('administration');
$strdescription    = get_string('description');
$strcourses        = get_string('courses');
$strout            = get_string('out', 'mgm');
$stryes            = get_string('yes');
$strno             = get_string('no');


// Editions
$editions = get_records('edicion');

// Print the page and form
$strgroups = get_string('groups');
$strparticipants = get_string('participants');
$stradduserstogroup = get_string('adduserstogroup', 'group');
$strusergroupmembership = get_string('usergroupmembership', 'group');


// Navigation links
$navlinks = array();
$navlinks[] = array('name' => $stradministration, 'link' => '', 'type' => 'misc');
$navlinks[] = array('name' => $strediciones, 'link' => 'review.php', 'type' => 'misc');
$navlinks[] = array('name' => $stredicionesmgm, 'link' => '', 'type' => 'activity');
$navigation = build_navigation($navlinks);

print_header($site->shortname.': '.$strmgm, $stredicionesmgm, build_navigation($navlinks),
             '', '', true);

if ($id) {
    $edition = get_record('edicion', 'id', $id);

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid='".$edition->id."' AND userid
    		IN ( SELECT userid FROM ".$CFG->prefix."edicion_preinscripcion
    			 WHERE edicionid='".$edition->id."' ) AND userid
    		NOT IN ( SELECT userid FROM ".$CFG->prefix."edicion_inscripcion
    				 WHERE edicionid='".$edition->id."' )";
    $rows = get_records_sql($sql);
    $alumnos = array();
    foreach ($rows as $row) {
        $alumno = get_record('user', 'id', $row->userid);
        $record = new object();
        $record->id = $alumno->id;
        $record->nombre = $alumno->firstname.' '.$alumno->lastname;
        $record->correo = $alumno->email;
        $sql2 = "SELECT * FROM ".$CFG->prefix."course
        		 WHERE id IN (".$row->value.")";
        $courses = get_records_sql($sql2);
        $record->cursos = $courses;
        foreach ($record->cursos as $curso) {
            $criteria = mgm_get_edition_course_criteria($edition->id, $curso->id);
            $curso->plazas = $criteria->plazas;
        }
        if($alumnodata = get_record('edicion_user', 'userid', $alumno->id)) {
            $record->dni = $alumnodata->dni;
            $record->cc = $alumnodata->cc;
            $record->especialidades = explode("\n", $alumnodata->especialidades);
        } else {
            $record->dni = '00000000H';
            $record->cc = 0;
            $record->especialidades = array();
        }

        $record->fecha = $row->timemodified;
        $alumnos[] = $record;
    }

    // Table data
    foreach($alumnos as $alumno) {
        // Especialidades
        $especs = $alumno->especialidades;
        $userespecs = '<select name="especialidades" readonly="">';
        foreach ($especs as $espec) {
            $userespecs .= '<option name="'.$espec.'">'.mgm_translate_especialidad($espec).'</option>';
        }
        $userespecs .= '</select>';

        // Courses
        $courses = '<select name="courses" readonly="">';
        foreach($alumno->cursos as $course) {
            $courses .= '<option name="'.$course->id.'">'.$course->fullname.'</option>';
        }
        $courses .= '</select>';

        $alumnostable->data[] = array(
            '<a href="../../user/view.php?id='.$alumno->id.'&amp;course='.$site->id.'">'.$alumno->nombre.'</a>',
            '<a href="mailto:'.$alumno->correo.'">'.$alumno->correo.'</a>',
            $record->dni,
            $record->cc,
            (empty($alumno->especialidades)) ? get_string('sinespecialidades', 'mgm') : $userespecs,
            $courses,
            date("d/m/Y H:i\"s", $alumno->fecha),
        );
    }

    // Table header
    $alumnostable->head = array(get_string('name'), get_string('configsectionmail', 'admin'), get_string('dni', 'mgm'), get_string('cc', 'mgm'), get_string('especialidades', 'mgm'), get_string('courses'), get_string('date'));
    $alumnostable->align = array('left', 'left', 'left', 'left', 'left', 'left', 'left');
} else {
    if (isset($editions) && is_array($editions)) {
        foreach($editions as $edition) {
            // Check if user can see the edition.
            if (!mgm_can_do_view()) {
                continue;
            }

            $editiontable->data[] = array(
            	'<a title="'.$edition->description.'" href="review.php?id='.$edition->id.'">'.$edition->name.'</a>',
                date('d/m/Y', $edition->inicio),
                date('d/m/Y', $edition->fin),
                mgm_count_courses($edition),
                mgm_get_edition_plazas($edition),
                mgm_get_edition_out($edition)
            );
        }
    }

    // Table header
    $editiontable->head  = array($stredicion, $strfechainicio, $strfechafin, $strcourses, $strplazas, $strout);
    $editiontable->align = array('left', 'left', 'left', 'center', 'center', 'center');
}

// Output the page

if (isset($editiontable)) {
    print_heading($strediciones);
    print_table($editiontable);
} else {
    print_heading($stralumnos);
    print_table($alumnostable);
}

print_footer();