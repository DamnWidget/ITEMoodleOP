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
require_once($CFG->dirroot.'/mod/mgm/locallib.php');

/**
 * This is a one-line short description of the file
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    block
 * @subpackage mgm
 * @copyright  2010 Oscar Campos <oscar.campos@open-phoenix.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_mgm extends block_base {
    function init() {
        $this->title = get_string('controlpanel', 'mgm');
        $this->version = 2010121901;
    }

    function applicable_formats() {
        return array('site' => true);
    }

    function get_content () {
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content->footer = '';
        $this->content->text = '<div>';

        $this->content->text .= "\n".'<ul>';
        if (mgm_can_do_aprobe()) {
            $this->content->text = '<li><a href="enrol/mgm/aprobe_requests.php">Aprobar Inscripciones</a></li>';
        }
        $this->content->text .= '<li><a href="mod/mgm/user.php">Editar información docente</a></li>';
        $this->content->text .= '<li><a href="enrol/mgm/show_requests.php">Ver Preinscripciones</a></li>';
        $this->content->text .= '</ul>'."\n";
        $this->content->text .= '</div>';

        return $this->content;
    }
}