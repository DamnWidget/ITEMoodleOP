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

/** Configurable Reports
  * A Moodle block for creating customizable reports
  * @package blocks
  * @author: Juan leyva <http://www.twitter.com/jleyvadelgado>
  * @date: 2009
  */

require_once($CFG->dirroot.'/blocks/configurable_reports/plugin.class.php');

class plugin_editions extends plugin_base{

	function init(){
		$this->form = false;
		$this->unique = true;
		$this->fullname = get_string('filtereditions','mgm');
		$this->reporttypes = array('courses','sql');
	}

	function summary($data){
		return get_string('filtereditions_summary','mgm');
	}

	function execute($finalelements, $data){

		$filter_editions = optional_param('filter_editions',0,PARAM_INT);
		if(!$filter_editions)
			return $finalelements;

		if($this->report->type != 'sql'){
				return array($filter_editions);
		}
		else{
			if(preg_match("/%%FILTER_EDITIONS:([^%]+)%%/i",$finalelements,
    $output)){
				$replace = ' AND '.$output[1].' = '.$filter_editions;
				return str_replace('%%FILTER_EDITIONS:'.$output[1].'%%',$replace,$finalelements);
			}
		}
		return $finalelements;
	}

	function print_filter(&$mform){
		global $CFG;

		$filter_editions = optional_param('filter_editions',0,PARAM_INT);

		$reportclassname = 'report_'.$this->report->type;
		$reportclass = new $reportclassname($this->report);

		if($this->report->type != 'sql'){
			$components = cr_unserialize($this->report->components);
			$conditions = $components['conditions'];

			$editionlist = $reportclass->elements_by_conditions($conditions);
		}
		else{
			$editionlist = array_keys(get_records('edicion'));
		}

		$editionoptions = array();
		$editionoptions[0] = get_string('choose');

		if(!empty($editionlist)){
			$editions = get_records_select('edicion','id in ('.(implode(',',$editionlist)).')');

			foreach($editions as $c){
				$editionoptions[$c->id] = format_string($c->name);
			}
		}

		$mform->addElement('select', 'filter_editions', get_string('edition', 'mgm'), $editionoptions);
		$mform->setType('filter_editions', PARAM_INT);

	}

}

?>