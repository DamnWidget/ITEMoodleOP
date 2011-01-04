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

require_once($CFG->libdir.'/formslib.php');

class enrol_mgm_form extends moodleform {

    // Form definition
    function definition() {
        $mform =& $this->_form;
        $course = $this->_customdata['course'];
        $edition = $this->_customdata['edition'];

        $mform->addElement('header', 'general', get_string('edicioncursos', 'mgm'));

        $mform->addElement('hidden', 'id', $course->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'edition', $edition->id);
        $mform->setType('edition', PARAM_INT);

        $mform->addElement('hidden', 'options', count($this->_customdata['choices']));
        $mform->setType('options', PARAM_INT);

        foreach ($this->_customdata['choices'] as $k=>$v) {
            $tmpnum = $k+1;
            $mform->addElement('select', 'option_'.$k, get_string('opcion', 'mgm').' '.$tmpnum, $v);
        }

        $this->add_action_buttons(false, get_string('savechanges'));
    }
}

class enrol_mgm_ro_form extends moodleform {

    // Form definition
    function definition() {
        $mform =& $this->_form;
        $course = $this->_customdata['course'];
        $edition = $this->_customdata['edition'];

        $mform->addElement('header', 'general', get_string('edicioncursos', 'mgm'));

        $mform->addElement('hidden', 'id', $course->id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'edition', $edition->id);
        $mform->setType('edition', PARAM_INT);

        $mform->addElement('hidden', 'options', count($this->_customdata['choices']));
        $mform->setType('options', PARAM_INT);

        foreach ($this->_customdata['choices'] as $k=>$v) {
            $tmpnum = $k+1;
            $mform->addElement('select', 'option_'.$k, get_string('opcion', 'mgm').' '.$tmpnum, $v, 'readonly');
        }
    }
}