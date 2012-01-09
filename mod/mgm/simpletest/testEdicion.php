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
 * Test pagos
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Jesus Jaen <jesus.jaen@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//if(!defined('MOODLE_INTERNAL')) {
//    die('Direct access to this script is fobidden.');
//}

class testEdicion extends UnitTestCase {
	  var $edicion1;
	  var $edicion2;


    function setUp() {
        global $CFG;
//
        $this->edicion1 = new stdClass();
        $this->edicion1 ->name="Test Edition 1";
        $this->edicion1 ->description="description";
        $this->edicion1 ->active=0;


//        $this->user_orig->id = 1;
//        $this->user_orig->name = "Origen";
//
//        $this->user_dest = new stdClass();
//        $this->user_dest->id = 2;
//        $this->user_dest->name = "Destino";




    }

    function tearDown() {
//        unset($this->user_orig);
//        unset($this->user_dest);
    }

    function testCreate() {
        $importData= new ImportData($this->file, $this->edicionid);
        $this->assertNotNull($importData);
    }

		function testAddDiff() {
        $joinusers= new JoinUsers($this->user_orig, $this->user_dest);
        $joinusers->addDiff();
        $this->assertNotNull($joinusers->getDiff());
    }

		function testDiffOrigName() {
        $joinusers= new JoinUsers($this->user_orig, $this->user_dest);
        $joinusers->addDiff();
        $this->assertEqual($joinusers->getDiff()->name->orig, "Origen");
    }

		function testDiffName() {
        $joinusers= new JoinUsers($this->user_orig, $this->user_dest);
        $joinusers->addDiff();
        $this->assertNotEqual($joinusers->getDiff()->name->orig, $joinusers->getDiff()->name->dest);
    }

		function testSetUsersId() {
//			  $m=new SimpleMock();
//			  $m->setReturnValue('mgm_get_user_complete', $this->user_orig);
//			  $m->setReturnValue('mgm_get_user_complete', $this->user_dest);
        $joinusers= new JoinUsers();
        $joinusers->setUserId($this->user_orig->id);
        $joinusers->setUserId($this->user_dest->id, 'dest');
        $this->assertEqual($joinusers->user_orig->id, $this->user_orig->id);
        $this->assertEqual($joinusers->user_dest->id, $this->user_dest->id);
    }
}