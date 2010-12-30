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