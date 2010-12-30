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

define('MGM_ITE_ESPECIALIDADES', 1);
define('MGM_ITE_CENTROS', 2);

if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    define('MGM_PHP_INLINE', 0);
} else {
    define('MGM_PHP_INLINE', 1);
}

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

    $sql = "SELECT COUNT(d.id)
    			FROM {$CFG->prefix}edicion_course d
    			WHERE d.edicionid = $edition->id";

    return get_field_sql($sql);
}

function mgm_get_edition_course($editionid, $courseid) {
    return get_record('edicion_course', 'edicionid', $editionid, 'courseid', $courseid);
}

function mgm_get_edition_link($edition) {
    global $CFG;

    if (!is_object($edition)) {
        return '';
    }

    return '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$edition->id.'">'.$edition->name.'</a>';
}

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
    	    );";

    $courses = get_records_sql($sql);

    return $courses;
}

function mgm_get_edition_available_courses($edition) {
    global $CFG;

    $sql = "SELECT id, fullname FROM ".$CFG->prefix."course
			WHERE id NOT IN (
				SELECT courseid FROM ".$CFG->prefix."edicion_course
				WHERE edicionid = ".$edition->id."
			) AND id != '1'";
    $courses = get_records_sql($sql);

    return $courses;
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

    return $especialidades[$id];
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
    foreach(get_records('edicion_course', 'edicionid', $edition->id) as $course) {
        if($criteria = mgm_get_edition_course_criteria($edition->id, $course->courseid)) {
            $plazas += $criteria->plazas;
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
    if (MGM_PHP_INLINE) {
        $filterespecialidades = array_filter($especialidades, function($element) use ($data) {
            return (!in_array($element, $data));
        });
    } else {
        $filterespecialidades = array_filter($especialidades, create_function("$element",
        "return (!in_array($element, $data))"));
    }

    return $filterespecialidades;
}

function mgm_get_course_edition($id) {
    if (!$row = get_record('edicion_course', 'courseid', $id)) {
        return null;
    }

    if (!$edition = get_record('edicion', 'id', $row->edicionid)) {
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
    $strcourses = implode(',', $courses);

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

function mgm_edition_get_solicitudes($edition, $course) {
    global $CFG;

    $ret = 0;
    if ($records = get_records('edicion_preinscripcion', 'edicionid', $edition->id)) {
        foreach($records as $record) {
            $solicitudes = explode(",", $record->value);
            if (MGM_PHP_INLINE) {
                $ret += count(array_filter($solicitudes, function($element) use ($course) {
                    return ($element == $course->id);
                }));
            } else {
                $ret += count(array_filter($solicitudes, create_function("$element",
                "return ($element == $course->id)")));
            }
        }
    }

    return $ret;
}

function mgm_get_edition_course_preinscripcion_data($edition, $course) {
    global $CFG;

    // Preinscripcion date first
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_preinscripcion
    		WHERE edicionid = '".$edition->id."' ORDER BY timemodified ASC";

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

    // Get data and order it by: Course, Date
    $data = $firstdata = $lastdata = array();
    foreach ($final as $row) {
        $user = get_record('user', 'id', $row->userid);
        $userdata = get_record('edicion_user', 'userid', $user->id);
        $especs = explode("\n", $userdata->especialidades);
        $userespecs = '<select name="especialidades">';
        foreach ($especs as $espec) {
            $userespecs .= '<option name="'.$espec.'">'.mgm_translate_especialidad($espec).'</option>';
        }
        $userespecs .= '</select>';
        $courses = '<select name="courses">';
        $values = explode(',', $row->value);
        foreach($values as $courseid) {
            $ncourse = get_record('course', 'id', $courseid);
            $courses .= '<option name="'.$courseid.'">'.$ncourse->fullname.'</option>';
        }
        $courses .= '</select>';
        $tmpdata = array(
        	'<input type="checkbox" name="'.$row->userid.'" value="on"/>',
            $user->firstname,
            $user->lastname,
            date("d/m/Y", $row->timemodified),
            $userdata->cc,
            $userespecs,
            $courses
        );

        if ($values[0] == $course->id) {
            if ($criteria = mgm_get_edition_course_criteria($edition->id, $course->id)) {
                if ($criteria->opcion1 == "especialidades") {
                    $found = false;
                    $kets = array_keys($criteria->espec);
                    if ($especs[0] == $kets[0]) {
                        array_unshift($firstdata, $tmpdata);
                    } else {
                        $firstdata[] = $tmpdata;
                    }
                }
            } else {
                array_unshift($firstdata, $tmpdata);
            }
        } else {
            if ($criteria = mgm_get_edition_course_criteria($edition->id, $course->id)) {
                if ($criteria->opcion1 == "especialidades") {
                    $found = false;
                    $kets = array_keys($criteria->espec);
                    if ($especs[0] == $kets[0]) {
                        array_unshift($lastdata, $tmpdata);
                    } else {
                        $lastdata[] = $tmpdata;
                    }
                }
            } else {
                array_unshift($lastdata, $tmpdata);
            }
        }
    }

    foreach ($firstdata as $fd) {
        $data[] = $fd;
    }
    foreach ($lastdata as $ld) {
        $data[] = $ld;
    }

    return $data;
}

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
    if (MGM_PHP_INLINE) {
        $filterespecialidades = array_filter($especialidades, function($element) use ($data) {
            return (!in_array($element, $data));
        });
    } else {
        $filterespecialidades = array_filter($especialidades, create_function("$element",
        "return (!in_array($element, $data))"));
    }

    return $filterespecialidades;
}

function mgm_set_userdata($userid, $data) {
    $newdata = new stdClass();
    $newdata->cc = $data->cc;
    $newdata->userid = $userid;
    if (!record_exists('edicion_user', 'userid', $userid)) {
        $newdata->especialidades = implode("\n", $data->aespecs);
        insert_record('edicion_user', $newdata);
    } else {
        $olddata = get_record('edicion_user', 'userid', $userid);
        $newdata->id = $olddata->id;
        if (!isset($data->addsel)) {
            $especialidades = explode("\n", $olddata->especialidades);
            if (MGM_PHP_INLINE) {
                $newdata->especialidades = implode("\n", array_filter($especialidades, function($element) use ($data) {
                    return (!in_array($element, $data));
                }));
            } else {
                $newdata->especialidades = implode("\n", array_filter($especialidades, create_function("$element",
                "return (!in_array($lement, $data))")));
            }
        } else {
            $newdata->especialidades = $olddata->especialidades;
        }
        update_record('edicion_user', $newdata);
    }
}