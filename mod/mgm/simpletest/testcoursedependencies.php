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
 * Test course dependencies
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if(!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is fobidden.');
}

class testCourseDependencies extends UnitTestCase {
    function setUp() {
        $this->edicion = get_record('edicion', 'id', 7);
        
        $this->course = get_record('course', 'id', 16);
        
        $this->user = new stdClass();
        $this->user->id = 1010101010;
        
        $this->history = new stdClass();
        $this->history->courseid = "PR03#001";
        $this->history->userid = $this->user->id;
        $this->history->edicionid = $this->edicion->id;
        $this->history->roleid = 5;
        
        $this->criteria = new stdClass();        
        $this->criteria->edicion = $this->edicion->id;
        $this->criteria->course = $this->course->id;
        $this->criteria->type = MGM_CRITERIA_DEPEND;
        $this->criteria->value = "PR03#001";
        
        insert_record('edicion_criterios', $this->criteria);
    }
    
    function tearDown() {
        unset($this->edicion);
        unset($this->course);
        unset($this->history);        
        
        delete_records('edicion_criterios', 'value', $this->criteria->value);
        
        unset($this->criteria);
    }
    
    function testCheckCourseDependencies() {
        insert_record('edicion_cert_history', $this->history);
        $this->assertTrue(mgm_check_course_dependencies($this->edicion, $this->course, $this->user));        
        delete_records('edicion_cert_history', 'userid', $this->user->id);
    }
    
    /*function testCheckCourseDependciesFail() {
        $this->history->courseid = "PR03#002";
        insert_record('edicion_cert_history', $this->history);
        $this->assertFalse(mgm_check_course_dependencies($this->edicion, $this->course, $this->user));
        delete_records('edicion_cert_history', 'userid', $this->user->id);        
    }*/
}