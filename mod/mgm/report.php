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

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");
require_login();

if (!isloggedin() or isguestuser()) {
    error('You need to be logged into the platform!');
}
$params='?id=';
$reporttype = required_param('report_type');
$courseid = optional_param('courseid', 0, PARAM_INT);
$id=0;
if ($reporttype){
	$reports=mgm_get_reports();
	foreach($reports as $report){
		  if ( $report->name == $reporttype){
		  	$id=$report->value;
		  }
	}
}
if ($id){
	$params=$params.$id;
}else{
  error(get_string('unknowreport', 'mgm'));
}
if ($courseid){
	$params=$params."&courseid=". $courseid;
}
$edition=mgm_get_active_edition();
if ($edition){
	$params=$params.'&filter_editions='.$edition->id;
}
#$params='?id=45&filter_editions=3';
redirect("$CFG->wwwroot".'/blocks/configurable_reports/viewreport.php'. $params);