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
 * @copyright  2011 Pedro Peña Pérez <pedro.pena@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/lib/filelib.php");
require_once($CFG->dirroot."/mod/mgm/locallib.php");

require_login();
require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

$strtitle = get_string('exportdata','mgm');

require_once($CFG->libdir.'/adminlib.php');

$tempdir = $CFG->dataroot."/temp/";
$filename = optional_param('filename');

if ($filename && file_exists($tempdir.$filename)) {
  $lifetime = 0;
  send_file($tempdir.$filename, "export.zip", $lifetime, 0, false, true);
  @unlink($tempdir.$filename);
}
else {
  admin_externalpage_setup('edicionesmgmt', mgm_update_edition_button());
  admin_externalpage_print_header();
  print_heading($strtitle);
  
  print_simple_box_start('center');
  
  $emision = new EmisionDatos();
  $resultado = $emision->aFichero( $tempdir );
  
  foreach ($resultado->incidencias as $incidencia)
    echo $incidencia;
  
  echo get_string('file_export_link', 'mgm', $resultado);
  print_simple_box_end();
  
  admin_externalpage_print_footer();
}
?>