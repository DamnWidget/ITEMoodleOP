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
 * Certification módule
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once($CFG->libdir.'/adminlib.php');

require_login();

$courseid = optional_param('contextid', 0, PARAM_INT);
$format = optional_param('format', '', PARAM_ALPHA);

if (!$courseid) {
    error('There is no course id!.');
}

if (!$course = get_record('course', 'id', $courseid)) {
    error('The '.$courseid.' course does not exists on the database');
}

$participants = mgm_get_course_participants($course);
$participants = mgm_check_double_role_in_course($participants);

$participants_table->head = array(get_string('name'), get_string('course'),
    get_string('edicion', 'mgm'), get_string('role'), get_string('numregistro', 'mgm'));
$participants_table->align = array('left', 'left', 'left', 'left', 'left');
$participants_table->data = array();

foreach($participants as $participant) {            
    if (!mgm_user_passed_course(get_record('user', 'id', $participant->userid), $course)) {
        continue;
    }
    
    $user = get_record('user', 'id', $participant->userid);
    $edicion = mgm_get_course_edition($courseid);
    $cedition = get_record('edicion_course', 'courseid', $course->idnumber, 'edicionid', $edicion->id);    
    
    $sql = "SELECT * FROM ".$CFG->prefix."edicion_cert_history 
            WHERE userid=".$user->id." AND courseid=".$course->id." 
            AND edicionid=".$edicion->id;
    
    if(!$cert = get_record_sql($sql)) {        
        continue;        
    }                
    $role = get_record('role', 'id', $cert->roleid);    
    
    $participants_table->data[] = array(
        $user->username,
        $cedition->codactividad,
        $edicion->name,
        $role->name,                
        $cert->numregistro        
    );
}

if ($format) {    
    if ($format == 'ods' || $format == 'xls') {
        $fields = array();
        $fields['filename'] = $course->idnumber.'-certs';        
        $fields['filetype'] = $format;
        $fields['header'] = $participants_table->head;
        $fields['data'] = $participants_table->data;        
        
        mgm_download_doc($fields);        
        die;
    }
}

$navlinks = array();
$navlinks[] = array('name' => $course->shortname, 'link' => $CFG->wwwroot.'/course/view.php?id='.$courseid, 'type' => 'misc');
$navlinks[] = array('name' => get_string('certificaciones', 'mgm'), 'link' => null, 'type' => 'misc');
$navigation = build_navigation($navlinks);

print_header($course->shortname.": ".get_string('certificaciones', 'mgm'), $course->fullname, $navigation, "", "", true, "&nbsp;", navmenu($course));
print_heading(get_string('certificaciones', 'mgm'));

print_table($participants_table);

if (!empty($participants_table->data)) {    
    print_box_start('');
    echo '<ul>';
    echo '<li><a href="show_certifications.php?contextid='.$courseid.'&format=ods">'.get_string('downloadods').'</a></li>';
    echo '<li><a href="show_certifications.php?contextid='.$courseid.'&format=xls">'.get_string('downloadexcel').'</a></li>';
    echo '</ul>';
    print_box_end();    
}

print_footer();