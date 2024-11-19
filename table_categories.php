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
    get_string('usagerate', 'report_coursestats_v2')
];

// Preenchendo as linhas da tabela com os dados reais
foreach ($categories as $category) {

    // Consulta para obter a quantidade de cursos utilizados na categoria
    $used_courses_sql = "SELECT COUNT(*)
                         FROM {report_coursestatsv2_course} rcc
                         JOIN {report_coursestatsv2} rc ON rcc.courseid = rc.courseid
                         WHERE rcc.coursestats_category_id = :categoryid";
    $used_course_count = $DB->count_records_sql($used_courses_sql, ['categoryid' => $category->id]);

    // Calculando a taxa de utilização (evita divisão por zero)
    $usage_rate = $category->coursecount > 0 ? round(($used_course_count / $category->coursecount) * 100, 2) . '%' : '-';

    // Link para a página details.php
    $link = html_writer::link(
        new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $category->id]),
        format_string($category->categoryname)
    );

    // Preenchendo os dados da tabela
    $table->data[] = [
        $link,                    // Nome da Categoria com link para a página de detalhes
        $category->coursecount,   // Quantidade de Cursos Criados
        $used_course_count,       // Quantidade de Cursos Utilizados
        $usage_rate               // Taxa de Utilização em %
    ];
}

// Retorna a tabela gerada
echo html_writer::table($table);

echo $OUTPUT->footer();


