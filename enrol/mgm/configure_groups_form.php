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
 * @copyright  2010 - 2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * mgm_course_edit_form class
 * @author Oscar Campos
 */
class mgm_groups_form extends moodleform {

    // Form definition
    function definition() {
        global $CFG;
        $mform =& $this->_form;
        $criteria = $this->_customdata;
        //print_object($this->_customdata);

        if (isset($criteria->id)) {
            // Editing an existing edition criteria
            $strsubmit = get_string('savechanges');
        } else {
            // Making a new edition
            $strsubmit = get_string('createcriteria', 'mgm');
        }

        $mform->addElement('header', 'groups', get_string('groups', 'mgm'));

        $mform->addElement('text', 'group_name', get_string('name'), 'readonly=1');
        $mform->addRule('group_name', get_string('required'), 'required', null);

        $mform->addElement('select', 'tutor', get_string('tutor', 'mgm'), $this->_customdata, 'onChange="mgm_opciones(true);"');
        $mform->addElement('select', 'coordinador', get_string('coordinador', 'mgm'), $this->_customdata, 'onChange="mgm_opciones(false);"');

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
