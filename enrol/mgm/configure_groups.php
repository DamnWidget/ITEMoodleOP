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
 * @package    enrol
 * @subpackage mgm
 * @copyright  2010 - 2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");
require_once('configure_groups_form.php');

require_login();

require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

$id = optional_param('id', 0, PARAM_INT);    // Edition id
$courseid = optional_param('courseid', 0, PARAM_INT);
$done = optional_param('done', false, PARAM_BOOL);

if ($courseid) {
    if (!$course = get_record('course', 'id', $courseid)) {
        error('Course not known');
    }
}

if ($id) {
    if (!$edition = get_record('edicion', 'id', $id)) {
        error('Edicion not known');
    }
}

if ($id && $courseid) {
    $criteria = mgm_get_edition_course_criteria($id, $courseid);
    if (!$groups = get_records('groups', 'courseid', $courseid)) {
        error('No groups found');
    }
} else {
    error('Edition and Course id required');
}

if ($frm = data_submitted() and confirm_sesskey()) {
    foreach ($frm->groups as $group) {
        if (!$userid = clean_param($group['tutor'], PARAM_INT)) {
            continue;
        }

        if (!groups_add_member($group['data'], $userid)) {
            print_error('erroraddremoveuser', 'group');
        }

        $ggroup = get_record('groups', 'id', $group['data']);
        $ggroup->name = $group['name'];
        $ggroup->description = $group['name'];
        if(!groups_update_group($ggroup)) {
            error('Error saving the group');
        }
    }

    redirect('aprobe_requests.php');
}

$posiblemembers = array();

// Print the page and form
$strgroups = get_string('groups');
$strparticipants = get_string('participants');
$stradduserstogroup = get_string('adduserstogroup', 'group');
$strusergroupmembership = get_string('usergroupmembership', 'group');

$navlinks = array();
$navlinks[] = array('name' => $edition->name, 'link' => "$CFG->wwwroot/enrol/mgm/aprobe_requests.php?id=$id", 'type' => 'misc');
$navlinks[] = array('name' => $course->shortname, 'type' => 'misc');
$navlinks[] = array('name' => $strgroups, 'link' => "$CFG->wwwroot/group/index.php?id=$courseid", 'type' => 'misc');
$navlinks[] = array('name' => $stradduserstogroup, 'link' => null, 'type' => 'misc');
$navigation = build_navigation($navlinks);

print_header("$course->shortname: $strgroups", $course->fullname, $navigation, '', '', true, '', user_login_string($course, $USER));
?>
<script type="text/javascript">
//<![CDATA[
function updateGroupName(elId, userId, gId) {
    var nameEl = document.getElementById(elId);
    var target = document.getElementById('tutor_'+userId);
    if (!target) {
        target = document.getElementById('none_'+gId);
    }
    nameEl.value = target.text;
}
//]]>
</script>
<div id="groupsform">
    <h3 class="main"><?php print_string('addtutortogroup', 'mgm');?></h3>
    <form id="assignform" method="post" action="">
        <div>
            <input type="hidden" name="sesskey" value="<?php p(sesskey()); ?>" />
            <input type="hidden" name="id" value="<?php p($id); ?>" />
            <input type="hidden" name="courseid" value="<?php p($courseid); ?>" />
            <table cellpadding="6" class="generaltable generalbox groupmanagementtable boxaligncenter" summary="">
            <?php foreach($groups as $group) {
                $gname = preg_split('/ /', $group->name, -1);
                $groupoptions = groups_get_users_not_in_group_by_role($courseid, $group->id);
                $memberoptions = '';
                if (!empty($groupoptions)) {
                    $memberoptions .= '<optgroup label="'.get_string('none').'">';
                    $memberoptions .= '<option id="none_'.$gname[1].'" value="'.$group->name.'">'.$group->name.'</option>';
                    foreach($groupoptions as $roleid=>$roledata) {
                        $memberoptions .= '<optgroup label="'.htmlspecialchars($roledata->name).'">';
                        foreach($roledata->users as $member) {
                            $name=htmlspecialchars(fullname($member, true));
                            $memberoptions .= '<option id="tutor_'.$member->id.'" value="'.$member->id.
                    		'" title="'.$name.'">'.$name.'</option>';
                            $posiblemembers[$member->id] = $member;
                        }
                        $memberoptions .= '</optgroup>';
                    }
                } else {
                    $memberoptions .= '<option>&nbsp;</option>';
                }?>
                <tr>
                    <td valign="top">
                        <p>
                            <label id="label[<?php echo $gname[1] ?>]" for="<?php echo $gname ?>"><?php echo $group->name ?></label>
                        </p>
                        <input type="text" id="groups[<?php echo $gname[1] ?>][name]" name="groups[<?php echo $gname[1] ?>][name]" readonly=1 value="<?php echo $group->name ?>" />
                    </td>
                    <td valign="top">
                        <p>
                            <label for="groups[<?php echo $gname[1] ?>][tutor]"><?php print_string('tutor', 'mgm'); ?></label>
                        </p>
                        <select name="groups[<?php echo $gname[1] ?>][tutor]" id="groups[<?php echo $gname[1] ?>][tutor]" onchange="updateGroupName('groups[<?php  echo $gname[1] ?>][name]', this.value, '<?php echo $gname[1]; ?>');"
                        onfocus="updateGroupName('groups[<?php  echo $gname[1] ?>][name]', this.value, '<?php echo $gname[1]; ?>');">
                          <?php echo $memberoptions ?>
                        </select>
                        <input type="hidden" name="groups[<?php echo $gname[1] ?>][data]" value="<?php echo $group->id ?>" />
                    </td>
                </tr>
            <?php } ?>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="<?php print_string('submit'); ?>" />
                </td>
            </tr>
            </table>
        </div>
    </form>
</div>

<?php
print_footer();
