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
* Report settings
*
* @package    report
* @copyright  2024 CAPES/UFLA
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die();
require(__DIR__. '/../constants.php');

class report_coursestats_v2_observer {	
    public static function forum_discussion_created(\mod_forum\event\discussion_created $event) {
		global $DB;
		
		// Check if the forum instance is for announcements 
		$result = $DB->get_record(FORUM_TABLE_NAME_V2, array('id'=>$event->other['forumid']));
		if ($result->type === NEWS_FORUM_NAME_V2) {
			// Get the course, based on its id
			$course = $DB->get_record(COURSE_TABLE_NAME_V2, array('id'=>$event->courseid));
			
			/* 
			 * Check if there is no records for the 'courseid' in the table 'report_coursestatsv2'.
			 * If yes, a record is created with usage type classified as 'forum'. 
			 */
			if (!$DB->record_exists(PLUGIN_TABLE_NAME_V2, array('courseid'=>$event->courseid))) {
				$record = new stdClass();
				$record->courseid = $event->courseid;
				$record->prev_usage_type = NULL_USAGE_TYPE_V2;
				$record->curr_usage_type = FORUM_USAGE_TYPE_V2;
				$record->last_update = time();
				$DB->insert_record(PLUGIN_TABLE_NAME_V2, $record);
			} 					
		}
	}
	
	private static function handle_module($event) {
		global $DB;
       	
		/* 
		* If the module name is 'url', 'folder' or 'resource', then the usage type is 'repository'.
		* Otherwise, the usage type is 'activity' 
		*/
		$usage_type = '';
		if (in_array($event->other['modulename'], unserialize(REPOSITORY_MODULES_V2))) {
 			$usage_type	= REPOSITORY_USAGE_TYPE_V2;
		} else {
 			$usage_type	= ACTIVITY_USAGE_TYPE_V2;
		}

		// Get the course, based on its id
		$course = $DB->get_record(COURSE_TABLE_NAME_V2, array('id'=>$event->courseid));
				
		if (!$DB->record_exists(PLUGIN_TABLE_NAME_V2, array('courseid'=>$event->courseid))) {
 			$record = new stdClass();
 			$record->courseid = $event->courseid;
 			$record->prev_usage_type = NULL_USAGE_TYPE_V2;
 			$record->curr_usage_type = $usage_type;			 
 			$record->last_update =  time();
 			$record->categoryid = $course->category;
 			$DB->insert_record(PLUGIN_TABLE_NAME_V2, $record);
		} else {
 			$result = $DB->get_record(PLUGIN_TABLE_NAME_V2, array('courseid'=>$event->courseid));
 			if ($result->curr_usage_type === FORUM_USAGE_TYPE_V2 or 
	 			($result->curr_usage_type === REPOSITORY_USAGE_TYPE_V2 and $usage_type === ACTIVITY_USAGE_TYPE_V2)) {
	 			$result->prev_usage_type = $result->curr_usage_type;
	 			$result->curr_usage_type = $usage_type;		
	 			$result->categoryid = $course->category;
	 			$result->last_update =  time();
	 			$DB->update_record(PLUGIN_TABLE_NAME_V2, $result); 
 			}			
		}
	}

	public static function course_module_created(\core\event\course_module_created $event) {
		self::handle_module($event);
	}

	public static function course_module_updated(\core\event\course_module_updated $event) {
		self::handle_module($event);
	}
}