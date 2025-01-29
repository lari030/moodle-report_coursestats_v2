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

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');


admin_externalpage_setup('reportcoursestatsv2', '', null, '', array('pagelayout' => 'report'));


echo $OUTPUT->header();

// Certifique-se de que este arquivo não seja acessado diretamente
defined('MOODLE_INTERNAL') || die();

$labels = [];
$values = [];

// Consulta usando a Data Manipulation API do Moodle para obter as categorias e cursos criados
$sql = "SELECT cc.id, cc.name AS categoryname, COUNT(c.id) AS coursecount
        FROM {report_coursestatsv2_cat} cc
        LEFT JOIN {report_coursestatsv2_course} c ON c.coursestats_category_id = cc.id
        GROUP BY cc.id, cc.name
        ORDER BY cc.name";

$categories = $DB->get_records_sql($sql);

// Criação da tabela principal com categorias reais e cursos criados
$table = new html_table();
$table->head = [
    get_string('category', 'report_coursestats_v2'),
    get_string('coursescreated', 'report_coursestats_v2'),
    get_string('coursesused', 'report_coursestats_v2'),
    get_string('unusedCourses', 'report_coursestats_v2'),
    get_string('usagerate', 'report_coursestats_v2')
];

$category_index = 1;

// Preenchendo as linhas da tabela com os dados reais
foreach ($categories as $category) {

    // Consulta para obter a quantidade de cursos utilizados na categoria
    $used_courses_sql = "SELECT COUNT(*)
                         FROM {report_coursestatsv2_course} rcc
                         JOIN {report_coursestatsv2} rc ON rcc.courseid = rc.courseid
                         WHERE rcc.coursestats_category_id = :categoryid";
    $used_course_count = $DB->count_records_sql($used_courses_sql, ['categoryid' => $category->id]);
    

    // Calculando a taxa de utilização (evita divisão por zero)
    $usage_rate = $category->coursecount > 0 ? round(($used_course_count / $category->coursecount) * 100, 2) : 0;
    $unused_courses = $category->coursecount - $used_course_count;

    // Link para a página details.php
    $link = html_writer::link(
        new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $category->id]),
        $category_index . ' - ' . format_string($category->categoryname)
    );

    //Link para a página de cursos criados
    $link2 = html_writer::link(
        new moodle_url('/report/coursestats_v2/created_courses.php', ['categoryid' => $category->id]),
        format_string($category->coursecount)
    );

    $link3 = html_writer::link(
        new moodle_url('/report/coursestats_v2/used_courses.php', ['categoryid' => $category->id]),
        format_string($used_course_count)
    );

    $link4 = html_writer::link(
        new moodle_url('/report/coursestats_v2/unused_courses.php', ['categoryid' => $category->id]),
        format_string($unused_courses)
    );

    $labels[] = $category_index;
    $values[] = $usage_rate;
    $category_index++;

    // Preenchendo os dados da tabela
    $table->data[] = [
        $link,                    // Nome da Categoria com link para a página de detalhes
        $link2,         // Quantidade de Cursos Criados
        $link3,       // Quantidade de Cursos Utilizados
        $link4,
        $usage_rate               // Taxa de Utilização em %
    ];
}

if(class_exists('core\chart_bar')) {
    $chart = new core\chart_bar();
    $series = new core\chart_series(get_string('usagerate', 'report_coursestats_v2'), $values);

    $chart->set_labels($labels);
    $chart->add_series($series);
    $chart->get_xaxis(0, true)->set_label(get_string('category', 'report_coursestats_v2'));
    $chart->get_yaxis(0, true)->set_label(get_string('usagerate', 'report_coursestats_v2'));

    echo $OUTPUT->render_chart($chart, false);
}

echo html_writer::start_div('text-center');
echo html_writer::link(
    new moodle_url('/report/coursestats_v2/index.php'),
    get_string('update', 'report_coursestats_v2'),
);

echo '  |  ';

echo html_writer::link(
    new moodle_url('/report/coursestats_v2/csvgen.php'),
    get_string('exporttocsv', 'report_coursestats_v2'),
);
echo html_writer::end_div();

// Retorna a tabela gerada
echo html_writer::table($table);

echo $OUTPUT->footer();


