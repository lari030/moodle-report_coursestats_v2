<?php

// Certifique-se de que este arquivo não seja acessado diretamente
defined('MOODLE_INTERNAL') || die();

// Consulta usando a Data Manipulation API do Moodle para obter as categorias e cursos criados
$sql = "SELECT cc.id, cc.name AS categoryname, COUNT(c.id) AS coursecount
        FROM {course_categories} cc
        LEFT JOIN {course} c ON c.category = cc.id
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
