<?php //$Id: user_bulk_delete.php,v 1.3.2.1 2007/11/13 09:02:12 skodak Exp $
/**
* script for bulk user delete operations
*/

require_once('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/mod/mgm/locallib.php');

$confirm = optional_param('confirm', 0, PARAM_BOOL);
$confirm2 = optional_param('confirm2', 0, PARAM_BOOL);

admin_externalpage_setup('joinusers');
require_capability('moodle/user:delete', get_context_instance(CONTEXT_SYSTEM));

$return = $CFG->wwwroot.'/mod/mgm/join_users.php';

if (empty($SESSION->sourceuser) or empty($SESSION->destinationuser)) {
    redirect($return);
}

admin_externalpage_print_header();
$user_orig=$SESSION->sourceuser;
$user_dest=$SESSION->destinationuser;
$SESSION->joinusers=new JoinUsers();
$SESSION->joinusers->setUserId($user_orig, 'orig');
$SESSION->joinusers->setUserId($user_dest, 'dest');
$SESSION->joinusers->addDiff();

if ($confirm and confirm_sesskey()) {
    //notificar los cambios a realizar
    if ($user_orig == get_admin()->id){
    	error(get_string('noadminsource', 'mgm'), $CFG->wwwroot.'/mod/mgm/join_users.php');
    }
    if ( $USER->id == $user_orig->id){
    	error(get_string('nologinusersource', 'mgm'), $CFG->wwwroot.'/mod/mgm/join_users.php');
    }
		if (isset($SESSION->joinusers)){
			$keys2set=array();
			foreach ($_POST as $k=>$v){
				if (is_int($k)){
					$keys2set[]=$v;
				}
			}
			$SESSION->keys2set=$keys2set;
			$SESSION->joinusers->setDiffVals($keys2set);
			print_heading('Acciones a realizar:');
			print_heading( "1.- Se eliminará el usuario origen (" . fullname($SESSION->joinusers->user_orig, true)." ,".$SESSION->joinusers->user_orig->id." )",'left', 4);
			print_heading( "2.- Se estableceran los siguientes datos en el usuario destino (". fullname($SESSION->joinusers->user_dest, true) ." ," .$SESSION->joinusers->user_dest->id." )", 'left', 4);
		  print_table($SESSION->joinusers->getSaveTable());
			if ($cert_orig=mgm_get_cert_history($SESSION->joinusers->user_orig->id)){
		  	print_heading( "3.- EL usuario destino certificará los siguientes cursos: \n", 'left', 4);
		  	print_table($SESSION->joinusers->getCertOrigTable());
			}
			echo '<br />';
			echo '<br />';
			echo '<br />';
			$optionsyes = array();
      $optionsyes['confirm2'] = 1;
    	$optionsyes['sesskey'] = sesskey();
			notice_yesno(get_string('joinusercheck', 'mgm'), 'join_users_act.php', 'join_users.php', $optionsyes, NULL, 'post', 'get');
		}

} else if ($confirm2 and confirm_sesskey()){
	//Realizar la fusión de usuario
	if (isset($SESSION->joinusers)){
		$SESSION->joinusers->setDiffVals($SESSION->keys2set);
		$SESSION->joinusers->save();
		unset($SESSION->joinusers);
		redirect($return, get_string('changessaved'));
	}
} else{
		if ($user_orig == $user_dest){
    	error(get_string('sameusers', 'mgm'), $CFG->wwwroot.'/mod/mgm/join_users.php');
    }
    $SESSION->joinusers->displayDiffForm($SESSION->joinusers->getDiffTable());

}
admin_externalpage_print_footer();
?>