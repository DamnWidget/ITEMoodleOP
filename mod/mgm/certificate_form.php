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
 * Certificate form
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
 
require_once($CFG->dirroot."/course/moodleform_mod.php");
 
/**
 * mod_mgm_certification_form class
 * @author Oscar Campos
 */
class mod_mgm_certification_form extends moodleform {
    
    // Form definition
    function definition() {        
        $mform =& $this->_form;
        $edition = $this->_customdata;
        
        $mform->addElement('header', 'general', get_string('general'));
        $mform->addElement('date_selector', 'fecha_emision', get_string('fechaemision', 'mgm'));
        
        $this->add_action_buttons(false, get_string('ok'));
    }    
}