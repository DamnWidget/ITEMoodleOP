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
 * @copyright  2011 Jesús Jaén Díaz <jesus.jaen@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/lib/filelib.php");
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");
require_once($CFG->dirroot."/mod/mgm/mgm_forms.php");
require_once($CFG->dirroot."/lib/adodb/adodb.inc.php");

require_login();
require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));
$strtitle = get_string('importdata','mgm');
require_once($CFG->libdir.'/adminlib.php');
$tempdir = $CFG->dataroot."/temp/";

$mform = new edicion_form("$CFG->wwwroot".'/mod/mgm/import.php');
//  $mform ->_form->addElement('file', 'userfile', get_string('file'));
//  $mform ->_form->insertElementBefore($mform->_form->removeElement('userfile', false), 'actionsgrp');
$mform ->_form->insertElementBefore($mform->_form->createElement('file', 'userfile', get_string('file')), 'actionsgrp');
//$um = new upload_manager('newfile');
//$mform -> set_upload_manager($um);

if ($data = $mform->get_data(false)) {
    if 	(!empty($data->cancel)){
    	unset($_POST);
    	redirect("$CFG->wwwroot".'/index.php');
    }
    else if (!empty($data->next)) {
    	if ($mform->save_files($tempdir)) {
    		$mform->_upload_manager->inputname='userfile';
    		$filename=$mform->get_new_filename();
    		if ($filename){
    			print 'fichero' . $filename . ' guardado';
    		}
    	}
    	$edicion=$data->edition;
    	if (isset($edicion) and $edicion != 0 and $filename){
    		print "procesar la edicion y el fichero";

				die();

    	}else{
    		error('Edicion no valida',"$CFG->wwwroot".'/mod/mgm/import.php');
    	}

    }else{
	    // reset the form selection
    	unset($_POST);
    }
}else{
 	unset($_POST);
}

//if ($filename && file_exists($tempdir.$filename)) {
//  $lifetime = 0;
//  //send_file($tempdir.$filename, "export.zip", $lifetime, 0, false, true);
//  print "Realizar operaciones en la base de datos Moodle";
//  $table="PARTICIPANTES";
//  $cmd="/usr/bin/mdb-export ".$tempdir.$filename. ' ' . $table;
//  $ret=0;
//  $dev=system($cmd, $ret);
//  print $dev;
//
//}else {

//do output
	admin_externalpage_setup('importdata', mgm_update_edition_button());
  admin_externalpage_print_header();
  print_heading($strtitle);
  print_simple_box_start('center');
  $mform->display();
  print_simple_box_end();
  admin_externalpage_print_footer();
  #redirect("$CFG->wwwroot".'/mod/mgm/import.php?filename=repuestamdb_externo.mdb');
//}

?>