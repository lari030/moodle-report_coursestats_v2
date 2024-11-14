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
$categoryname = $DB->get_field('report_coursestats_categories', 'name', ['id' => $categoryid], MUST_EXIST);

$PAGE->set_url(new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $categoryid]));
$PAGE->set_context($context);
$PAGE->set_title(get_string('categorydetails', 'report_coursestats_v2'));
$PAGE->set_heading(get_string('categorydetails', 'report_coursestats_v2'));

echo $OUTPUT->header();

// Exibe o nome da categoria no topo
//echo $OUTPUT->heading('Categoria: ' . format_string($categoryname));

// Link para voltar à página anterior (index.php)
$back = html_writer::link(new moodle_url('/report/coursestats_v2/table_categories.php'), get_string('backtocategories', 'report_coursestats_v2'));

$query1 = "SELECT count(*) AS total FROM {report_coursestats_courses} 
          JOIN {report_coursestats} 
          ON {report_coursestats_courses}.courseid = {report_coursestats}.courseid
          WHERE {report_coursestats_courses}.coursestats_category_id = :category
          AND {report_coursestats}.curr_usage_type = :type";
          
$params1 = ['category' => $categoryid, 'type' => 'forum'];
$params2 = ['category' => $categoryid,'type'=> 'repository'];
$params3 = ['category' => $categoryid,'type'=> 'activity'];

$forum = $DB->get_record_sql($query1, $params1);
$repository = $DB->get_record_sql($query1, $params2);
$activity = $DB->get_record_sql($query1, $params3);

$allCoursesUsage = $forum->total + $repository->total + $activity->total;

$percentageForum = $forum->total > 0 ? round(($forum->total / $allCoursesUsage) * 100, 2). '%' : '0%'; 

$percentageRepository = $repository->total > 0 ? round(($repository->total / $allCoursesUsage) * 100, 2). '%' : '0%'; 

$percentageActivity = $activity->total > 0 ? round(($activity->total / $allCoursesUsage) * 100, 2). '%' : '0%'; 

// Primeira tabela: Tipos de Uso
//echo $OUTPUT->heading($categoryname.'('.$back.')');
echo $OUTPUT->heading($categoryname . ' (' . $back . ')', 4, 'text-center');

$usage_table = new html_table();
$usage_table->head = [
    get_string('usagetype', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('percentage', 'report_coursestats_v2')
];

// Dados fictícios 
$usage_table->data[] = ['Fórum', $forum->total, $percentageForum];
$usage_table->data[] = ['Repositório', $repository->total, $percentageRepository];
$usage_table->data[] = ['Atividades', $activity->total, $percentageActivity];
$usage_table->data[] = [html_writer::tag('strong', 'Total'),
                        html_writer::tag('strong', $allCoursesUsage),
                        html_writer::tag('strong', '100%')];

echo html_writer::table($usage_table);

// Segunda tabela: Módulos
echo $OUTPUT->heading('Módulos');
$modules_table = new html_table();
$modules_table->head = [
    get_string('modules', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('percentage', 'report_coursestats_v2')
];

// Dados fictícios 
$modules_table->data[] = ['Arquivo', '-', '-'];
$modules_table->data[] = ['Fórum', '-', '-'];
$modules_table->data[] = ['Questionário', '-', '-'];

echo html_writer::table($modules_table);

echo $OUTPUT->footer();