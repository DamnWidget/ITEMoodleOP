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
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * mod_mgm_edit_form class
 * @author Oscar Campos
 */
class mod_mgm_user_form extends moodleform {

    // Form definition
    function definition() {
        global $CFG, $USER, $NIVELES_EDUCATIVOS, $CUERPOS_DOCENTES;
        $mform =& $this->_form;
        $strsubmit = get_string('savechanges');

        $mform->addElement('header', 'general', get_string('general'));
        
        $tiposid = array(
          'N' => 'N NIF',
          'P' => 'P PASAPORTE',
          'T'  => 'T TARJETA DE RESIDENCIA'
        );
        $mform->addElement('select', 'tipoid', get_string('tipoid','mgm'), $tiposid);
        $mform->addRule('tipoid', get_string('required'), 'required', null);
        
        $mform->addElement('text', 'dni', get_string('dni', 'mgm'), array('size' => '9'));
        $mform->addRule('dni', get_string('required'), 'required', null);
        
        $mform->addElement('text', 'cc', get_string('cc', 'mgm'), array('size'=>'30'));
        $mform->setHelpButton('cc', array('cc', get_string('cc', 'mgm'), 'mgm'));
        $mform->addRule('cc', get_string('required'), 'required', null);
        
        $mform->addElement('select', 'codniveleducativo', get_string('codniveleducativo','mgm'), $NIVELES_EDUCATIVOS);
        $mform->addRule('codniveleducativo', get_string('required'), 'required', null);
        
        $mform->addElement('select', 'codcuerpodocente', get_string('codcuerpodocente','mgm'), $CUERPOS_DOCENTES);
        $mform->addRule('codcuerpodocente', get_string('required'), 'required', null);
        
        $mform->addElement('text', 'codpostal', get_string('codpostal','mgm'), array('size' => '5'));
        $mform->addRule('codpostal', get_string('required'), 'required', null);
        
        $sexos = array(
          'H' => 'H Hombre',
          'M' => 'M Mujer'
        );
        $mform->addElement('select', 'sexo', get_string('sexo','mgm'), $sexos);
        $mform->addRule('sexo', get_string('required'), 'required', null);


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

        $renderer = & $mform->defaultRenderer();
        $tpl = '<label class="qflabel" style="vertical-align:top;">{label}</label> {element}';
        $renderer->setGroupElementTemplate($tpl, 'coursesgrp');

        $this->add_action_buttons(true, $strsubmit);
    }
}