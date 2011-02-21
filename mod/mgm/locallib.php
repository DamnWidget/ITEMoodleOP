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
 * Internal library of functions for module mgm
 *
 * All the mgm specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->dirroot.'/course/lib.php');

defined('MOODLE_INTERNAL') || die();

define('EDICIONES', $CFG->prefix.'ediciones');
define('EDICIONES_COURSE', $CFG->prefix.'ediciones_course');

define('MGM_CRITERIA_PLAZAS', 0);
define('MGM_CRITERIA_OPCION1', 1);
define('MGM_CRITERIA_OPCION2', 2);
define('MGM_CRITERIA_ESPECIALIDAD', 3);
define('MGM_CRITERIA_CC', 4);

define('MGM_ITE_ESPECIALIDADES', 1);
define('MGM_ITE_CENTROS', 2);

/**
 * Checks if an user can perform the view action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_view($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:viewedicion', $context));
}

/**
 * Checks if an user can perform the create action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_create($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:createedicion', $context));
}

/**
 * Checks if an user can perform the edit action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_edit($cm=0) {
    if ($cm) {
         $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:editedicion', $context));
}

/**
 * Checks if an user can perform the assigncriteria action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_assigncriteria($cm=0) {
    if ($cm) {
         $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:assigncriteria', $context));
}

/**
 * Checks if an user can perform the aprobe action on module access
 * @param object $cm
 * @return boolean
 */
function mgm_can_do_aprobe($cm=0) {
    if ($cm) {
        $context = get_context_instance(CONTEXT_MODULE, $cm->id);
    } else {
        $context = get_context_instance(CONTEXT_SYSTEM);
    }

    return (has_capability('mod/mgm:aprobe', $context));
}

/**
 * Prints the turn editing on/off button on mod/mgm/index.php
 *
 * @param integer $editionid The id of the edition we are showing or 0 for system context
 * @return string HTML of the editing button, or empty string if the user is not allowed to see it.
 */
function mgm_update_edition_button($editionid = 0) {
    global $CFG, $USER;

    // Check permissions.
    if (!mgm_can_do_create()) {
        return '';
    }

    // Work out the appropiate action.
    if (!empty($USER->editionediting)) {
        $label = get_string('turneditingoff');
        $edit = 'off';
    } else {
        $label = get_string('turneditingon');
        $edit = 'on';
    }

    // Generate the button HTML.
    $options = array('editionedit' => $edit, 'sesskey' => sesskey());
    if ($editionid) {
        $options['id'] = $editionid;
        $page = 'edition.php';
    } else {
        $page = 'index.php';
    }

    return print_single_button($CFG->wwwroot.'/mod/mgm/'.$page, $options, $label, 'get', '', true);
}

function mgm_get_edition_data($edition) {
    if (!is_object($edition)) {
        return NULL;
    }

    $fulledition = array(
        'edition'		 => $edition,
        'courses'	 	 => get_record('edicion_course', 'edicionid', $edition->id),
        'criteria'		 => get_record('edicion_criterios', 'edicion', $edition->id),
        'preinscripcion' => get_record('edicion_preinscripcion', 'edicionid', $edition->id),
        'inscripcion'	 => get_record('edicion_inscripcion', 'edicionid', $edition->id)
    );

    return $fulledition;
}

/**
 * Return the number of courses in an edition.
 *
 * @param object $edition
 * @return string The number of courses in an edition
 */
function mgm_count_courses($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    $sql = "SELECT COUNT(d.id) FROM ".$CFG->prefix."edicion_course d
    		WHERE d.edicionid = $edition->id";

    return get_field_sql($sql);
}

function mgm_get_edition_course($editionid, $courseid) {
    return get_record('edicion_course', 'edicionid', $editionid, 'courseid', $courseid);
}

/**
 * Return an edition HTML link
 * @param object $edition
 * @return string
 */
function mgm_get_edition_link($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    return '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$edition->id.'">'.$edition->name.'</a>';
}

/**
 * Return the edition's actions menu HTML code
 *
 * @param object $edition
 * @return string
 */
function mgm_get_edition_menu($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    $menu  = '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$edition->id.'"><img'.
             ' src="'.$CFG->pixpath.'/t/edit.gif" class="iconsmall" alt="'.get_string('edit').'" /></a>';
    $menu .= ' | ';
    $menu .= '<a title="'.get_string('delete').'" href="delete.php?id='.$edition->id.'">'.
             '<img src="'.$CFG->pixpath.'/t/delete.gif" class="iconsmall" alt="'.get_string('delete').'" /></a>';
    $menu .= ' | ';
    if (mgm_edition_is_active($edition)) {
        $menu .= '<a title="'.get_string('desactivar', 'mgm').'" href="active.php?id='.$edition->id.'">'.
             	 '<img src="'.$CFG->pixpath.'/t/stop.gif" class="iconsmall" alt="'.get_string('desactivar', 'mgm').'" /></a>';
    } else {
        $menu .= '<a title="'.get_string('activar', 'mgm').'" href="active.php?id='.$edition->id.'">'.
             	 '<img src="'.$CFG->pixpath.'/t/go.gif" class="iconsmall" alt="'.get_string('activar', 'mgm').'" /></a>';
    }

    return $menu;
}

function mgm_print_ediciones_list() {
    global $CFG;

    $editions = get_records('edicion');

    $editionimage = '<img src="'.$CFG->pixpath.'/i/db.gif" alt="" />';
    $courseimage = '<img src="'.$CFG->pixpath.'/i/course.gif" alt="" />';
    $table = '<table class="mod-mgm editionlist">';
    foreach($editions as $edition) {
        $table .= '<tr>';
        $table .= '<td valign="top" class="mod-mgm edition image">'.$editionimage.'</td>';
        $table .= '<td valign="top" class="mod-mgm edition name">';
        $table .= format_string($edition->name);
        $table .= '</td>';
        $table .= '<td class="mod-mgm edition info">&nbsp;</td>';
        if (mgm_count_courses($edition) > 0) {
            foreach(mgm_get_edition_courses($edition) as $course) {
                $table .= '<tr>';
                $table .= '<td valign="top" class="mod-mgm edition image course">'.$courseimage.'</td>';
                $table .= '<td valign="top" class="mod-mgm edition name course">';
                $table .= '<a class="mod-mgm edition link course" href="'.$CFG->wwwroot.'/mod/mgm/courses.php?courseid='.
                          $course->id.'&edicionid='.$edition->id.'">'.format_string($course->fullname).'</a>';
                if ($cr = mgm_exists_criteria_for_course($edition, $course)) {
                    $table .= ' <span style="font-size: 9pt;">(Criterios Fijados: '.$cr.' )</span>';
                } else {
                    $table .= ' <span style="font-size: 9pt;">(Criterios no Fijados)</span>';
                }
                $table .= '</td>';
                $table .= '<td class="mod-mgm course info">&nbsp;</td>';
                $table .= '</tr>';
            }
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    echo $table;
}

function mgm_print_whole_ediciones_list() {
    global $CFG;

    $editions = get_records('edicion');

    $editionimage = '<img src="'.$CFG->pixpath.'/i/db.gif" alt="" />';
    $courseimage = '<img src="'.$CFG->pixpath.'/i/course.gif" alt="" />';
    $table = '<table class="mod-mgm editionlist">';
    foreach($editions as $edition) {
        $table .= '<tr>';
        $table .= '<td valign="top" class="mod-mgm edition image">'.$editionimage.'</td>';
        $table .= '<td valign="top" class="mod-mgm edition name">';
        $table .= '<a class="mod-mgm edition link" href="'.$CFG->wwwroot.'/mod/mgm/view.php?id='.$edition->id.'">'.
                  format_string($edition->name).'</a>';
        $table .= '</td>';
        $table .= '<td class="mod-mgm edition info">&nbsp;</td>';
        if (mgm_count_courses($edition) > 0) {
            foreach(mgm_get_edition_courses($edition) as $course) {
                $table .= '<tr>';
                $table .= '<td valign="top" class="mod-mgm edition image course">'.$courseimage.'</td>';
                $table .= '<td valign="top" class="mod-mgm edition name course">';
                $table .= '<a class="mod-mgm edition link course" href="'.$CFG->wwwroot.'/course/view.php?id='.
                          $course->id.'">'.format_string($course->fullname).'</a>';
                $table .= '</td>';
                $table .= '<td class="mod-mgm course info">&nbsp;</td>';
                $table .= '</tr>';
            }
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

    echo $table;
}

function mgm_get_edition_courses($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return array();
    }

    $sql = "SELECT * FROM ".$CFG->prefix."course
    		WHERE id IN (
    			SELECT courseid FROM ".$CFG->prefix."edicion_course
    			WHERE edicionid=".$edition->id."
    	    )
    	    ORDER BY fullname;";

    if (!$courses = get_records_sql($sql)) {
        return array();
    }

    return $courses;
}

function mgm_get_edition_available_courses($edition) {
    global $CFG;
    require_once("$CFG->dirroot/enrol/enrol.class.php");

    $sql = "SELECT id, fullname, enrol FROM ".$CFG->prefix."course
			WHERE id NOT IN (
				SELECT courseid FROM ".$CFG->prefix."edicion_course
				WHERE edicionid = ".$edition->id."
			) AND id NOT IN (
				SELECT courseid FROM ".$CFG->prefix."edicion_course
			) AND id != '1'
			ORDER BY fullname";
    $courses = get_records_sql($sql);
    $ret = array();
    foreach ($courses as $course) {
        if(enrolment_factory::factory($course->enrol) instanceof enrolment_plugin_mgm) {
            $ret[] = $course;
        }
    }

    return $ret;
}

function mgm_add_course($edition, $courseid) {
    global $DB;

    if (!record_exists('edicion_course', 'edicionid', $edition->id, 'courseid', $courseid)) {
        $row = new stdClass();
        $row->edicionid = $edition->id;
        $row->courseid = $courseid;

        return insert_record('edicion_course', $row);
    }

    return false;
}

function mgm_remove_course($edition, $courseid) {
    if ($row = mgm_get_edition_course($edition->id, $courseid)) {
        delete_records('edicion_course', 'id', $row->id);

        return true;
    }

    return false;
}

function mgm_update_edition($edition) {
    $edition->timemodified = time();

    $result = update_record('edicion', $edition);

    if($result) {
        events_trigger('edition_updated', $edition);
    }

    return $result;
}

function mgm_create_edition($edition) {
    $edition->timecreated = time();

    $id = insert_record('edicion', $edition);

    if ($result) {
        events_trigger('edition_created', get_record('edicion', 'id', $result));
    }

    return $result;
}

function mgm_delete_edition($editionorid) {
    global $CFG;
    $result = true;

    if (is_object($editionorid)) {
        $editionid = $editionorid->id;
        $edition = $editionorid;
    } else {
        $editionid = $editionorid;
        if (!$edition = get_record('edition', 'id', $editionid)) {
            return false;
        }
    }

    if (!mgm_remove_edition_contents($editionid)) {
        $result = false;
    }

    if (!delete_records('edicion', 'id', $editionid)) {
        $result = false;
    }

    if ($result) {
        // trigger events
        events_trigger('edition_deleted', $edition);
    }
}

function mgm_remove_edition_contents($editionid) {
    if (!$editon = get_record('edicion', 'id', $editionid)) {
        error('Edition ID was incorrect (can\'t find it)');
    }

    delete_records('edicion_course', 'edicionid', $editionid);
    delete_records('edicion_criterios', 'edicion', $editionid);
    delete_records('edicion_inscripcion', 'edicionid', $editionid);
    delete_records('edicion_preinscripcion', 'edicionid', $editionid);

    return true;
}

function mgm_translate_especialidad($id) {
    global $CFG;

    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    return ($id !== false && $id != '') ? $especialidades[$id] : '';
}

function mgm_get_edition_course_criteria($editionid, $courseid) {
    global $CFG;

    $criteria = new stdClass();
    $criteria->plazas = 0;
    $criteria->espec = array();

    $sql = 'SELECT * FROM '.$CFG->prefix.'edicion_criterios
    		WHERE edicion = \''.$editionid.'\' AND course = \''.$courseid.'\'';
    if (!$cdata = get_records_sql($sql)) {
        return $criteria;
    }

    foreach($cdata as $c) {
        if ($c->type == MGM_CRITERIA_PLAZAS) {
            $criteria->plazas = $c->value;
        }

        // OBSOLETE v1.0
        /*if ($c->type == MGM_CRITERIA_CC) {
            $criteria->ccaas = $c->value;
        }*/

        if ($c->type == MGM_CRITERIA_OPCION1) {
            $criteria->opcion1 = $c->value;
        }

        if ($c->type == MGM_CRITERIA_OPCION2) {
            $criteria->opcion2 = $c->value;
        }

        if ($c->type == MGM_CRITERIA_ESPECIALIDAD) {
            $criteria->espec[$c->value] = mgm_translate_especialidad($c->value);
        }
    }

    return $criteria;
}

function mgm_set_edition_course_criteria($data) {
    global $CFG;

    $criteria = new stdClass();
    $criteria->edicion = $data->edicionid;
    $criteria->course = $data->courseid;

    // Plazas
    $criteria->type = MGM_CRITERIA_PLAZAS;
    $criteria->value = $data->plazas;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // CC
    // OBSOLETE v1.0
    /*$criteria->type = MGM_CRITERIA_CC;
    $criteria->value = $data->ccaas;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }*/

    // Opcion1
    $criteria->type = MGM_CRITERIA_OPCION1;
    $criteria->value = $data->opcion1;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // Opcion2
    $criteria->type = MGM_CRITERIA_OPCION2;
    $criteria->value = $data->opcion2;
    if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
        insert_record('edicion_criterios', $criteria);
    } else {
        $criteria->id = $criteriaid->id;
        update_record('edicion_criterios', $criteria);
        unset($criteria->id);
    }

    // Add especialidad
    if (isset($data->aespecs)) {
        foreach($data->aespecs as $k=>$v) {
            $criteria->type = MGM_CRITERIA_ESPECIALIDAD;
            $criteria->value = $v;
            if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
                insert_record('edicion_criterios', $criteria);
            } else {
                $criteria->id = $criteriaid->id;
                update_record('edicion_criterios', $criteria);
                unset($criteria->id);
            }
        }
    }

    // Remove especialidad
    if (isset($data->sespecs)) {
        foreach($data->sespecs as $k=>$v) {
            $criteria->type = MGM_CRITERIA_ESPECIALIDAD;
            $criteria->value = $v;
            if (!$criteriaid = mgm_edition_course_criteria_data_exists($criteria)) {
                continue;
            } else {
                delete_records('edicion_criterios', 'id', $criteriaid->id);
            }
        }
    }
}

function mgm_edition_course_criteria_data_exists($criteria) {
    global $CFG;

    if ($criteria->type !== MGM_CRITERIA_ESPECIALIDAD) {
        $sql = 'SELECT id FROM '.$CFG->prefix.'edicion_criterios
    			WHERE edicion = \''.$criteria->edicion.'\' AND course = \''.$criteria->course.'\'
    			AND type = \''.$criteria->type.'\'';
        if (!$value = get_record_sql($sql)) {
            return false;
        }
    } else {
        $sql = 'SELECT id FROM '.$CFG->prefix.'edicion_criterios
    		WHERE edicion = \''.$criteria->edicion.'\' AND course = \''.$criteria->course.'\'
    		AND type = \''.$criteria->type.'\' AND value = \''.$criteria->value.'\'';
        if (!$value = get_record_sql($sql)) {
            return false;
        }
    }

    return $value;
}

function mgm_get_edition_plazas($edition) {
    if (!is_object($edition)) {
        return '';
    }

    $plazas = 0;
    if (get_records('edicion_course', 'edicionid', $edition->id)) {
        foreach(get_records('edicion_course', 'edicionid', $edition->id) as $course) {
            if($criteria = mgm_get_edition_course_criteria($edition->id, $course->courseid)) {
                $plazas += $criteria->plazas;
            }
        }
    }

    return $plazas;
}

function mgm_get_course_especialidades($courseid, $editionid) {
    $criteria = mgm_get_edition_course_criteria($editionid, $courseid);

    return $criteria->espec;
}

function mgm_get_course_available_especialidades($courseid, $editionid) {
    global $CFG;

    $data = mgm_get_course_especialidades($courseid, $editionid);
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    $strdata = implode(',', $data);
    $filterespecialidades = array_filter($especialidades, create_function('$element',
        '$data = explode(",", "'.$strdata.'"); return (!in_array($element, $data));'));

    return $filterespecialidades;
}

/**
 * Returns the active edition if any, elsewhere returns false
 *
 * @return object bool
 */
function mgm_get_active_edition() {
    if (!$edition = get_record('edicion', 'active', 1)) {
        return false;
    }

    return $edition;
}

/**
 * Returns true if edition is the active one otherwise returns false
 * @param object $edition
 * @return bool
 */
function mgm_edition_is_active($edition) {
    return ($edition->active) ? true : false;
}

/**
 * Return the course's edition (Edition will be active)
 *
 * @param string $id
 * @return null object The edition object
 */
function mgm_get_course_edition($id) {
    if (!$row = get_record('edicion_course', 'courseid', $id)) {
        return null;
    }

    if (!$edition = get_record('edicion', 'id', $row->edicionid)) {
        return null;
    }

    if (!$edition->active) {
        return null;
    }

    return $edition;
}

function mgm_get_edition_user_options($edition, $user) {
    global $CFG;
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition."' AND userid = '".$user."'";

    if (!$data = get_record_sql($sql)) {
        return false;
    } else {
        $data = $data->value;
    }
    $choices = array();
    $options = explode(',', $data);
    foreach ($options as $option) {
        $choices[] = $option;
    }

    return $choices;
}

function mgm_preinscribe_user_in_edition($edition, $user, $courses) {
    $rcourses = array();
    foreach ($courses as $course) {
        if ($course) {
            $rcourses[] = $course;
        }
    }

    if (!count($rcourses)) {
        delete_records('edicion_preinscripcion', 'edicionid', $edition, 'userid', $user);
        return;
    }

    $strcourses = implode(',', $rcourses);

    if (!$record = get_record('edicion_preinscripcion', 'edicionid', $edition, 'userid', $user)) {
        // New record
        $record = new stdClass();
        $record->edicionid = $edition;
        $record->userid = $user;
        $record->value = $strcourses;
        $record->timemodified = time();
        insert_record('edicion_preinscripcion', $record);
    } else {
        // Update record
        $record->value = $strcourses;
        $record->timemodified = time();
        update_record('edicion_preinscripcion', $record);
    }
}

/**
 * Inscribe an user into an edition
 * @param string $edition
 * @param string $user
 * @param string $course
 */
function mgm_inscribe_user_in_edition($edition, $user, $course) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$edition."' AND userid='".$user."'";
    if (!$record = get_record_sql($sql)) {
        // New record
        $record = new stdClass();
        $record->edicionid = $edition;
        $record->userid = $user;
        $record->value = $course;
        insert_record('edicion_inscripcion', $record);
    } else {
        // Update record
        $record->value = $course;
        update_record('edicion_inscripcion', $record);
    }
}

function mgm_enrol_edition_course($editionid, $courseid) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$editionid."' AND value='".$courseid."'";
    if ($data = get_records_sql($sql)) {
        $course = get_record('course', 'id', $courseid);
        foreach($data as $row) {
            $user = get_record('user', 'id', $row->userid);
            if (!enrol_into_course($course, $user, 'mgm')) {
                print_error('couldnotassignrole');
            }

            // Delete user preinscriptions
            //delete_records('edicion_preinscripcion', 'userid', $user->id);
        }
    }
}

function mgm_check_already_enroled($editionid, $courseid) {
    global $CFG;

    $sql = "SELECT * FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid='".$editionid."' AND value='".$courseid."'";
    return get_records_sql($sql);
}

function mgm_edition_get_solicitudes($edition, $course) {
    global $CFG;

    $ret = 0;
    if ($records = get_records('edicion_preinscripcion', 'edicionid', $edition->id)) {
        foreach($records as $record) {
            $solicitudes = explode(",", $record->value);

            $ret += count(array_filter($solicitudes, create_function('$element',
                'return ($element == '.$course->id.');')));
        }
    }

    return $ret;
}

/**
 * Return true if the given course of given edition has criteria set
 * @param object $edition
 * @param object $course
 * @return boolean
 */
function mgm_exists_criteria_for_course($edition, $course) {
    // Local variables
    $ret = false;
    $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);

    if (isset($criteria->opcion1)) {
        $ret = $criteria->opcion1.', '.$criteria->opcion2;
    }

    return $ret;
}

/**
 * Return the user preinscription data as array
 * @param object $line
 * @return array
 */
function mgm_get_user_preinscription_data($line, $edition, $data) {
    $site = get_site();
    $user = $data->user;
    $userdata = $data->userdata;
    $especs = ($data->especs) ? $data->especs : array();
    $userespecs = '<select name="especialidades" readonly="">';
    foreach ($especs as $espec) {
        $userespecs .= '<option name="'.$espec.'">'.mgm_translate_especialidad($espec).'</option>';
    }
    $userespecs .= '</select>';
    $courses = '<select name="courses" readonly="">';
    $values = explode(',', $line->value);
    $realcourses = array();
    for ($i = 0; $i < count($values); $i++) {
        if (mgm_check_already_enroled($edition->id, $values[$i])) {
            continue;
        }
        $realcourses[] = $values[$i];
    }

    foreach($values as $courseid) {
        $ncourse = get_record('course', 'id', $courseid);
        $courses .= '<option name="'.$courseid.'">'.$ncourse->fullname.'</option>';
    }
    $courses .= '</select>';
    $check = '<input type="checkbox" name="users['.$line->userid.']" />';
    $tmpdata = array(
        $check,
        '<a href="../../user/view.php?id='.$line->userid.'&amp;course='.$site->id.'">'.$user->firstname.'</a>',
        $user->lastname,
        date("d/m/Y H:i\"s", $line->timemodified),
        ($userdata) ? $userdata->cc : '',
        $userespecs,
        $courses
    );

    return $tmpdata;
}

/**
 * Reorder by course
 * @param array $file
 * @param object $course
 */
function mgm_order_by_course_preinscription_data(&$data, $course) {
    // Local variables
    $_data = array();

    if (!isset($data)) {
        return $_data;
    }

    foreach ($data as $line) {
        foreach ($line->realcourses as $k=>$v) {
            if ($course->id == $v) {
                if (!isset($_data[$k])) {
                    $_data[$k] = array();
                }

                $_data[$k][] = $line;
            }
        }
    }

    $data = $_data;
}

/**
 * Return not already enroled courses
 * @param string $editionid
 * @param string $value
 */
function mgm_get_user_preinscription_realcourses($editionid, $value) {
    $values = explode(',', $value);
    $realcourses = array();
    for ($i = 0; $i < count($values); $i++) {
        if (mgm_check_already_enroled($editionid, $values[$i])) {
            continue;
        }
        $realcourses[] = $values[$i];
    }

    return $realcourses;
}

function mgm_user_preinscription_tmpdata($userid) {
    if (!$user = get_record('user', 'id', $userid)) {
        return null;
    }

    $tmpuser = new stdClass();
    $tmpuser->user = $user;
    $tmpuser->userdata = get_record('edicion_user', 'userid', $user->id);
    $tmpuser->especs = ($tmpuser->userdata) ? explode("\n", $tmpuser->userdata->especialidades) : array();

    return $tmpuser;
}

function mgm_parse_preinscription_data($edition, $course, $data) {
    // Local variables
    $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);
    $retdata = array();

    foreach ($data as $sqline) {
        $lineuser = mgm_user_preinscription_tmpdata($sqline->userid);
        $lineuser->realcourses = mgm_get_user_preinscription_realcourses($edition->id, $sqline->value);
        $lineuser->tmpdata = mgm_get_user_preinscription_data($sqline, $edition, $lineuser);
        $lineuser->sqline = $sqline;
        $lineuser->data = array(
        	'opcion1' => array(
                'especialidades' => array(
                    'found'	=> false,
                    'data' => array()
                ),
                'centros' => array(
                    'found'	=> false,
                    'data' => array(
                        'found' => array(),
                        'notfound' => array()
                    )
                )
            ),
            'opcion2' => array(
                'especialidades' => array(
                    'found'	=> false,
                    'data' => array()
                ),
                'centros' => array(
                    'found'	=> false,
                    'data' => array(
                        'found' => array(),
                        'notfound' => array()
                    )
                )
            )
        );

        // Store data by course at first option or not
        if ($course->id == $lineuser->realcourses[0]) {
            $retdata['first'][] = $lineuser;
        } else {
            $retdata['last'][] = $lineuser;
        }
    }

    /*
     * Now we have all the data splited into those ones who choosed the course as first option at
     * $first and everyone else at $last. We are going to proccess and order the data on every one
     * of those arrays before return it.
     */
    $rlastdata = array();
    if (isset($criteria->opcion1)) {    // This course is configured with criteria options
        mgm_order_preinscription_first_data($retdata['first'], $criteria, $edition, $course);
        mgm_order_by_course_preinscription_data($retdata['last'], $course);
        if (isset($retdata['last'])) {
            foreach ($retdata['last'] as $rdata) {
                mgm_order_preinscription_last_data($rdata, $edition, $criteria, $course);
                foreach ($rdata as $rd) {
                    $rlastdata[] = $rd;
                }
            }
        }
    } else {    // This course is not configured with criteria options
        foreach ($retdata as $key=>$value) {
            if ($key == 'first') {
                foreach ($value as $k=>$v) {
                    $retdata[$key][$k] = $v->tmpdata;
                }
            } else {
                foreach ($value as $k=>$v) {
                    $rlastdata[$k] = $v->tmpdata;
                }
            }
        }
    }

    $tmpdata = array();
    if (isset($retdata['first'])) {
        foreach ($retdata['first'] as $data) {
            $tmpdata[] = $data;
        }
    }

    foreach ($rlastdata as $data) {
        $tmpdata[] = $data;
    }

    return $tmpdata;
}

function mgm_order_preinscription_first_data_opcion($opcion, &$data, $criteria, $edition, $course) {
    if (!isset($data)) {
        return;
    }
    foreach ($data as $linedata) {
        if ($criteria->$opcion == 'especialidades') {
            // First option is especialidades
            $linedata->data[$opcion]['especialidades']['found'] = true;
            $kets = array_keys($criteria->espec);
            $found = false;
            // Each espec on course criteria
            foreach($kets as $key=>$value) {
                // Each espec on user criteria
                foreach ($linedata->especs as $k=>$v) {
                    // Match
                    if ($v !== '' && $value == $v) {
                        $linedata->data[$opcion]['especialidades']['data'][$key][$k] = $linedata;
                        $found = true;
                    }
                }
            }
            // If criteria does not match just store it in date order
            if (!$found) {
                $linedata->data[$opcion]['especialidades']['data']['notfound'][] = $linedata;
            }
        } else if($criteria->$opcion == 'centros') {
            // First option is centros
            $linedata->data[$opcion]['centros']['found'] = true;

            if (!empty($linedata->userdata) && mgm_is_cc_on_csv($linedata->userdata->cc)) {
                $linedata->data[$opcion]['centros']['data']['found'] = $linedata;
            } else {
                $linedata->data[$opcion]['centros']['data']['notfound'] = $linedata;
            }
        } else if ($criteria->$opcion == 'ninguna') {
            $linedata->data[$opcion]['ninguna']['found'] = true;
            $linedata->data[$opcion]['ninguna']['data'] = $linedata;
        }
    }
}

function mgm_abstract_results_opcion($opcion, $data) {
    // Local variables
    $tmptdata = array();

    if (!isset($data)) {
        return $tmptdata;
    }

    foreach ($data as $linedata) {
        // If especialidades is the first option
        if ($linedata->data[$opcion]['especialidades']['found']) {
            foreach ($linedata->data[$opcion]['especialidades']['data'] as $key=>$value) {
                foreach ($value as $k=>$v) {
                    $vdata = new stdClass();
                    $vdata->sqline = $v->sqline;
                    $vdata->data = $v->tmpdata;
                    if ($key !== 'notfound') {
                        $tmptdata['found'][$k][] = $vdata;
                    } else {
                        $tmptdata['notfound'][] = $v;
                    }
                }
            }
        } else if ($linedata->data[$opcion]['centros']['found']) {
            foreach ($linedata->data[$opcion]['centros']['data'] as $key=>$value) {
                if (empty($value)) continue;
                $vdata = new stdClass();
                $vdata->sqline = $value->sqline;
                $vdata->data = $value->tmpdata;
                if ($key !== 'notfound') {
                    $tmptdata[$key][0][] = $vdata;
                } else {
                    $tmptdata[$key][] = $value;
                }
            }
        } else if ($linedata->data[$opcion]['ninguna']['found']) {
            $vdata = new stdClass();
            $vdata->sqline = $linedata->data[$opcion]['ninguna']['data']->sqline;
            $vdata->data = $linedata->data[$opcion]['ninguna']['data']->tmpdata;
            $tmptdata['nocriteria'][0][] = $vdata;
        }
    }

    return $tmptdata;
}

function mgm_order_preinscription_first_data(&$data, $criteria, $edition, $course) {
    // Local variables
    $firstdata = $tmptdata = $founddata = $nfdata = $nffounddata = $nfnfdata = $ncdata = $finaldata = array();

    // First pass
    mgm_order_preinscription_first_data_opcion('opcion1', $data, $criteria, $edition, $course);

    // Abstract the opcion1 results
    $tmptdata = mgm_abstract_results_opcion('opcion1', $data);

    $founddata = (array_key_exists('found', $tmptdata)) ? $tmptdata['found'] : array();

    // Seccond pass (Only for not found)
    if (isset($tmptdata['notfound'])) {
        mgm_order_preinscription_first_data_opcion('opcion2', $tmptdata['notfound'], $criteria, $edition, $course);
        $nfdata = mgm_abstract_results_opcion('opcion2', $tmptdata['notfound']);
        if (array_key_exists('found', $nfdata)) {
            foreach ($nfdata['found'] as $nff) {
                $nffounddata[] = $nff;
            }

            // Order data by date
            foreach ($nffounddata as $nff) {
                usort($nff, 'mgm_order_by_date');
            }
        }

        if (array_key_exists('notfound', $nfdata)) {
            foreach ($nfdata['notfound'] as $nfnf) {
                $nfnfdata[] = $nfnf;
            }

            // Order data by date
            usort($nfnfdata, 'mgm_order_by_date');
        }

        if (array_key_exists('nocriteria', $nfdata)) {
            foreach ($nfdata['nocriteria'] as $ncd) {
                $ncdata[] = $ncd;
            }

            // Order data by date
            foreach ($ncdata as $ncd) {
                usort($ncd, 'mgm_order_by_date');
            }
        }
    }

    if (array_key_exists('nocriteria', $tmptdata)) {
        $ncdata = $tmptdata['nocriteria'];
    }

    // Order data by date
    foreach ($founddata as $found) {
        usort($found, 'mgm_order_by_date');
    }

    /**
     * Create tha final and valid array
     */
    foreach ($founddata as $found) {
        foreach ($found as $finalfound) {
            $finaldata[] = $finalfound->data;
        }
    }

    if (!empty($nffounddata)) {
        foreach ($nffounddata as $nffound) {
            foreach ($nffound as $nff) {
                $finaldata[] = $nff->data;
            }
        }
    }

    if (!empty($nfnfdata)) {
        foreach ($nfnfdata as $nfnf) {
            $finaldata[] = $nfnf->tmpdata;
        }
    }

    if (!empty($ncdata)) {
        foreach ($ncdata as $ncd) {
            foreach ($ncd as $nc) {
                $finaldata[] = $nc->data;
            }
        }
    }

    $data = $finaldata;
}

function mgm_order_preinscription_last_data(&$data, $edition, $criteria, $course) {
    // Local variables
    $firstdata = $tmptdata = $founddata = $nfdata = $nffounddata = $nfnfdata = $ncdata = $finaldata = array();

    // First pass
    mgm_order_preinscription_first_data_opcion('opcion1', $data, $criteria, $edition, $course);

    // Abstract the opcion1 results
    $tmptdata = mgm_abstract_results_opcion('opcion1', $data);

    $founddata = (array_key_exists('found', $tmptdata)) ? $tmptdata['found'] : array();

    // Seccond pass (Only for not found)
    if (isset($tmptdata['notfound'])) {
        mgm_order_preinscription_first_data_opcion('opcion2', $tmptdata['notfound'], $criteria, $edition, $course);
        $nfdata = mgm_abstract_results_opcion('opcion2', $tmptdata['notfound']);
        if (array_key_exists('found', $nfdata)) {
            foreach ($nfdata['found'] as $nff) {
                $nffounddata[] = $nff;
            }

            // Order data by date
            foreach ($nffounddata as $nff) {
                usort($nff, 'mgm_order_by_date');
            }
        }

        if (array_key_exists('notfound', $nfdata)) {
            foreach ($nfdata['notfound'] as $nfnf) {
                $nfnfdata[] = $nfnf;
            }

            // Order data by date
            usort($nfnfdata, 'mgm_order_by_date');
        }

        if (array_key_exists('nocriteria', $nfdata)) {
            foreach ($nfdata['nocriteria'] as $ncd) {
                $ncdata[] = $ncd;
            }

            // Order data by date
            foreach ($ncdata as $ncd) {
                usort($ncd, 'mgm_order_by_date');
            }
        }
    }

    if (array_key_exists('nocriteria', $tmptdata)) {
        $ncdata = $tmptdata['nocriteria'];
    }

    // Order data by date
    foreach ($founddata as $found) {
        usort($found, 'mgm_order_by_date');
    }

    /**
     * Create tha final and valid array
     */
    foreach ($founddata as $found) {
        foreach ($found as $finalfound) {
            $finaldata[] = $finalfound->data;
        }
    }

    if (!empty($nffounddata)) {
        foreach ($nffounddata as $nffound) {
            foreach ($nffound as $nff) {
                $finaldata[] = $nff->data;
            }
        }
    }

    if (!empty($nfnfdata)) {
        foreach ($nfnfdata as $nfnf) {
            $finaldata[] = $nfnf->tmpdata;
        }
    }

    if (!empty($ncdata)) {
        foreach ($ncdata as $ncd) {
            foreach ($ncd as $nc) {
                $finaldata[] = $nc->data;
            }
        }
    }

    $data = $finaldata;
}

function mgm_order_by_date($x, $y) {
    if ($x->sqline->timemodified == $y->sqline->timemodified) {
        return 0;
    }
    return ($x->sqline->timemodified < $y->sqline->timemodified) ? -1 : 1;
}

/**
 * Get edition preinscrition data and return it
 *
 * @param object $edition
 * @param object $course
 * @param boolean $docheck
 */
function mgm_get_edition_course_preinscripcion_data($edition, $course, $docheck=true) {
    global $CFG;

    // Preinscripcion date first
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition->id."' AND
    		userid NOT IN (select userid FROM ".$CFG->prefix."edicion_inscripcion
    		WHERE edicionid = '".$edition->id."') ORDER BY timemodified ASC";
    if (!$preinscripcion = get_records_sql($sql)) {
        return;
    }

    $final = array();
    foreach ($preinscripcion as $data) {
        $courses = explode(",", $data->value);
        if (in_array($course->id, $courses)) {
            $final[] = $data;
        }
    }

    if (!count($final)) {
        return;
    }

    $data = mgm_parse_preinscription_data($edition, $course, $final);

    if ($docheck) {
        $criteria = mgm_get_edition_course_criteria($edition->id, $course->id);
        $asigned = 0;
        foreach ($data as $k=>$row) {
            $arr = explode('"', $row[0]);
            $userid = $arr[3];
            $check = '<input type="checkbox" name="'.$userid.'" checked="true"/>';

            if ($criteria->plazas > $asigned || $criteria->plazas == 0) {
                $data[$k][0] = $check;
                $asigned++;
            }
        }
    }

    $count = 1;
    foreach ($data as $k=>$row) {
      $r = $row;
      array_unshift($r,$count);
      $data[$k] = $r;
      $count++;
    }

    return $data;
}

/**
 * Return the user's CC
 * @param string $userid
 * @return string
 */
function mgm_get_user_cc($userid) {
    if ($cc = get_record('edicion_user', 'userid', $userid)) {
        return $cc->cc;
    }

    return '';
}

function mgm_get_user_especialidades($userid) {
    if ($especialidades = get_record('edicion_user', 'userid', $userid)) {
        $especs = array();
        foreach (explode("\n", $especialidades->especialidades) as $espec) {
            $especs[$espec] = mgm_translate_especialidad($espec);
        }

        return $especs;
    }

    return array();
}

function mgm_get_user_available_especialidades($userid) {
    global $CFG;

    $data = mgm_get_user_especialidades($userid);
    $sql = "SELECT value FROM ".$CFG->prefix."edicion_ite
    		WHERE type = ".MGM_ITE_ESPECIALIDADES."";
    $especialidades = explode("\n", get_record_sql($sql)->value);

    $strdata = implode(',', $data);
    $filterespecialidades = array_filter($especialidades, create_function('$element',
        '$data = explode(",", "'.$strdata.'"); return (!in_array($element, $data));'));

    return $filterespecialidades;
}

function mgm_set_userdata($userid, $data) {
    $newdata = new stdClass();
    $newdata->cc = $data->cc;
    $newdata->userid = $userid;
    if (!record_exists('edicion_user', 'userid', $userid)) {
        if (isset($data->addsel)) {
             $newdata->especialidades = implode("\n", $data->aespecs);
        } else {
            $newdata->espcialidades = "";
        }
        insert_record('edicion_user', $newdata);
    } else {
        $olddata = get_record('edicion_user', 'userid', $userid);
        $newdata->id = $olddata->id;
        if (isset($data->addsel)) {
            $oldespec = explode("\n", $olddata->especialidades);
            $newespec = array_merge($oldespec, $data->aespecs);
            $newdata->especialidades = implode("\n", $newespec);
        } else if (isset($data->removesel)) {
            $oldespec = explode("\n", $olddata->especialidades);
            foreach ($data->sespecs as $k=>$v) {
                if (in_array($v, $oldespec)) {
                    $idx = array_search($v, $oldespec);
                    unset($oldespec[array_search($v, $oldespec)]);
                }
            }
            $newespec = implode("\n", $oldespec);
            $newdata->especialidades = $newespec;
        } else {
            $newdata->especialidades = $olddata->especialidades;
        }

        update_record('edicion_user', $newdata);
    }
}


/**
 * Return if a given cc is on cc CSV file
 * @param string $cc
 * @return boolean
 */
function mgm_is_cc_on_csv($cc) {
    foreach (mgm_get_cc_data() as $ccdata) {
        if ($ccdata[5] == $cc) {
            return true;
        }
    }

    return false;
}

/**
 * Return an array with the cc data on CSV file
 * @return array
 */
function mgm_get_cc_data() {
    global $CFG;

    $csvdata = array();
    if (($gestor = fopen($CFG->mgm_centros_file, "r")) !== FALSE) {
        while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
            $csvdata[] = $datos;
        }
        fclose($gestor);
    }

    return $csvdata;
}

/**
 * Activate an edition
 * @param object $edition
 */
function mgm_active_edition($edition) {
    if ($aedition = mgm_get_active_edition()) {
        mgm_deactive_edition($aedition);
    }

    $edition->active = 1;
    update_record('edicion', $edition);
}

/**
 * Deactivate an edition
 * @param unknown_type $edition
 */
function mgm_deactive_edition($edition) {
    $edition->active = 0;
    update_record('edicion', $edition);
}

/**
 * Get preinscription uer timemodified data
 * @param string $edition
 * @param string $user
 */
function mgm_get_preinscription_timemodified($edition, $user) {
    global $CFG;

    $sql = "SELECT timemodified FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition."' AND userid = '".$user."'";

    if (!$record = get_record_sql($sql)) {
        return null;
    }

    return $record;
}