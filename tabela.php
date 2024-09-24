<?php

defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');



// Consulta às categorias
$sql = "SELECT cc.id, cc.name AS category, COUNT(c.id) AS coursecount
        FROM {course_categories} cc
        LEFT JOIN {course} c ON c.category = cc.id
        GROUP BY cc.id, cc.name
        ORDER BY cc.name";

$categories = $DB->get_records_sql($sql);

if ($categories) {
    // Criação da tabela usando a classe html_table
    $table = new html_table();
    $table->head = [
        get_string('category', 'report_coursestats_v2'),
        get_string('coursecount', 'report_coursestats_v2')
    ];
    
    // Preenchendo as linhas da tabela com os dados
    foreach ($categories as $category) {
        $table->data[] = [
            format_string($category->category),
            $category->coursecount
        ];
    }

    // Exibindo a tabela
    echo html_writer::table($table);

} else {
    // Notificação se não houver categorias
    echo $OUTPUT->notification(get_string('nocategories', 'report_coursestats_v2'), 'notifyproblem');
}
