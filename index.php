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
 * Report main page
 *
 * @package    report
 * @copyright  2019 Paulo Jr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require(__DIR__.'/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('reportcoursestatsv2', '', null, '', array('pagelayout'=>'report'));

$customcatnames = get_config('report_coursestats_v2', 'customcatnames');

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('heading',  'report_coursestats_v2'));

if(!empty($customcatnames)) {
  echo format_text($customcatnames, FORMAT_HTML);
} else {
  echo $OUTPUT -> notification('No custom category names have been set.', 'notifymessage');
}

echo $OUTPUT->footer();