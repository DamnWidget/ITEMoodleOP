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
 * Edition edit form
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * mod_mgm_edit_form class
 * @author Oscar Campos
 */
class mod_mgm_edit_form extends moodleform {

    // Form definition
    function definition() {
        global $CFG;
        $mform =& $this->_form;
        $edition = $this->_customdata;

        if (isset($edition->id)) {
            // Editing an existing edition
            $strsubmit = get_string('savechanges');
        } else {
            // Making a new edition
            $strsubmit = get_string('createedition', 'mgm');
        }

        $mform->addElement('header', 'general', get_string('general'));
        $mform->addElement('text', 'name', get_string('editionname', 'mgm'), array('size'=>'30'));
        $mform->addRule('name', get_string('required'), 'required', null);
        $mform->addElement('htmleditor', 'description', get_string('description'));
        $mform->setType('description', PARAM_RAW);
        if (!empty($CFG->allowcategory)) {
            $themes = array();
            $themes[''] = get_string('forceno');
            $themes += get_list_of_themes();
            $mform->addElement('select', 'theme', get_string('forcetheme'), $themes);
        }
        $mform->setHelpButton('description', array('writing', 'richtext'), false, 'editorhelpbutton');

        $iniciogrp = array();
        $iniciogrp[] = &MoodleQuickForm::createElement('date_time_selector', 'inicio');
        $mform->addGroup($iniciogrp, 'iniciogrp', get_string('fechainicio', 'mgm'), ' ', false);
        $mform->setDefault('inicio', 0);

        $fingrp = array();
        $fingrp[] = &MoodleQuickForm::createElement('date_time_selector', 'fin');
        $mform->addGroup($fingrp, 'fingrp', get_string('fechafin', 'mgm'), ' ', false);
        $mform->setDefault('fin', 0);

        $achoices = $schoices = array();
        $acourses = & $this->_customdata->acourses;
        $scourses = & $this->_customdata->scourses;

        if (is_array($acourses)) {
            $achoices += $acourses;
        }

        if (is_array($scourses)) {
            $schoices += $scourses;
        }

        $mform->addElement('header', 'courses', get_string('courses'));
        $courses = array();
        $courses[0] = & $mform->createElement('select', 'acourses', get_string('cavailable', 'mgm'), $achoices,
        									  'size="15" class="mod-mgm courses-select"
        									   onfocus="getElementById(\'id_addsel\').disabled=false;
        									   getElementById(\'id_removesel\').disabled=true;
        									   getElementById(\'id_scourses\').selectedIndex=-1;"');
        $courses[0]->setMultiple(true);
        $courses[1] = & $mform->createElement('select', 'scourses', get_string('cselected', 'mgm'), $schoices,
        									  'size="15" class="mod-mgm courses-select"
        									   onfocus="getElementById(\'id_addsel\').disabled=true;
        									   getElementById(\'id_removesel\').disabled=false;
        									   getElementById(\'id_acourses\').selectedIndex=-1;"');
        $courses[1]->setMultiple(true);

        $grp =& $mform->addElement('group', 'coursesgrp', get_string('courses'), $courses, ' ', false);
        $grp->setHelpButton(array('lists', get_string('courses'), 'mgm'));

        $objs = array();
        $objs[] =& $mform->createElement('submit', 'addsel', get_string('addsel', 'mgm'));
        $objs[] =& $mform->createElement('submit', 'removesel', get_string('removesel', 'mgm'));
        $grp =& $mform->addElement('group', 'buttonsgrp', get_string('selectedlist', 'mgm'), $objs, array(' ', '<br />'), false);
        $grp->setHelpButton(array('selectedlist', get_string('selectedlist', 'mgm'), 'mgm'));

        if (isset($edition->id)) {
            $mform->addElement('hidden', 'id', 0);
            $mform->setType('id', PARAM_INT);
            $mform->setDefault('id', $edition->id);
        }

        $renderer = & $mform->defaultRenderer();
        $tpl = '<label class="qflabel" style="vertical-align:top;">{label}</label> {element}';
        $renderer->setGroupElementTemplate($tpl, 'coursesgrp');

        $this->add_action_buttons(true, $strsubmit);
    }
}