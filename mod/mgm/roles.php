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
 * Interface for choose roles 
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 - 2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/locallib.php');

require_login();

$roles = mgm_get_certification_roles();
$availableroles = get_records('role');

$strtitle = get_string('setroles', 'mgm');


require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('edicionesmgmt', mgm_update_edition_button());
admin_externalpage_print_header();
print_heading($strtitle);

if ($frm = data_submitted() and confirm_sesskey()) {
    if (isset($frm->roles)) {
        $ardy = array();
        foreach ($frm->roles as $k=>$v) {
            if (in_array($v, $ardy)) {
                error('You have duplicated IDs in your data, fix it!');
            }
            $ardy[] = $v;           
        }        
        
        mgm_set_certification_roles($frm->roles);
        redirect('index.php', get_string('rolesdone', 'mgm'), 5);       
    }
}

print_simple_box_start('center');
?>
<form id="rolesform" method="post" action="">
    <div style="text-align: center;">
        <input type="hidden" name="sesskey" value="<?php p(sesskey()) ?>" />
        <!-- Coordinador -->
        <label for="coordinadorselect">Coordinador :</label>
        <select name="roles[coordinador]" id="coordinadorselect">
            <?php
                foreach($availableroles as $avalrole) {
                    if ($roles['coordinador'] && $roles['coordinador'] == $avalrole->id) {                    
                        ?>
                        <option value="<?php echo $avalrole->id; ?>" selected><?php echo $avalrole->name; ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $avalrole->id; ?>"><?php echo $avalrole->name; ?></option>
                        <?php
                    }
                }
            ?>
        </select>
        <!-- Tutor -->
        <label for="tutorselect">Tutor :</label>
        <select name="roles[tutor]" id="tutorselect">
            <?php
                foreach($availableroles as $avalrole) {
                    if ($roles['tutor'] && $roles['tutor'] == $avalrole->id) {                    
                        ?>
                        <option value="<?php echo $avalrole->id; ?>" selected><?php echo $avalrole->name; ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $avalrole->id; ?>"><?php echo $avalrole->name; ?></option>
                        <?php
                    }
                }
            ?>
        </select>
        <!-- Alumno -->
        <label for="alumnoselect">Alumno :</label>
        <select name="roles[alumno]" id="alumnoselect">
            <?php
                foreach($availableroles as $avalrole) {
                    if ($roles['alumno'] && $roles['alumno'] == $avalrole->id) {                    
                        ?>
                        <option value="<?php echo $avalrole->id; ?>" selected><?php echo $avalrole->name; ?></option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $avalrole->id; ?>"><?php echo $avalrole->name; ?></option>
                        <?php
                    }
                }
            ?>
        </select>
        <input name="submit" id="submit" type="submit" value="<?php p(get_string('save', 'quiz')); ?>" />
    </div>
</form>
<?php
print_simple_box_end();

admin_externalpage_print_footer();