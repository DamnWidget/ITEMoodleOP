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
 * Unit tests for (some of) mod/mgm/locallib.php and enrol/mgm/enrol.php
 *
 * @package    enrol
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');
}

require_once ($CFG->dirroot . '/mod/mgm/locallib.php');

class testPreinscribeUserInEdition extends UnitTestCase {
    function setUp() {
        $this->edition = mgm_get_active_edition();
        $this->user = 1010101010;

        $tcourses = mgm_get_edition_available_courses($this->edition);
        $courses = array();
        foreach ($tcourses as $course) {
            $courses[] = $course->id;
        }
        $this->courses = $courses;
    }

    function tearDown() {
    }

    function testPreinscribeNewRegister() {
        mgm_preinscribe_user_in_edition($this->edition->id, $this->user, $this->courses, null);

        $this->assertTrue(get_records('edicion_preinscripcion', 'userid', $this->user), 'New register created');
    }

    function testPreinscribeUpdateRegister() {
        $origdata = get_record('edicion_preinscripcion', 'userid', $this->user);
        $this->assertTrue($origdata, 'Record already exists');

        $courses = array(1);
        mgm_preinscribe_user_in_edition($this->edition->id, $this->user, $courses, null);
        $newdata = get_record('edicion_preinscripcion', 'userid', $this->user);

        $this->assertNotEqual($origdata, $newdata, 'Registers are not equal');
    }

    function testPreinscribeWithEmptyCourses() {
        $courses = array();
        mgm_preinscribe_user_in_edition($this->edition->id, $this->user, $courses, null);

        $this->assertFalse(get_records('edicion_preinscripcion', 'userid', $this->user), 'It\'s false');
    }
}

class testInscribeUserInEdition extends UnitTestCase {
    function setup() {
        $this->edition = mgm_get_active_edition();
        $this->user = '1010101010';
        $this->course = '1010101010';
    }

    function tearDown() {
    }

    function testInscribeNewRegister() {
        mgm_inscribe_user_in_edition($this->edition->id, $this->user, $this->course, false);

        $data = get_record('edicion_inscripcion', 'userid', $this->user);
        $this->assertTrue($data, 'New record inserted');
        $this->assertFalse($data->released, 'Released is False');
    }

    function testInscribeUpdateRegister() {
        $origdata = get_record('edicion_inscripcion', 'userid', $this->user);
        $this->assertTrue($origdata, 'Record already exists');

        mgm_inscribe_user_in_edition($this->edition->id, $this->user, $this->course, true);
        $newdata = get_record('edicion_inscripcion', 'userid', $this->user);
        $this->assertTrue($newdata->released, 'Released is True');

        $this->assertNotEqual($origdata, $newdata, 'Registers are not equal');
        delete_records('edicion_inscripcion', 'userid', $this->user);
    }
}

class testCreateEnrolmentGroups extends UnitTestCase {
    function setup() {
        $this->edition = '1010101010';
        $this->course = '1010101010';
    }

    function tearDown() {
    }

    function createGroup($edition=null, $course=null) {
        if ($edition == null && $course == null) {
            mgm_create_enrolment_groups($this->edition, $this->course);
        } else {
            mgm_create_enrolment_groups($edition, $course);
        }
    }

    function testNoInscription() {
        $this->expectError('Error, there is no inscription for the edition and course ids given');
        $this->createGroup();
    }

    function testNoCriteria() {
        $this->expectError('Error, there is no inscription for the edition and course ids given');
        $this->expectError('Error, there is no criteria for the edition and course ids given');
        $this->createGroup();
    }
}