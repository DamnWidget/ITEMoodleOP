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
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/mod/mgm/locallib.php');

/*
 * Module Configuration
 */
$settings->add(
    new admin_setting_configcheckbox('mgm_email_notification', get_string('emailnotification', 'mgm'),
        get_string('configemailnotification', 'mgm'), 0)
);

$choices = array('ccaa', 'especialidad', 'escuela20');

$settings->add(
    new admin_setting_configmultiselect('mgm_revision_information', get_string('revisioninformation', 'mgm'),
        get_string('configrevisioninformation', 'mgm'), array(), $choices)
);

$settings->add(
    new admin_setting_configfile('mgm_centros_file', get_string('centrosfile', 'mgm'),
        get_string('configcentrosfile', 'mgm'), $CFG->dirroot.'/mod/mgm/Centros.cvs')
);
