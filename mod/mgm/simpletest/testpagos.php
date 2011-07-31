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
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if(!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is fobidden.');
}

class testPagos extends UnitTestCase {
    function setUp() {
        global $CFG;
        
        $this->edition = new stdClass();
        $this->edition->id = 1;
        $this->edition->name = 'First Edition';
        $this->edition->active = false;

        $this->menu  = '<a title="'.get_string('edit').'" href="edicionedit.php?id='.$this->edition->id.'"><img'.
                       ' src="'.$CFG->pixpath.'/t/edit.gif" class="iconsmall" alt="'.get_string('edit').'" /></a>'.
                       ' | '.
                       '<a title="'.get_string('delete').'" href="delete.php?id='.$this->edition->id.'">'.
                       '<img src="'.$CFG->pixpath.'/t/delete.gif" class="iconsmall" alt="'.get_string('delete').'" /></a>'.
                       ' | '.
                       '<a title="'.get_string('activar', 'mgm').'" href="active.php?id='.$this->edition->id.'">'.
                       '<img src="'.$CFG->pixpath.'/t/go.gif" class="iconsmall" alt="'.get_string('activar', 'mgm').'" /></a>';
    }
    
    function tearDown() {
        unset($this->edition);
        unset($this->menu);
    }
    
    function testButtonPagosDownloadNotCert() {
        global $CFG;

        $this->edition->certified = MGM_CERTIFICATE_NONE;
        $this->menu .= ' | <a title="'.get_string('cert', 'mgm').'" href="certificate.php?id='.$this->edition->id.'" id="edicion_'.$this->edition->id.'">'.
                       '<img src="'.$CFG->pixpath.'/t/grades.gif" class="iconsmall" alt="'.get_string('cert', 'mgm').'" /></a>';
        $this->menu .= ' | <a title="'.get_string('pago-nc', 'mgm').'" href="#">'.
                       '<img src="'.$CFG->pixpath.'/i/unlock.gif" class="iconsmall" alt="'.get_string('pago-nc', 'mgm').'" /></a>';         

        $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
    }
    
    function testButtonPagosDownloadCertDraft() {
        global $CFG;

        $this->edition->certified = MGM_CERTIFICATE_DRAFT;
        $this->menu .= ' | <a title="'.get_string('certdraft', 'mgm').'" href="certificate.php?id='.$this->edition->id.'&draft=1" id="edicion_'.$this->edition->id.'">'.
                       '<img src="'.$CFG->pixpath.'/c/site.gif" class="iconsmall" alt="'.get_string('certdraft', 'mgm').'" /></a>';
        $this->menu .= ' | <a title="'.get_string('pago-nc', 'mgm').'" href="#">'.
                       '<img src="'.$CFG->pixpath.'/i/unlock.gif" class="iconsmall" alt="'.get_string('pago-nc', 'mgm').'" /></a>';         

        $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
    }
    
    function testButtonPagosDownloadCert() {
        global $CFG;

        $this->edition->certified = MGM_CERTIFICATE_VALIDATED;
        $this->menu .= ' | <a title="'.get_string('certified', 'mgm').'" href="#">'.
                       '<img src="'.$CFG->pixpath.'/i/tick_green_small.gif" class="iconsmall" alt="'.get_string('certified', 'mgm').'" /></a>';
        $this->menu .= ' | <a title="'.get_string('pago', 'mgm').'" href="#">'.
                       '<img src="'.$CFG->pixpath.'/i/lock.gif" class="iconsmall" alt="'.get_string('pago', 'mgm').'" /></a>';

        $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
    }
}