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
 * @copyright  2011 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot."/mod/mgm/locallib.php");

require_login();

require_capability('mod/mgm:aprobe', get_context_instance(CONTEXT_SYSTEM));

define("MAX_USERS_PER_PAGE", 5000);

$search         = optional_param('search', '', PARAM_RAW); // search button
$searchtext     = optional_param('searchtext', '', PARAM_RAW); // search string
$previoussearch = optional_param('previoussearch', 0, PARAM_BOOL);

$strediciones     = get_string('ediciones', 'mgm');
$strperfil        = get_string('profile');
$strsearch        = get_string('search');
$strsearchresults = get_string('searchresults');

$navlinks = array();
$navlinks[] = array('name' => $strediciones, 'type' => 'misc');
$navlinks[] = array('name' => $strperfil, 'type' => 'misc');

$navigation = build_navigation($navlinks);

if ($frm = data_submitted() and confirm_sesskey()) {
    if (!empty($frm->addselect) && empty($search)) {
        if (!$user = get_record('user', 'id', $_REQUEST['addselect'][0])) {
            error('User doesn\'t exists!');
        } else {
            print_header("", get_string('edicionesaddress', 'mgm'), $navigation);
            print_simple_box_start('center');
        ?>
<form id="assignform" method="post" action="">
    <div style="text-align: center;">
        <input type="hidden" name="sesskey" value="<?php p(sesskey()) ?>" />
        <input type="hidden" name="userid" value="<?php p($user->id) ?>" />
        <table summary="" style="margin-left:auto;margin-right:auto" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td valign="top">
                    <label for="address"><?php print_string('newaddress', 'mgm'); ?></label>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <textarea id="address" name="address" rows=3 cols=45>
                    <?php
                        if ($euser = get_record('edicion_user', 'userid', $user->id)) {
                            p($euser->address);
                        }
                    ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <input name="finish" id="finish" type="submit" value="<?php p(get_string('savechanges')); ?>" />
                    <input name="back" id="back" type="submit" value="<?php p(get_string('back')); ?>" />
                </td>
            </tr>
        </table>
    </div>
</form>
        <?php
            print_simple_box_end();
            die();
        }
    }

    if (!empty($frm->finish)) {
        mgm_edition_set_user_address($frm->userid, trim($frm->address));
        redirect('user_extend.php', get_string('sysnewaddress', 'mgm', 5));
    }
}

$previoussearch = ($searchtext != '') or ($previoussearch) ? 1:0;

$baseurl = 'user_extend.php';

$select  = "username <> 'guest' AND deleted = 0 AND confirmed = 1";

if ($searchtext !== '') {   // Search for a subset of remaining users
    $LIKE      = sql_ilike();
    $FULLNAME  = sql_fullname();

    $selectsql = " AND ($FULLNAME $LIKE '%$searchtext%' OR email $LIKE '%$searchtext%') ";
    $select  .= $selectsql;
} else {
    $selectsql = '';
}

$navlinks = array();
$navlinks[] = array('name' => $strediciones, 'type' => 'misc');
$navlinks[] = array('name' => $strperfil, 'type' => 'misc');

$navigation = build_navigation($navlinks);

print_header("", get_string('edicionesaddress', 'mgm'), $navigation);

$searchtext = trim($searchtext);

if ($searchtext !== '') {
    $LIKE = sql_ilike();
    $FULLNAME = sql_fullname();

    $selectsql = " AND (".$FULLNAME." ".$LIKE." '%".$searchtext."%' OR email ".$LIKE." '%".$searchtext."%') ";
    $select .= $selectsql;
}

$sql = 'SELECT id, firstname, lastname, email
		FROM '.$CFG->prefix.'user
        WHERE '.$select.'
        ORDER BY lastname ASC, firstname ASC';
$availableusers = get_recordset_sql($sql);

$usercount = $availableusers->_numOfRows;

print_simple_box_start('center');
?>
<form id="assignform" method="post" action="">
    <div style="text-align: center;">
        <input type="hidden" name="previoussearch" value="<?php p($previoussearch) ?>" />
        <input type="hidden" name="sesskey" value="<?php p(sesskey()) ?>" />
        <table summary="" style="margin-left:auto;margin-right:auto" border="0" cellpadding="5" cellspacing="0">
            <tr>
                <td valign="top">
                    <label for="addselect"><?php print_string('userselect', 'mgm'); ?></label>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <select name="addselect[]" size="20" id="addselect">
                    <?php
                        $i = 0;
                        if (!empty($searchtext)) {
                            echo "<optgroup label=\"$strsearchresults (" . $usercount . ")\">\n";
                            while ($user = rs_fetch_next_record($availableusers)) {
                                $fullname = fullname($user, true);
                                echo "<option value=\"$user->id\">".$fullname.", ".$user->email."</option>\n";
                                $i++;
                            }
                            echo "</optgroup>\n";
                        } else {
                            if ($usercount > MAX_USERS_PER_PAGE) {
                                echo '<optgroup label="'.get_string('toomanytoshow').'"><option></option></optgroup>'."\n"
                                  .'<optgroup label="'.get_string('trysearching').'"><option></option></optgroup>'."\n";
                            } else {
                                while ($user = rs_fetch_next_record($availableusers)) {
                                    $fullname = fullname($user, true);
                                    echo "<option value=\"$user->id\">".$fullname.", ".$user->email."</option>\n";
                                    $i++;
                                }
                            }
                            if ($i==0) {
                                echo '<option/>'; // empty select breaks xhtml strict
                            }
                         }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <label for="searchtext" class="accesshide"><?php p($strsearch) ?></label>
                    <input type="text" name="searchtext" id="searchtext" size="30" value="<?php p($searchtext, true) ?>" />
                    <input name="search" id="search" type="submit" value="<?php p($strsearch) ?>" />
                </td>
            </tr>
            <tr>
                <td valign="top">
                    <input name="next" id="next" type="submit" value="<?php p(get_string('next')); ?>" />
                </td>
            </tr>
        </table>
    </div>
</form>
<?php
print_simple_box_end();

