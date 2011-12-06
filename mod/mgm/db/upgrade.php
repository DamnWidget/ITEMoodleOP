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

    if ($result && $oldversion < 2011062700) {

        /// Define field certified to be added to edicion
        $table = new XMLDBTable('edicion');
        $field = new XMLDBField('certified');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '1', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'active');

        /// Launch add field timemodified
        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011062700) {

        /// Define field fechaemision to be added to edicion
        $table = new XMLDBTable('edicion');
        $field = new XMLDBField('fechaemision');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null, 'certified' );

        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011063000) {

        $table = new XMLDBTable('edicion_cert_history');
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null, null);
        $table->addFieldInfo('courseid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'id');
        $table->addFieldInfo('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'courseid');
        $table->addFieldInfo('edicionid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'userid');
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

        $result = create_table($table);
    }

    if ($result && $oldversion < 2011070600) {
        $table = new XMLDBTable('edicion_cert_history');
        $field = new XMLDBField('roleid');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, null, 'edicionid');

        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011070701) {
        $table = new XMLDBTable('edicion_cert_history');
        $field = new XMLDBField('courseid');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'id');

        $result = $result && change_field_type($table, $field);
    }

    if ($result && $oldversion < 2011071200) {
      $table = new XMLDBTable('edicion_user');
      $field = new XMLDBField('codpais');
      $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, null, 'sexo');

      /// Launch add field codpais
      $result = $result && add_field($table, $field);

      $field = new XMLDBField('codprovincia');
      $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, null, 'codpais');

      /// Launch add field codprovincia
      $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011071400) {
        $table = new XMLDBTable('edicion_course');
        $field = new XMLDBField('codactividad');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'fechainimodalidad');

        $result = $result && add_field($table, $field);

        $table = new XMLDBTable('edicion_cert_history');
        $field = new XMLDBField('numregistro');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'roleid');

        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011071500) {
        $table = new XMLDBTable('edicion_cert_history');
        $field = new XMLDBField('confirm');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '2', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, null, null, '0', 'numregistro');

        $result = $result && add_field($table, $field);
    }

    if ($result && $oldversion < 2011090900) {
        /// Define index (not unique) to be added to edicion_user
        $table = new XMLDBTable('edicion_user');
        $index = new XMLDBIndex('userid');
        $index->setAttributes(XMLDB_INDEX_NOTUNIQUE, array('userid'));

        /// Launch add index userid
        $result = $result && add_index($table, $index);
    }

    if ($result && $oldversion < 2011092000) {
        $table = new XMLDBTable('edicion_centro');

        $field = new XMLDBField('cp');
        $field->setAttributes(XMLDB_TYPE_CHAR, '5', null, null, null, null, null, null, 'direccion');

        $result = $result && add_field($table, $field);

        $field = new XMLDBField('provincia');
        $field->setAttributes(XMLDB_TYPE_TEXT, 'big', null, null, null, null, null, null, 'cp');

        $result = $result && add_field($table, $field);

        $field = new XMLDBField('telefono');
        $field->setAttributes(XMLDB_TYPE_CHAR, '9', null, null, null, null, null, null, 'provincia');

        $result = $result && add_field($table, $field);
    }
 if ($result && $oldversion < 2011120100) {

   #Add edition_user fields
      $table = new XMLDBTable('edicion_user');
      $field = new XMLDBField('codpais');
      $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, '724', 'sexo');

      /// Launch add field codpais
      $result = $result && add_field($table, $field);

      $field = new XMLDBField('codprovincia');
      $field->setAttributes(XMLDB_TYPE_CHAR, '3', null, null, null, null, null, null, 'codpais');

      /// Launch add field codprovincia
      $result = $result && add_field($table, $field);

   #Add some record for report
		    $rec = new stdClass;
        $rec->name = 'Información de ediciones';
        $rec->summary = 'Listado de datos estadisticos de usuarios en una edición';
        $rec->type= 'sql';
        $rec->jsordering= 1;
        $rec->visible= 1;
        $rec->components='a:4:{s:9:"customsql";a:1:{s:6:"config";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:8:"querysql";s:1950:"select+cmat.fullname+Curso%2C+cmat.fechainicio+as+Inicio%2C+cmat.fechafin+as+Fin%2C+cmat.matriculados+as+Matriculados%2C+cpre.presentados+as+Presentados%2C+ce1.e1+as+E1%2C+cec.ec+as+Ecuador%2C+ccert.certificados+as+Certificados+from+%0D%0A++%28SELECT+c.id%2C+c.fullname%2C+date_format%28from_unixtime%28ec.fechainicio%29%2C+%5C%27%25d%2F%25m%2F%25Y%5C%27%29+as+fechainicio%2C+date_format%28from_unixtime%28ec.fechafin%29%2C+%5C%27%25d%2F%25m%2F%25Y%5C%27%29+as+fechafin+%2C+count%28ei.value%29+as+matriculados+FROM+prefix_edicion_inscripcion+ei%2C+prefix_course+c%2C+prefix_edicion_course+ec+where+ei.value%3Dc.id+and+ec.courseid%3Dc.id+%0D%0A%25%25FILTER_EDITIONS%3Aei.edicionid%25%25%0D%0A%25%25FILTER_CATEGORIES%3Ac.category%25%25%0D%0A%25%25FILTER_COURSES%3Ac.id%25%25%0D%0A+group+by+fullname%2C+startdate++order+by+c.id%29+as+cmat%0D%0A++left+join+%28SELECT+courseid%2C+count%28itemid%29+as+presentados+FROM+prefix_grade_grades+gg%2C+prefix_grade_items+gi+where+gg.itemid%3Dgi.id+and+itemname+like+%5C%27pre%25%5C%27+and+finalgrade%3D2+group+by+courseid%29+as+cpre+on+%28cmat.id%3Dcpre.courseid%29%0D%0A++left+join+%28SELECT+courseid%2C+count%28itemid%29+as+e1+FROM+prefix_grade_grades+gg%2C+prefix_grade_items+gi+where+gg.itemid%3Dgi.id+and+itemname+like+%5C%27e1%25%5C%27+and+finalgrade+%3D+2+group+by+courseid+order+by+courseid%29+as+ce1+on+%28cmat.id%3Dce1.courseid%29%0D%0Aleft+join+%28SELECT+courseid%2C+count%28itemid%29+as+ec+FROM+prefix_grade_grades+gg%2C+prefix_grade_items+gi+where+gg.itemid%3Dgi.id+and+itemname+like+%5C%27ec%25%5C%27+and+finalgrade+%3D+2+group+by+courseid+order+by+courseid%29+as+cec+on+%28cmat.id%3Dcec.courseid%29+%0D%0A++left+join+%28SELECT+courseid%2C+count%28itemid%29+as+certificados+FROM+prefix_grade_grades+gg%2C+prefix_grade_items+gi+where+gg.itemid%3Dgi.id+and+itemname+like+%5C%27cert%25%5C%27+and+finalgrade+%3D+2+group+by+courseid%29+ccert+on+%28cmat.id+%3Dccert.courseid%29%0D%0Aorder+by+cmat.fullname";s:12:"submitbutton";s:15:"Guardar+cambios";}}s:7:"filters";a:1:{s:8:"elements";a:3:{i:0;a:5:{s:2:"id";s:15:"ijr0IlRl46iaSOm";s:8:"formdata";O:6:"object":0:{}s:10:"pluginname";s:8:"editions";s:14:"pluginfullname";s:9:"Ediciones";s:7:"summary";s:98:"Este+filtro+muestra+una+lista+de+ediciones.+Solo+se+puede+seleccionar+una+ediciona+al+mismo+tiempo";}i:1;a:5:{s:2:"id";s:15:"5HNJ9M7wQnTRxAk";s:8:"formdata";O:6:"object":0:{}s:10:"pluginname";s:10:"categories";s:14:"pluginfullname";s:25:"Filtro+de+categor%C3%ADas";s:7:"summary";s:31:"Para+filtrar+por+categor%C3%ADa";}i:2;a:5:{s:2:"id";s:15:"CShdpgWcsPC7T5J";s:8:"formdata";O:6:"object":0:{}s:10:"pluginname";s:7:"courses";s:14:"pluginfullname";s:6:"Cursos";s:7:"summary";s:87:"Este+filtro+muestra+una+lista+de+cursos.+S%C3%B3lo+un+curso+puede+seleccionado+a+la+vez";}}}s:11:"permissions";a:1:{s:6:"config";O:6:"object":1:{s:13:"conditionexpr";s:0:"";}}s:5:"calcs";a:1:{s:8:"elements";a:5:{i:0;a:5:{s:2:"id";s:15:"n94q3SS2BSWSkRU";s:8:"formdata";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:6:"column";s:1:"3";s:12:"submitbutton";s:7:"Agregar";}s:10:"pluginname";s:3:"sum";s:14:"pluginfullname";s:4:"Suma";s:7:"summary";s:12:"Matriculados";}i:1;a:5:{s:2:"id";s:15:"pT0FhrbZt9WhUIB";s:8:"formdata";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:6:"column";s:1:"4";s:12:"submitbutton";s:7:"Agregar";}s:10:"pluginname";s:3:"sum";s:14:"pluginfullname";s:4:"Suma";s:7:"summary";s:11:"Presentados";}i:2;a:5:{s:2:"id";s:15:"bTrdYqyZD9uQKCn";s:8:"formdata";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:6:"column";s:1:"5";s:12:"submitbutton";s:7:"Agregar";}s:10:"pluginname";s:3:"sum";s:14:"pluginfullname";s:4:"Suma";s:7:"summary";s:2:"E1";}i:3;a:5:{s:2:"id";s:15:"sTXIJ6ruGMxU9Cc";s:8:"formdata";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:6:"column";s:1:"6";s:12:"submitbutton";s:7:"Agregar";}s:10:"pluginname";s:3:"sum";s:14:"pluginfullname";s:4:"Suma";s:7:"summary";s:7:"Ecuador";}i:4;a:5:{s:2:"id";s:15:"svbXk9lXQjq27Qb";s:8:"formdata";O:6:"object":3:{s:13:"MAX_FILE_SIZE";s:7:"2097152";s:6:"column";s:1:"7";s:12:"submitbutton";s:7:"Agregar";}s:10:"pluginname";s:3:"sum";s:14:"pluginfullname";s:4:"Suma";s:7:"summary";s:12:"Certificados";}}}}';
        $rec->export='ods,xls,';
        global $USER;
        $rec->ownerid=$USER->id;
        $rec->courseid=SITEID;


        $retid = insert_record('block_configurable_reports_report', $rec);
   			if ($retid)	{
   				$rec = new stdClass;
        	$rec->name = 'Report001';
        	$rec->type = '5';
        	$rec->value = $retid;
        	$result = insert_record('edicion_ite', $rec);
   			}
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
