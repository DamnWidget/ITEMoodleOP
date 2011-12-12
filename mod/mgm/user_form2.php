<?php //$Id: user_bulk_forms.php,v 1.1.2.3 2007/12/20 10:54:07 skodak Exp $

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/datalib.php');

class user_mgm_form2 extends moodleform {
    function definition() {

        $mform =& $this->_form;
        $acount =& $this->_customdata['acount'];
        $ausers =& $this->_customdata['ausers'];
        $total  =& $this->_customdata['total'];

        $achoices = array();

        if (is_array($ausers)) {
            if ($total == $acount) {
                $achoices[0] = get_string('allusers', 'bulkusers', $total);
            } else {
                $a = new object();
                $a->total  = $total;
                $a->count = $acount;
                $achoices[0] = get_string('allfilteredusers', 'bulkusers', $a);
            }
            $achoices = $achoices + $ausers;

            if ($acount > MAX_BULK_USERS) {
                $achoices[-1] = '...';
            }

        } else {
            $achoices[-1] = get_string('nofilteredusers', 'bulkusers', $total);
        }

				if(! $sourceuser){
					$sourceuser=get_string('noselectedsourceuser');
				}
				if(! $destinationuser){
					$destinationuser=get_string('noselecteddestinationuser');
				}

        $mform->addElement('header', 'users', get_string('usersinlist', 'bulkusers'));

        $objs = array();
        $objs[0] =& $mform->createElement('select', 'ausers', '', $achoices, 'size="15"');
        $grp =& $mform->addElement('group', 'usersgrp', get_string('users'), $objs, ' ', false);
        $grp->setHelpButton(array('lists', get_string('users'), 'bulkusers'));
        $mform->addElement('static', 'comment');
        $objs[0] =& $mform->createElement('submit', 'cancel', get_string('cancel'));
        $objs[1] =& $mform->createElement('submit', 'next', get_string('next'));
        $mform->addElement('group', 'actionsgrp', '', $objs, ' ', false);
        $renderer =& $mform->defaultRenderer();
        $template = '<label class="qflabel" style="vertical-align:top">{label}</label> {element}';
        $renderer->setGroupElementTemplate($template, 'usersgrp');
    }
}
?>
