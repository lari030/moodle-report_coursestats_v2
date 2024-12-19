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

defined('MOODLE_INTERNAL') || die;

define('NULL_USAGE_TYPE_V2', 'null');
define('FORUM_USAGE_TYPE_V2', 'forum');
define('REPOSITORY_USAGE_TYPE_V2', 'repository');
define('ACTIVITY_USAGE_TYPE_V2', 'activity');
define('PLUGIN_TABLE_NAME_V2', 'report_coursestatsv2');
define('PLUGIN_MODULES_TABLE_NAME_V2', 'report_coursestatsv2_mod');
define('NEWS_FORUM_NAME_V2', 'news');
define('FORUM_TABLE_NAME_V2', 'forum');
define('COURSE_TABLE_NAME_V2', 'course');
define('MODULES_TABLE_NAME_V2', 'modules');
define('REPOSITORY_MODULES_V2', serialize(array('resource', 'url', 'folder')));
