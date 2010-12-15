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

        $mform->addElement('select', 'opcion1', get_string('opcionuno', 'mgm'), $choices, 'onChange="mgm_opciones(true);"');
        $mform->addElement('select', 'opcion2', get_string('opciondos', 'mgm'), $choices, 'onChange="mgm_opciones(false);"');

        if (isset($criteria->id)) {
            $mform->addElement('hidden', 'id', 0);
            $mform->setType('id', PARAM_INT);
            $mform->setDefault('id', $criteria->id);
        }

        if (isset($criteria->edition)) {
            $mform->addElement('hidden', 'id', 0);
            $mform->setType('edicionid', PARAM_INT);
            $mform->setDefault('edicionid', $criteria->edition);
        }

        $this->add_action_buttons(true, $strsubmit);
    }
}