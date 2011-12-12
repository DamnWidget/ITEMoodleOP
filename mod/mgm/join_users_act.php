<?php //$Id: user_bulk_delete.php,v 1.3.2.1 2007/11/13 09:02:12 skodak Exp $
/**
* script for bulk user delete operations
*/

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);

admin_externalpage_setup('joinusers');
require_capability('moodle/user:delete', get_context_instance(CONTEXT_SYSTEM));

$return = $CFG->wwwroot.'/mod/mgm/join_users.php';

if (empty($SESSION->sourceuser) or empty($SESSION->destinationuser)) {
    redirect($return);
}

admin_externalpage_print_header();
$SESSION->bulk_users=array();
$SESSION->bulk_users[$SESSION->sourceuser] = $SESSION->sourceuser;
$SESSION->bulk_users[$SESSION->destinationuser] = $SESSION->destinationuser;

//TODO: add support for large number of users

if ($confirm and confirm_sesskey()) {
    $primaryadmin = get_admin();
    //Realizar la fusion de usuario y notificar los cambios realizados:

		print 'Realizar fusion de usuarios ... ';
//    $in = implode(',', $SESSION->bulk_users);
//    if ($rs = get_recordset_select('user', "id IN ($in)")) {
//        while ($user = rs_fetch_next_record($rs)) {
//            //if ($primaryadmin->id != $user->id and $USER->id != $user->id and delete_user($user)) {
//            	if ($primaryadmin->id != $user->id and $USER->id != $user->id) {//un usuario no se puede borrar a si mismo ni al administrador original
//            	  print $SESSION->bulk_users[$user->id];
//                //unset($SESSION->bulk_users[$user->id]);
//            } else {
//                notify(get_string('deletednot', '', fullname($user, true)));
//            }
//        }
//        rs_close($rs);
//    }
//    redirect($return, get_string('changessaved'));

} else {
//    $in = implode(',', $SESSION->bulk_users);
//    $userlist = get_records_select_menu('user', "id IN ($in)", 'fullname', 'id,'.sql_fullname().' AS fullname');
//    $usernames = implode(', ', $userlist);
    $optionsyes = array();
    $optionsyes['confirm'] = 1;
    $optionsyes['sesskey'] = sesskey();
//    print_heading(get_string('confirmation', 'admin'));

    //mostrar los cambios que se van a realizar en los usuarios
    //pedir confirmacion
    print 'Mostrar cambios que realizará la fusión ... ';
    notice_yesno(get_string('joinusercheck'), 'join_users_act.php', 'join_users.php', $optionsyes, NULL, 'post', 'get');
}

admin_externalpage_print_footer();
?>
