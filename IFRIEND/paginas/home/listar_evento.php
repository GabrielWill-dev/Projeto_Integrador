<?php

// Incluir o arquivo com a conexão com banco de dados
include_once 'IFRIEND/db/conexao.php';

// QUERY para recuperar os eventos
$query_events = "SELECT idTarefa, tituloTarefa, dataConclusaoTarefa FROM tbtarefas";

// Prepara a QUERY
$result_events = $conn->prepare($query_events);

// Executar a QUERY
$result_events->execute();

// Criar o array que recebe os eventos
$eventos = [];

// Percorrer a lista de registros retornado do banco de dados
while($row_events = $result_events->fetch(PDO::FETCH_ASSOC)){

    // Extrair o array
    extract($row_events);

    $eventos[] = [
        'idTarefa' => $id,
        'tituloTarefa' => $title,
        //'color' => $color,
        'dataConclusaoTarefa' => $start,
       // 'end' => $end,
    ];
}

echo json_encode($eventos);