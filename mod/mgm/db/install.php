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
 * This file replaces the legacy STATEMENTS section in db/install.xml,
 * lib.php/modulename_install() post installation hook and partially defaults.php
 *
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../locallib.php');
 
/**
 * Post installation procedure
 */
function xmldb_mgm_install() {
    global $DB;

    $result = true;    
    
    $result = mgm_create_especs();

    // Install default common logging actions
    update_log_display_entry('mgm', 'add', 'mgm', 'name');
    update_log_display_entry('mgm', 'update', 'mgm', 'name');
    update_log_display_entry('mgm', 'view', 'mgm', 'name');
    update_log_display_entry('mgm', 'view all', 'mgm', 'name');
    
    return $result;    
}


