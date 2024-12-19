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


require_once('../../config.php');

echo '<style>
    .text-center {
        text-align: center;
    }
    .bold-text {
        font-weight: bold;
    }
</style>';

// Obtém o ID da categoria a partir da URL
$categoryid = required_param('categoryid', PARAM_INT);

// Consulta o nome da categoria a partir do ID
$categoryname = $DB->get_field('report_coursestatsv2_cat', 'name', ['id' => $categoryid], MUST_EXIST);

$PAGE->set_url(new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $categoryid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('categorydetails', 'report_coursestats_v2'));
$PAGE->set_heading(get_string('categorydetails', 'report_coursestats_v2'));

echo $OUTPUT->header();

// Exibe o nome da categoria no topo
// Link para voltar à página anterior (index.php)
$back = html_writer::link(new moodle_url('/report/coursestats_v2/table_categories.php'), get_string('backtocategories', 'report_coursestats_v2'));

$query1 = "SELECT count(*) AS amount FROM {report_coursestatsv2_course} 
          JOIN {report_coursestatsv2} 
          ON {report_coursestatsv2_course}.courseid = {report_coursestatsv2}.courseid
          WHERE {report_coursestatsv2_course}.coursestats_category_id = :category
          AND {report_coursestatsv2}.curr_usage_type = :type";
          
$params1 = ['category' => $categoryid, 'type' => 'forum'];
$params2 = ['category' => $categoryid,'type'=> 'repository'];
$params3 = ['category' => $categoryid,'type'=> 'activity'];

$forum = $DB->get_record_sql($query1, $params1);
$repository = $DB->get_record_sql($query1, $params2);
$activity = $DB->get_record_sql($query1, $params3);

$allCoursesUsage = $forum->amount + $repository->amount + $activity->amount;

$percentageForum = $forum->amount > 0 ? round(($forum->amount / $allCoursesUsage) * 100, 2). '%' : '0%'; 

$percentageRepository = $repository->amount > 0 ? round(($repository->amount / $allCoursesUsage) * 100, 2). '%' : '0%'; 

$percentageActivity = $activity->amount > 0 ? round(($activity->amount / $allCoursesUsage) * 100, 2). '%' : '0%'; 

// Primeira tabela: Tipos de Uso
echo $OUTPUT->heading($categoryname . ' (' . $back . ')', 4, 'text-center');

$usage_table = new html_table();
$usage_table->head = [
    get_string('usagetype', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('percentage', 'report_coursestats_v2')
];

// Dados fictícios 
$usage_table->data[] = [get_string('usageForum', 'report_coursestats_v2'), $forum->amount, $percentageForum];
$usage_table->data[] = [get_string('usageRepository', 'report_coursestats_v2'), $repository->amount, $percentageRepository];
$usage_table->data[] = [get_string('usageActivity', 'report_coursestats_v2'), $activity->amount, $percentageActivity];
$usage_table->data[] = [html_writer::tag('strong', get_string('amount', 'report_coursestats_v2')),
                        html_writer::tag('strong', $allCoursesUsage),
                        html_writer::tag('strong', $allCoursesUsage > 0 ? '100%' : '0%')];

echo html_writer::table($usage_table);

$query2 = "SELECT M.name, M.id, COUNT(CM.id) AS amount 
    FROM {modules} AS M 
    INNER JOIN {report_coursestatsv2_mod} AS CM ON M.id = CM.moduleid 
    INNER JOIN {report_coursestatsv2} AS C ON CM.courseid = C.courseid
    INNER JOIN {report_coursestatsv2_course} AS RCC ON C.courseid = RCC.courseid
    WHERE RCC.coursestats_category_id = :cat GROUP BY M.name, M.id";



$data = $DB->get_record_sql($query2, array("cat", $categoryid));

// Segunda tabela: Módulos
echo $OUTPUT->heading('Módulos utilizados');
 $modules_table = new html_table();
 $modules_table->head = [
    get_string('modules', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('percentage', 'report_coursestats_v2')
 ];

 foreach ($data as $item) {
    $row = array();

    $percent = number_format(($item->amount / $allCoursesUsage) * 100, 2);

    $row[] = $item->name;
    $row[] = $item->amount;
    $row[] = $percent;

    $modules_table->data[] = $row;
}
 

echo html_writer::table($modules_table);

echo $OUTPUT->footer();