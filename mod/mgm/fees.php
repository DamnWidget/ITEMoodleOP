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
 * @package   mod_mgm
 * @copyright 2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

require_login();
require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

function print_edition_edit_header() {
    global $CFG;
    require_once($CFG->libdir.'/adminlib.php');

    admin_externalpage_setup('fees');        
    admin_externalpage_print_header();
    $strtitle = get_string('fees', 'mgm');
    print_heading($strtitle);
}

$strfechainicio     = get_string('fechainicio', 'mgm');
$strfechafin        = get_string('fechafin', 'mgm');
$strname            = get_string('name');
$strlastname        = get_string('lastname'); 
$strcourse          = get_string('course');
$stramount          = get_string('amount', 'mgm');
$strtutors          = get_string('amount_tutors', 'mgm');
$strdni             = get_string('dni', 'mgm');
$stralumnos         = get_string('alumnos', 'mgm');
$strnostart         = get_string('nostart', 'mgm');
$strhalf            = get_string('half', 'mgm');
$strfull            = get_string('full', 'mgm');
$strprev            = get_string('prevlab', 'mgm');
$strtramo           = get_string('tramo', 'mgm');
$stremail           = get_string('email');
$strfechainicio    = get_string('fechainicio', 'mgm');
$strfechafin       = get_string('fechafin', 'mgm');

$id = optional_param('id', 0, PARAM_INT);    // Course id
$format = optional_param('format', '', PARAM_ALPHA);
$type = optional_param('type', '', PARAM_ALPHA);
$multiple = optional_param('multiple', false, PARAM_BOOL);
$editionid = optional_param('edition', 0, PARAM_INT);

if ($id) {
    if (!$course = get_record('course', 'id', $id)) {
        error('Course not known!');
    }
}

if(!$multiple) {
    if(!$format) {    
        print_edition_edit_header();    
        echo skip_main_destination();
        print_box_start('edicionesbox');
    }
    if (!isset($course) && !isset($edition) && !$format) {    
        mgm_print_fees_ediciones_list();    
    } else {
        $feestablec->head  = array($strname, $strlastname, $stremail, $strdni, $strcourse, $strtutors, $strprev, $strtramo, $stramount);
        $feestablec->align = array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center');
            
        $tmp_data = array(
            'course' => array(
                'id' => $course->id, 
                'name' => $course->shortname, 
                'fullname' => $course->fullname
            ),
            'criteria' => mgm_get_edition_course_criteria($edition->id, $course->id),
            'ecuador' => mgm_get_course_ecuador($course->id),
            'alumnos' => mgm_get_course_tutor_payment_count($course),
            'coordinacion' => mgm_get_course_coordinador_payment($course),
            'grupos' => mgm_get_course_tutor_payment($course)
        );        
      
        $edition = mgm_get_course_edition($course->id);
        
        $data[] = array(
            $tmp_data['coordinacion']['user']->firstname,
            $tmp_data['coordinacion']['user']->lastname,  
            $tmp_data['coordinacion']['user']->email,      
            ($tmp_data['coordinacion']['edicion_user']->dni != '') ? $tmp_data['coordinacion']['edicion_user']->dni : 'NO DATA' ,
            $tmp_data['course']['fullname'],
            count($tmp_data['coordinacion']['tutors']),
            $tmp_data['coordinacion']['prevlab'].'€',
            $tmp_data['coordinacion']['amount_per_tutor'].'€',
            $tmp_data['coordinacion']['total_amount'].'€'
        );
            
        $feestablec->data = $data;
        if(!$format) {
            print_heading(date('d-m-Y', $edition->inicio).' / '.date('d-m-Y', $edition->fin).'<br/>'.get_string('coordinador', 'mgm'));            
            print_table($feestablec); 
            echo "<br />";
        }
        
        if(!$format) {     
            print_heading('');
            print_box_start('');
            echo '<ul>';        
            echo '<li><a href="fees.php?id='.$id.'&type=coord&format=xls">'.get_string('downloadexcel').'</a></li>';
            echo '<li><a href="fees.php?id='.$id.'&type=coord&format=ods">'.get_string('downloadods').'</a></li>';
            echo '</ul>';
            print_box_end();
        }
        
        $feestable->head  = array($strname, $strlastname, $strdni, $stremail, $strcourse, $stralumnos, $strnostart, $strhalf, $strfull, $stramount);
        $feestable->align = array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center');
        unset($feestable->data);
        if(!$format) print_heading(get_string('tutores', 'mgm'));    
        if (empty($tmp_data['grupos'])) {            
            foreach($tmp_data['coordinacion']['tutors'] as $tutor) {                
                $feestable->data[] = array(
                    $tutor->firstname,
                    $tutor->lastname,
                    mgm_get_user_dni($tutor->id),
                    $tmp_data['course']['fullname'],
                    $tmp_data['alumnos']['dont_start']['count'] + $tmp_data['alumnos']['half']['count'] + $tmp_data['alumnos']['full']['count'],
                    $tmp_data['alumnos']['dont_start']['count'],
                    $tmp_data['alumnos']['half']['count'],
                    $tmp_data['alumnos']['full']['count'],
                    $tmp_data['alumnos']['half']['amount'] + $tmp_data['alumnos']['full']['amount'].'€' 
                );
            }
        } else {
            foreach($tmp_data['grupos'] as $grupo) {                
                $feestable->data[] = array(
                    $grupo['tutor'][0]['firstname'],
                    $grupo['tutor'][0]['lastname'],
                    mgm_get_user_dni($grupo['tutor'][0]['id']),
                    $grupo['tutor'][0]['email'],
                    $tmp_data['course']['fullname'],
                    count($grupo['alumnos']),
                    $grupo['result']['dont_start']['count'],
                    $grupo['result']['half']['count'],
                    $grupo['result']['full']['count'], 
                    $grupo['result']['half']['amount'] + $grupo['result']['full']['amount'].'€'
                );
            }
        }  
        
        if(!$format) {
            print_table($feestable);
            print_heading('');
            print_box_start('');
            echo '<ul>';        
            echo '<li><a href="fees.php?id='.$id.'&type=tutor&format=xls">'.get_string('downloadexcel').'</a></li>';
            echo '<li><a href="fees.php?id='.$id.'&type=tutor&format=ods">'.get_string('downloadods').'</a></li>';
            echo '</ul>';
            print_box_end();
        }
    }
    
    if(!$format) {
        print_box_end();
        admin_externalpage_print_footer();
    }
    
    if ($format) {
        if ($type == 'tutor') {
            $filename = $course->fullname.'-Tutores-fees';
        } else {
            $filename = $course->fullname.'-Coordinador-fees';
        }        
        if ($format == 'ods' || $format == 'xls') {
            $fields = array();
            $fields['filename'] = $filename;
            $fields['filetype'] = $format;
            $fields['header'] = ($type == 'tutor') ? $feestable->head : $feestablec->head;
            $fields['data'] = ($type == 'tutor') ? $feestable->data : $feestablec->data;        
            
            mgm_download_doc($fields);        
            die;
        }
    }
        
} else {
    $coordstable->head  = array('<b>'.$strcourse.'</b>', $strname, $strlastname, $strdni, $strtutors, $strprev, $strtramo, $stramount);
    $coordstable->align = array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center');
    
    $tutorstable->head  = array($strcourse, $strname, $strlastname, $strdni, $stremail, $stralumnos, $strnostart, $strhalf, $strfull, $stramount);
    $tutorstable->align = array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center', 'center');
    
    $data = '';        
    $edition = get_record('edicion', 'id', $editionid);    
    mgm_get_edition_payment_data($edition, $data);    
    $coord_data = $tutor_data = array();
    foreach($data as $course) {
        $coord_data[] = array(
            '<b>'.$course['course']['fullname'].'</b>',
            ($course['coordinacion']['user']) ? $course['coordinacion']['user']->firstname : 'NO DATA',
            ($course['coordinacion']['user']) ? $course['coordinacion']['user']->lastname : 'NO DATA',        
            ($course['coordinacion']['edicion_user']->dni != '') ? $course['coordinacion']['edicion_user']->dni : 'NO DATA' ,            
            count($course['coordinacion']['tutors']),
            ($course['coordinacion']['prevlab']) ? $course['coordinacion']['prevlab'].'€' : 'NO DATA',
            ($course['coordinacion']['amount_per_tutor']) ? $course['coordinacion']['amount_per_tutor'].'€' : 'NO DATA',
            ($course['coordinacion']['total_amount']) ? $course['coordinacion']['total_amount'].'€' : 'NO DATA'               
        );
        
        if (empty($course['grupos'])) {
            foreach($course['coordinacion']['tutors'] as $tutor) {
                $tutor_data[] = array(
                    '<b>'.$course['course']['fullname'].'</b>',
                    $tutor->firstname,
                    $tutor->lastname,
                    mgm_get_user_dni($tutor->id),                    
                    $course['coordinacion']['alumnos']['dont_start']['count'] + $course['coordinacion']['alumnos']['half']['count'] + $course['coordinacion']['alumnos']['full']['count'],
                    $course['coordinacion']['alumnos']['dont_start']['count'],
                    $course['coordinacion']['alumnos']['half']['count'],
                    $course['coordinacion']['alumnos']['full']['count'],
                    $course['coordinacion']['alumnos']['half']['amount'] + $course['coordinacion']['alumnos']['full']['amount'].'€' 
                );
            }
        } else {
            foreach($course['grupos'] as $grupo) {                
                $tutor_data[] = array(
                    '<b>'.$course['course']['fullname'].'</b>',
                    $grupo['tutor'][0]['firstname'],
                    $grupo['tutor'][0]['lastname'],
                    mgm_get_user_dni($grupo['tutor'][0]['id']),
                    $grupo['tutor'][0]['email'],                    
                    count($grupo['alumnos']),
                    $grupo['result']['dont_start']['count'],
                    $grupo['result']['half']['count'],
                    $grupo['result']['full']['count'], 
                    $grupo['result']['half']['amount'] + $grupo['result']['full']['amount'].'€'
                );
            }
        }                
    }

    $coordstable->data = $coord_data;
    $tutorstable->data = $tutor_data;

    if(!$format) {
        print_edition_edit_header();    
        echo skip_main_destination();
        print_box_start('edicionesbox');
        print_heading(get_string('coordinador', 'mgm'));
        print_table($coordstable);
        print_box_start('');
        echo '<ul>';        
        echo '<li><a href="fees.php?id='.$id.'&edition='.$editionid.'&multiple=1&type=coord&format=xls">'.get_string('downloadexcel').'</a></li>';
        echo '<li><a href="fees.php?id='.$id.'&edition='.$editionid.'&multiple=1&type=coord&format=ods">'.get_string('downloadods').'</a></li>';
        echo '</ul>';
        print_box_end();
        print_heading(get_string('tutores', 'mgm'));
        print_table($tutorstable);
        print_heading('');
        print_box_start('');
        echo '<ul>';        
        echo '<li><a href="fees.php?id='.$id.'&edition='.$editionid.'&multiple=1&type=tutor&format=xls">'.get_string('downloadexcel').'</a></li>';
        echo '<li><a href="fees.php?id='.$id.'&edition='.$editionid.'&multiple=1&type=tutor&format=ods">'.get_string('downloadods').'</a></li>';
        echo '</ul>';
        print_box_end();
        print_box_end();
        admin_externalpage_print_footer();
    }

    if ($format) {
        if ($type == 'tutor') {
            $filename = $edition->name.'-Tutores-fees';
        } else {
            $filename = $edition->name.'-Coordinador-fees';
        }        
        if ($format == 'ods' || $format == 'xls') {
            $fields = array();
            $fields['filename'] = $filename;
            $fields['filetype'] = $format;
            $fields['header'] = ($type == 'tutor') ? $tutorstable->head : $coordstable->head;
            $fields['data'] = ($type == 'tutor') ? $tutorstable->data : $coordstable->data;        
            
            mgm_download_doc($fields);        
            die;
        }
    }
}
