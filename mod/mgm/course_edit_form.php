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
 * Edition course criteria form
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * mgm_course_edit_form class
 * @author Oscar Campos
 */
class mgm_course_edit_form extends moodleform {

    // Form definition
    function definition() {
        global $CFG;
        $mform =& $this->_form;
        $criteria = $this->_customdata;

        if (isset($criteria->id)) {
            // Editing an existing edition criteria
            $strsubmit = get_string('savechanges');
        } else {
            // Making a new edition
            $strsubmit = get_string('createcriteria', 'mgm');
        }

        $mform->addElement('header', 'criteria', get_string('criterios', 'mgm'));

        $mform->addElement('text', 'plazas', get_string('plazas', 'mgm'));
        $mform->addRule('plazas', get_string('required'), 'required', null);

        $choices = array(
            'centros'        => get_string('prioridadcentro', 'mgm'),
            'especialidades' => get_string('prioridadespec', 'mgm')
        );

        $ccdata = & $this->_customdata->ccdata;

        $mform->addElement('select', 'opcion1', get_string('opcionuno', 'mgm'), $choices, 'onChange="mgm_opciones(true);"');
        $mform->addElement('select', 'opcion2', get_string('opciondos', 'mgm'), $choices, 'onChange="mgm_opciones(false);"');
        $mform->addElement('select', 'ccaas', get_string('cc', 'mgm'), $ccdata);

        $achoices = $schoices = array();
        $aespecs = & $this->_customdata->aespecs;
        $sespecs = & $this->_customdata->sespecs;

        if (is_array($aespecs)) {
            $achoices += $aespecs;
        }

        if (is_array($sespecs)) {
            $schoices += $sespecs;
        }

        $especs = array();
        $especs[0] = & $mform->createElement('select', 'aespecs', get_string('cavailable', 'mgm'), $achoices,
        	          						 'size="15" class="mod-mgm courses-select"
        									  onfocus="getElementById(\'id_addsel\').disabled=false;
        									  getElementById(\'id_removesel\').disabled=true;
        									  getElementById(\'id_scourses\').selectedIndex=-1;"');
        $especs[0]->setMultiple(true);
        $especs[1] = & $mform->createElement('select', 'sespecs', get_string('cselected', 'mgm'), $schoices,
        									 'size="15" class="mod-mgm courses-select"
        									  onfocus="getElementById(\'id_addsel\').disabled=true;
        									  getElementById(\'id_removesel\').disabled=false;
        									  getElementById(\'id_acourses\').selectedIndex=-1;"');
        $especs[1]->setMultiple(true);

        $grp =& $mform->addElement('group', 'especsgrp', get_string('especialidades', 'mgm'), $especs, ' ', false);

        $objs = array();
        $objs[] =& $mform->createElement('submit', 'addsel', get_string('addespec', 'mgm'));
        $objs[] =& $mform->createElement('submit', 'removesel', get_string('removeespec', 'mgm'));
        $grp =& $mform->addElement('group', 'buttonsgrp', get_string('selectedespeclist', 'mgm'), $objs, array(' ', '<br />'), false);


        if (isset($criteria->edicionid)) {
            $mform->addElement('hidden', 'edicionid', 0);
            $mform->setType('edicionid', PARAM_INT);
            $mform->setDefault('edicionid', $criteria->edicionid);
        }

        if (isset($criteria->courseid)) {
            $mform->addElement('hidden', 'courseid', 0);
            $mform->setType('courseid', PARAM_INT);
            $mform->setDefault('courseid', $criteria->courseid);
        }

        $this->add_action_buttons(true);
    }
}