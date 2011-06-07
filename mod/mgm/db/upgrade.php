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
 * This file keeps track of upgrades to the mgm module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installtion to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in
 * lib/ddllib.php
 *
 * @package   mod_mgm
 * @copyright 2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_mgm_upgrade
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_mgm_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

/// And upgrade begins here. For each one, you'll need one
/// block of code similar to the next one. Please, delete
/// this comment lines once this file start handling proper
/// upgrade code.

/// if ($result && $oldversion < YYYYMMDD00) { //New version in version.php
///     $result = result of "/lib/ddllib.php" function calls
/// }

/// Lines below (this included)  MUST BE DELETED once you get the first version
/// of your module ready to be installed. They are here only
/// for demonstrative purposes and to show how the mgm
/// iself has been upgraded.

/// For each upgrade block, the file mgm/version.php
/// needs to be updated . Such change allows Moodle to know
/// that this file has to be processed.

/// To know more about how to write correct DB upgrade scripts it's
/// highly recommended to read information available at:
///   http://docs.moodle.org/en/Development:XMLDB_Documentation
/// and to play with the XMLDB Editor (in the admin menu) and its
/// PHP generation posibilities.

/// First example, some fields were added to the module on 20070400
    if ($result && $oldversion < 2007040100) {

    /// Define field course to be added to mgm
        $table = new XMLDBTable('mgm');
        $field = new XMLDBField('course');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'id');
    /// Launch add field course
        $result = $result && add_field($table, $field);

    /// Define field intro to be added to mgm
        $table = new XMLDBTable('mgm');
        $field = new XMLDBField('intro');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'medium', null, null, null, null, null, null, 'name');
    /// Launch add field intro
        $result = $result && add_field($table, $field);

    /// Define field introformat to be added to mgm
        $table = new XMLDBTable('mgm');
        $field = new XMLDBField('introformat');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'intro');
    /// Launch add field introformat
        $result = $result && add_field($table, $field);
    }

/// Second example, some hours later, the same day 20070401
/// two more fields and one index were added (note the increment
/// "01" in the last two digits of the version
    if ($result && $oldversion < 2007040101) {

    /// Define field timecreated to be added to mgm
        $table = new XMLDBTable('mgm');
        $field = new XMLDBField('timecreated');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'introformat');
    /// Launch add field timecreated
        $result = $result && add_field($table, $field);

    /// Define field timemodified to be added to mgm
        $table = new XMLDBTable('mgm');
        $field = new XMLDBField('timemodified');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'timecreated');
    /// Launch add field timemodified
        $result = $result && add_field($table, $field);

    /// Define index course (not unique) to be added to mgm
        $table = new XMLDBTable('mgm');
        $index = new XMLDBIndex('course');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('course'));
    /// Launch add index course
        $result = $result && add_index($table, $index);
    }

/// Third example, the next day, 20070402 (with the trailing 00), some inserts were performed, related with the module
    if ($result && $oldversion < 2007040200) {
    /// Add some actions to get them properly displayed in the logs
        $rec = new stdClass;
        $rec->module = 'mgm';
        $rec->action = 'add';
        $rec->mtable = 'mgm';
        $rec->filed  = 'name';
    /// Insert the add action in log_display
        $result = insert_record('log_display', $rec);
    /// Now the update action
        $rec->action = 'update';
        $result = insert_record('log_display', $rec);
    /// Now the view action
        $rec->action = 'view';
        $result = insert_record('log_display', $rec);
    }

    if ($result && $oldversion < 2011060700) {

    /// Define field codagrupacion to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codagrupacion');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '5', XMLDB_UNSIGNED, null, null, null, null, '1', 'courseid');

    /// Launch add field codagrupacion
        $result = $result && add_field($table, $field);

    /// Define field codmodalidad to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codmodalidad');
        $field->setAttributes(XMLDB_TYPE_CHAR, '2', null, null, null, XMLDB_ENUM, array('10', '20', '30', 'A0'), '10', 'codagrupacion');

    /// Launch add field codmodalidad
        $result = $result && add_field($table, $field);

    /// Define field codprovincia to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codprovincia');
        $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, null, 'codmodalidad');

    /// Launch add field codprovincia
        $result = $result && add_field($table, $field);
        
    /// Define field codpais to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codpais');
        $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, '724', 'codprovincia');

    /// Launch add field codpais
        $result = $result && add_field($table, $field);
        
    /// Define field codmateria to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codmateria');
        $field->setAttributes(XMLDB_TYPE_CHAR, '4', null, null, null, null, null, null, 'codpais');

    /// Launch add field codmateria
        $result = $result && add_field($table, $field);
        
    /// Define field codniveleducativo to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codniveleducativo');
        $field->setAttributes(XMLDB_TYPE_CHAR, '2', null, null, null, null, null, null, 'codmateria');

    /// Launch add field codniveleducativo
        $result = $result && add_field($table, $field);
        
    /// Define field numhoras to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('numhoras');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, null, null, null, null, null, 'codniveleducativo');

    /// Launch add field numhoras
        $result = $result && add_field($table, $field);
        
    /// Define field numcreditos to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('numcreditos');
        $field->setAttributes(XMLDB_TYPE_NUMBER, '3, 1', XMLDB_UNSIGNED, null, null, null, null, null, 'numhoras');

    /// Launch add field numcreditos
        $result = $result && add_field($table, $field);
        
    /// Define field fechainicio to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('fechainicio');
        $field->setAttributes(XMLDB_TYPE_CHAR, '10', null, null, null, null, null, null, 'numcreditos');

    /// Launch add field fechainicio
        $result = $result && add_field($table, $field);
        
    /// Define field fechafin to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('fechafin');
        $field->setAttributes(XMLDB_TYPE_CHAR, '10', null, null, null, null, null, null, 'fechainicio');

    /// Launch add field fechafin
        $result = $result && add_field($table, $field);
        
    /// Define field localidad to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('localidad');
        $field->setAttributes(XMLDB_TYPE_CHAR, '35', null, null, null, null, null, null, 'fechafin');

    /// Launch add field localidad
        $result = $result && add_field($table, $field);
        
    /// Define field fechainimodalidad to be added to edicion_course
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('fechainimodalidad');
        $field->setAttributes(XMLDB_TYPE_CHAR, '10', null, null, null, null, null, null, 'localidad');

    /// Launch add field fechainimodalidad
        $result = $result && add_field($table, $field);

    /// Define field alt_address to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('alt_address');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'especialidades');

    /// Launch add field alt_address
        $result = $result && add_field($table, $field);
        
    /// Define field address to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('address');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'alt_address');

    /// Launch add field address
        $result = $result && add_field($table, $field);
        
    /// Define field tipoid to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('tipoid');
        $field->setAttributes(XMLDB_TYPE_CHAR, '1', null, null, null, XMLDB_ENUM, array('N', 'P', 'T'), null, 'address');

    /// Launch add field tipoid
        $result = $result && add_field($table, $field);
        
    /// Define field codtipoparticipante to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('codtipoparticipante');
        $field->setAttributes(XMLDB_TYPE_CHAR, '2', null, null, null, XMLDB_ENUM, array('A', 'C', 'T'), null, 'tipoid');

    /// Launch add field codtipoparticipante
        $result = $result && add_field($table, $field);
        
    /// Define field codniveleducativo to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('codniveleducativo');
        $field->setAttributes(XMLDB_TYPE_CHAR, '2', null, null, null, null, null, null, 'codtipoparticipante');

    /// Launch add field codniveleducativo
        $result = $result && add_field($table, $field);
        
    /// Define field codcuerpodocente to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('codcuerpodocente');
        $field->setAttributes(XMLDB_TYPE_CHAR, '8', null, null, null, null, null, null, 'codniveleducativo');

    /// Launch add field codcuerpodocente
        $result = $result && add_field($table, $field);
        
    /// Define field codpostal to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('codpostal');
        $field->setAttributes(XMLDB_TYPE_CHAR, '5', null, null, null, null, null, null, 'codcuerpodocente');

    /// Launch add field codpostal
        $result = $result && add_field($table, $field);
        
    /// Define field sexo to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $field = new XMLDBField('sexo');
        $field->setAttributes(XMLDB_TYPE_CHAR, '1', null, null, null, XMLDB_ENUM, array('H', 'M'), null, 'codpostal');

    /// Launch add field sexo
        $result = $result && add_field($table, $field);
    }
/// And that's all. Please, examine and understand the 3 example blocks above. Also
/// it's interesting to look how other modules are using this script. Remember that
/// the basic idea is to have "blocks" of code (each one being executed only once,
/// when the module version (version.php) is updated.

/// Lines above (this included) MUST BE DELETED once you get the first version of
/// yout module working. Each time you need to modify something in the module (DB
/// related, you'll raise the version and add one upgrade block here.

/// Final return of upgrade result (true/false) to Moodle. Must be
/// always the last line in the script
    return $result;
}
