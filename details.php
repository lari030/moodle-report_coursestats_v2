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
echo $OUTPUT->heading('Categoria: ' . format_string($categoryname));

// Link para voltar à página anterior (table_categories.php)
echo html_writer::link(new moodle_url('/report/coursestats_v2/table_categories.php'), get_string('backtocategories', 'report_coursestats_v2'));

// Primeira tabela: Tipos de Uso
echo $OUTPUT->heading('Tipos de Uso');
$usage_table = new html_table();
$usage_table->head = [
    get_string('usagetype', 'report_coursestats_v2'),
    get_string('roomcount', 'report_coursestats_v2'),
    get_string('percentage', 'report_coursestats_v2')
];

// Dados fictícios 
$usage_table->data[] = ['Fórum', '-', '-'];
$usage_table->data[] = ['Repositório', '-', '-'];
$usage_table->data[] = ['Atividades', '-', '-'];

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
