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

require(dirname(__FILE__) . '/../../config.php');
require_login();

// Certifique-se de que este arquivo não seja acessado diretamente
defined('MOODLE_INTERNAL') || die();

// Configuração de cabeçalhos para exportação do CSV
header('Content-Type: application/excel');
header('Content-Disposition: attachment; filename="sample.csv"');

$fp = fopen('php://output', 'w');

// Cabeçalho da tabela no CSV
$headers = [
    get_string('category', 'report_coursestats_v2'),
    get_string('coursescreated', 'report_coursestats_v2'),
    get_string('coursesused', 'report_coursestats_v2'),
    get_string('unusedCourses', 'report_coursestats_v2'),
    get_string('usagerate', 'report_coursestats_v2')
];
fputcsv($fp, $headers);

// Consulta usando a Data Manipulation API do Moodle para obter as categorias e cursos criados
$sql = "SELECT cc.id, cc.name AS categoryname, COUNT(c.id) AS coursecount
        FROM {report_coursestatsv2_cat} cc
        LEFT JOIN {report_coursestatsv2_course} c ON c.coursestats_category_id = cc.id
        GROUP BY cc.id, cc.name
        ORDER BY cc.name";

$categories = $DB->get_records_sql($sql);

foreach ($categories as $category) {
    // Consulta para obter a quantidade de cursos utilizados na categoria
    $used_courses_sql = "SELECT COUNT(*)
                         FROM {report_coursestatsv2_course} rcc
                         JOIN {report_coursestatsv2} rc ON rcc.courseid = rc.courseid
                         WHERE rcc.coursestats_category_id = :categoryid";
    $used_course_count = $DB->count_records_sql($used_courses_sql, ['categoryid' => $category->id]);

    // Calculando a taxa de utilização e cursos não utilizados
    $usage_rate = $category->coursecount > 0 ? round(($used_course_count / $category->coursecount) * 100, 2) . '%' : '-';
    $unused_courses = $category->coursecount - $used_course_count;

    // Adicionando os dados ao CSV
    $row = [
        $category->categoryname, 
        $category->coursecount,  
        $used_course_count,      
        $unused_courses,         
        $usage_rate             
    ];
    fputcsv($fp, $row);
}

fclose($fp);
exit;
