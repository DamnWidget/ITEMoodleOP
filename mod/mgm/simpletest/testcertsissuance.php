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
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if(!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is fobidden.');
}

class testCertificatesIssuance extends UnitTestCase {
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

    function testButtonCreateDraft() {
        global $CFG;

        $this->edition->certified = MGM_CERTIFICATE_NONE;
        $this->menu .= ' | <a title="'.get_string('cert', 'mgm').'" href="certificate.php?id='.$this->edition->id.'" id="edicion_'.$this->edition->id.'">'.
         				'<img src="'.$CFG->pixpath.'/t/grades.gif" class="iconsmall" alt="'.get_string('cert', 'mgm').'" /></a>';

        $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
    }

    function testButtonValidateDraft() {
        global $CFG;

        $this->edition->certified = MGM_CERTIFICATE_DRAFT;
        $this->menu .= ' | <a title="'.get_string('certdraft', 'mgm').'" href="certificate.php?id='.$this->edition->id.'&draft=1" id="edicion_'.$this->edition->id.'">'.
        				'<img src="'.$CFG->pixpath.'/c/site.gif" class="iconsmall" alt="'.get_string('certdraft', 'mgm').'" /></a>';

         $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
     }

     function testButtonValidateCertified() {
         global $CFG;

         $this->edition->certified = MGM_CERTIFICATE_VALIDATED;
         $this->menu .= ' | <a title="'.get_string('certified', 'mgm').'" href="">'.
         			    '<img src="'.$CFG->pixpath.'/i/tick_green_small.gif" class="iconsmall" alt="'.get_string('certified', 'mgm').'" /></a>';

         $this->assertEqual($this->menu, mgm_get_edition_menu($this->edition));
     }
}

class testCertificationIssuanceInterface extends WebTestCase {

    function setUp() {
        global $CFG;

        $this->get($CFG->wwwroot.'/login/index.php');

        $this->setFieldById('username', 'administracion');
        $this->setFieldById('password', 'administracion');
        $this->click('Login');
    }

    function tearDown() {
        $this->clickLink('Salir');
        unset($this->edition);
    }

    function helperGetCertificationDraft() {
        global $CFG;

        $this->get($CFG->wwwroot.'/mod/mgm/index.php?editionedit=on');

        $sql = "SELECT * FROM ".$CFG->prefix."edicion " .
               "WHERE certified='0' AND active='0' LIMIT 1";
        $this->edition = get_record_sql($sql, false, true);
        $this->clickLinkById('edicion_'.$this->edition->id);
    }

    function testNoEditionId() {
        global $CFG;

        $this->get($CFG->wwwroot.'/mod/mgm/certificate.php');
        $this->assertText('No id provided');
    }

    function testInvalidEditionId() {
        global $CFG;

        $this->get($CFG->wwwroot.'/mod/mgm/certificate.php?id=1010101010');
        $this->assertText('Edition not known');
    }

    function testButtonNoRollback() {
        $this->helperGetCertificationDraft();
        $this->click('No');
        $this->assertText('Desarrollo-ITE: Administración: Ediciones: Agregar/modificar ediciones', 'Click at NO button just returns to index.php');
    }

    function testButtonYesCertificateDraft() {
        $this->helperGetCertificationDraft();
        $this->click('Sí');
        $data = get_record('edicion', 'id', $this->edition->id);
        $this->assertEqual(MGM_CERTIFICATE_DRAFT, $data->certified);
        $this->edition->certified = MGM_CERTIFICATE_NONE;
        update_record('edicion', $this->edition);
    }

    function testButtonYesCertificateValidation() {
        global $CFG;

        $edition = new stdClass();
        $edition->active = false;
        $edition->certified = MGM_CERTIFICATE_DRAFT;
        $edition->name = 'Test Edition';
        insert_record('edicion', $edition);
        $edition = get_record('edicion', 'name', 'Test Edition');

        $this->get($CFG->wwwroot.'/mod/mgm/index.php?editionedit=on');
        $this->clickLinkById('edicion_'.$edition->id);
        $this->click('Sí');
        $data = get_record('edicion', 'id', $edition->id);
        $this->assertEqual(MGM_CERTIFICATE_VALIDATED, $data->certified);
        delete_records('edicion', 'id', $edition->id);
    }
}