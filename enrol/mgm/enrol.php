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
 * Implements the main code for the mgm enrolment
 *
 * @package    enrol
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot."/enrol/enrol.class.php");
require_once($CFG->dirroot."/mod/mgm/locallib.php");

/**
 *
 * Enrolment plugin class definition
 * @author Oscar Campos
 *
 */
class enrolment_plugin_mgm {
	/**
     * Prints out the configuration form for this plugin. All we need
     * to provide is the form fields. The <form> tags and submit button will
     * be provided for us by Moodle.
     *
     * @param object $formdata Equal to the global $CFG variable, or if
     *      process_config() returned false, the form contents
     * @return void
     */
    public function config_form($formdata){
        return;
    }

    /**
     * Process the data from the configuration form.
     *
     * @param object $formdata
     * @return boolean True if configuration was successful, False if the user
     *      should be kicked back to config_form() again.
     */
    public function process_config($formdata){
        return true;
    }

/**
     * Prints the entry form/page for interactive enrolment into a course.
     *
     * This is only called from course/enrol.php. Most plugins will probably
     * override this to print payment forms, etc, or even just a notice to say
     * that manual enrollment is disabled.
     *
     * @param object $course
     * @return void
     */
    public function print_entry($course) {
        global $CFG, $USER, $form;

        if (!$edition = mgm_get_course_edition($course->id)) {
            error(get_string('noeditioncourse', 'mgm'));
        }
        $strloginto = get_string('loginto', '', $edition->name);
        $strcourses = get_string('courses');

        $context = get_context_instance(CONTEXT_SYSTEM);

        $navlinks = array();
        $navlinks[] = array('name' => $strcourses, 'link' => ".", 'type' => 'misc');
        $navlinks[] = array('name' => $strloginto, 'link' => null, 'type' => 'misc');
        $navigation = build_navigation($navlinks);

        if (has_capability('moodle/legacy:guest', $context, $USER->id, false)) {
            add_to_log($course->id, 'course', 'guest', 'view.php?id='.$course->id, getremoteaddr());
            return;
        }

        print_header($strloginto, $course->fullname, $navigation);
        echo '<br />';
        print_heading($edition->name.' ('.$edition->description.')');
        print_simple_box_start('center', '80%');

        $choices = array();
        if (!$options = mgm_get_edition_user_options($edition->id, $USER->id)) {
            foreach (mgm_get_edition_courses($edition) as $course) {
                $choices[0][$course->id] = $course->fullname;
            }
        } else {
            $plus = 0;
            if (mgm_count_courses($edition) > count($options)) {
                $plus = 1;
            }
            for ($i = 0; $i < count($options)+$plus; $i++) {
                foreach (mgm_get_edition_courses($edition) as $course) {
                    $choices[$i][$course->id] = $course->fullname;
                }
            }
        }

        // Print form
        require_once($CFG->dirroot.'/enrol/mgm/enrol_form.php');
        $eform = new enrol_mgm_form('enrol.php', compact('course', 'edition', 'choices'));
        if ($options) {
            $data = new stdClass();
            foreach ($options as $k=>$v) {
                $prop = 'option_'.$k;
                $data->$prop = $v;
            }
            $eform->set_data($data);
        }
        if ($eform->get_data()) {
            $courses = array();
            for ($i = 0; $i < $form->options; $i++) {
                $property = 'option_'.$i;

                if (in_array($form->$property, $courses)) {
                    error(get_string('opcionesduplicadas', 'mgm'));
                    print_simple_box_end();
                    print_footer();
                    die();
                }

                $courses[$i] = $form->$property;
            }

            mgm_preinscribe_user_in_edition($edition->id, $USER->id, $courses);
            notify(get_string('preinscrito', 'mgm'), 'bold', 'center');
        }

        $eform->display();

        if ($options) {
            echo "<br />";
            echo get_string('edicionwarning', 'mgm');
        }

        print_simple_box_end();
        print_footer();
    }

    /**
     * The other half to print_entry(), this checks the form data.
     *
     * This function checks that the user has completed the task on the enrollment
     * entry page and enrolls them.
     *
     * @param object $form
     * @param object $course
     * @return void
     */
    public function check_entry($form, $course) {
        // some logic
        // some role_assign();
    }

    /**
     * OPTIONAL: Check if the given enrolment key matches a group enrolment key for
     * the given course.
     *
     * @param int $courseid
     * @param string $enrolmentkey
     * @return mixed The group id of the group which the key matches, or false
     *       if it matches none
     */
    public function check_group_entry($courseid, $password){
        // some logic
        if ($itlooksgood){
            return $groupid;
        } else {
            return false;
        }
    }

    /**
     * OPTIONAL: Return a string with icons that give enrolment information
     * for this course.
     *
     * @param object $course
     * @return string
     */
    public function get_access_icons($course){
        global $CFG;

        global $strallowguests;
        global $strrequireskey;

        if (empty($strallowguests)) {
            $strallowguests = get_string('allowguests');
            $strrequireskey = get_string('requireskey');
        }

        $str = '';

        if (!empty($course->guest)) {
            $str .= '<a title="'.$strallowguests.'" href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'">';
            $str .= '<img class="accessicon" alt="'.$strallowguests.'" src="'.$CFG->pixpath.'/i/guest.gif" /></a>&nbsp;&nbsp;';
        }
        if (!empty($course->password)) {
            $str .= '<a title="'.$strrequireskey.'" href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'">';
            $str .= '<img class="accessicon" alt="'.$strrequireskey.'" src="'.$CFG->pixpath.'/i/key.gif" /></a>';
        }

        return $str;
    }
}