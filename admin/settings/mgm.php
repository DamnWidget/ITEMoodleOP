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
 * MGM Admin settings
 *
 * @package    mod
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$ADMIN->add('root', new admin_category('ediciones', get_string('ediciones','mgm')));

$ADMIN->add(
	'ediciones', new admin_externalpage('edicionesmgmt', get_string('edicionesmgmt', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/index.php?editionedit=on', 'mod/mgm:createedicion')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionescoursemgmt', get_string('edicionescoursemgmt', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/courses.php', array('moodle/course:update', 'mod/mgm:assigncriteria'))
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionesaprobe', get_string('edicionesaprobe', 'mgm'),
        $CFG->wwwroot . '/enrol/mgm/aprobe_requests.php', 'mod/mgm:aprobe')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionescert', get_string('edicionescert', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/user_certifications.php', 'mod/mgm:aprobe')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionesaddress', get_string('edicionesaddress', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/user_extend.php', 'mod/mgm:aprobe')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('reviewnotaprobed', get_string('reviewnotaprobed', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/review.php', 'mod/mgm:aprobe')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionesscala', get_string('edicionesscala', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/edicionesscala.php', 'mod/mgm:createedicion')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('edicionesrole', get_string('edicionesrole', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/roles.php', 'mod/mgm:createedicion')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('exportdata', get_string('exportdata', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/export.php', 'mod/mgm:aprobe')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('especdata', get_string('especdata', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/espec.php', 'mod/mgm:createedicion')
);

$ADMIN->add('ediciones', new admin_category('mgmreports', get_string('reports', 'mgm')));

$ADMIN->add(
    'mgmreports', new admin_externalpage('dinamicinfo', get_string('dinamicinfo', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/report.php?report_type=Report001', 'mod/mgm:createedicion')
);

$ADMIN->add(
    'ediciones', new admin_externalpage('fees', get_string('fees', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/fees.php', 'mod/mgm:aprobe')
);
$ADMIN->add('ediciones', new admin_category('mgmcertifications', get_string('certifications', 'mgm')));

$ADMIN->add(
    'mgmcertifications', new admin_externalpage('joinusers', get_string('joinusers', 'mgm'),
        $CFG->wwwroot . '/mod/mgm/join_users.php', 'mod/mgm:createedicion')
);