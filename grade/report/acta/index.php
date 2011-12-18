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

require_once '../../../config.php';
require_once $CFG->libdir.'/gradelib.php';
require_once $CFG->dirroot.'/grade/lib.php';
require_once $CFG->dirroot.'/mod/mgm/locallib.php';

$courseid = required_param('id', PARAM_INT);
$userid   = optional_param('userid', $USER->id, PARAM_INT);

/// basic access checks
if (!$course = get_record('course', 'id', $courseid)) {
    print_error('nocourseid');
}
require_login($course);

$context = get_context_instance(CONTEXT_COURSE, $course->id);
require_capability('gradereport/user:view', $context);

if (empty($userid)) {
    require_capability('moodle/grade:viewall', $context);

} else {
    if (!get_record('user', 'id', $userid, 'deleted', 0) or isguestuser($userid)) {
        error("Incorrect userid");
    }
}
if(!$groups=get_records_sql('select id from mdl_groups where id in ( select groupid from mdl_groups_members where userid ='. $userid . ' and courseid=' .$courseid .' )' )) {
	$groups='';
}else{

	$groups='&filter_groups=('. implode(",", array_keys($groups)).')';
}

if (!$coursemgm = get_record('edicion_course', 'courseid', $courseid)) {
    error(get_string('nocoursemgm', 'mgm'), $CFG->wwwroot.'/course/view.php?id='.$courseid);
}else{
	if ($coursemgm->fechafin>time()){

		error(get_string('coursenotended','mgm'), $CFG->wwwroot.'/course/view.php?id='.$courseid);
	}
}

$access = false;
if (has_capability('moodle/grade:viewall', $context)) {
    //ok - can view all course grades
    $access = true;

} else if ($userid == $USER->id and has_capability('moodle/grade:view', $context) and $course->showgrades) {
    //ok - can view own grades
    $access = true;

} else if (has_capability('moodle/grade:viewall', get_context_instance(CONTEXT_USER, $userid)) and $course->showgrades) {
    // ok - can view grades of this user- parent most probably
    $access = true;
}

if (!$access) {
    // no access to grades!
    error("Can not view grades.", $CFG->wwwroot.'/course/view.php?id='.$courseid); //TODO: localize
}

/// return tracking object
$gpr = new grade_plugin_return(array('type'=>'report', 'plugin'=>'user', 'courseid'=>$courseid, 'userid'=>$userid));

/// last selected report session tracking
if (!isset($USER->grade_last_report)) {
    $USER->grade_last_report = array();
}
$USER->grade_last_report[$course->id] = 'user';


//first make sure we have proper final grades - this must be done before constructing of the grade tree
grade_regrade_final_grades($courseid);

$reporttype = 'Acta';
$reportid=0;
if ($reporttype){
	$reports=mgm_get_reports();
	foreach($reports as $report){
		  if ( $report->name == $reporttype){
		  	$reportid=$report->value;
		  }
	}
}
if ($reportid ==0){
	error("Informe incorrecto.", $CFG->wwwroot.'/course/view.php?id='.$courseid);
}
//Establecer permisos para acceso a informe de actas:
global $SESSION;
$SESSION->MGMINF->active=1;
$MGMINF->courseid=$courseid;
$MGMINF->userid=$userid;


if ($course)
$params='?id='.$reportid . '&filter_courses=' . $courseid . $groups. '&report_name=' . $reporttype . '&download=true&format=pdf';
if (has_capability('moodle/grade:viewall', $context)) { //Only Teachers will see de Acta
	   redirect("$CFG->wwwroot".'/blocks/configurable_reports/viewreport.php'. $params);
} else { //Students can not see act
    error("Acción no permitida.", $CFG->wwwroot.'/course/view.php?id='.$courseid);
}
//print_footer($course);

?>
