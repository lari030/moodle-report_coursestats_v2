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


// Certifique-se de que este arquivo não seja acessado diretamente
defined('MOODLE_INTERNAL') || die();

// Consulta usando a Data Manipulation API do Moodle para obter as categorias e cursos criados
$sql = "SELECT cc.id, cc.name AS categoryname, COUNT(c.id) AS coursecount
        FROM {report_coursestats_categories} cc
        LEFT JOIN {report_coursestats_courses} c ON c.coursestats_category_id = cc.id
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
    $link = html_writer::link(
        new moodle_url('/report/coursestats_v2/details.php', ['categoryid' => $category->id]),
        format_string($category->categoryname)
    );

    // Valores fictícios
    $table->data[] = [
        $link,                   // Nome da Categoria com link para a página de detalhes
        $category->coursecount,   // Quantidade de Cursos Criados
        '-',                     // Quantidade de Cursos Utilizados (a ser implementado)
        '-'                      // Taxa de Utilização (a ser implementado)
    ];
}

// Retorna a tabela gerada
echo html_writer::table($table);

