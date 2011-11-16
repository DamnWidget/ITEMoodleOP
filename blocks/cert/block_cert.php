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
 * Test certificates issuance
 *
 * @package    block
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once($CFG->dirroot.'/mod/mgm/locallib.php');

class block_cert extends block_base {
    function init() {
        $this->title = get_string('certificaciones', 'mgm');
        $this->version = 2011071401;
    }

    function applicable_formats() {
        return array('all' => true, 'my' => false, 'tag' => false);
    }

    function get_content () {        
        global $CFG, $COURSE;
        
        if ($this->content !== NULL) {            
            return $this->content;
        }
        
        if(!$currentcontext = get_context_instance(CONTEXT_COURSE, $COURSE->id)) {
            $this->content = '';
            return $this->content;
        }
        
        if (!mgm_can_do_create()) {
            $this->content = '';
            return $this->content;
        }
        
        if (!mgm_edition_is_certified(mgm_get_course_edition($COURSE->id))) {            
            $this->content = '';
            return $this->content;
        }

        $this->content->footer = '';
        $this->content->text = '<div>';

        $this->content->text .= "\n".'<ul>';
        $this->content->text .= '<li><a href="'.$CFG->wwwroot.'/mod/mgm/show_certifications.php?contextid='.$COURSE->id.'">'.get_string('showcertifications', 'mgm').'</a></li>';        
        $this->content->text .= '</ul>'."\n";
        $this->content->text .= '</div>';
        
        return $this->content;        
    }
}