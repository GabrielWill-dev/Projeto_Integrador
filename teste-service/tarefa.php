<?php
header('Content-Type: application/json');

// Conexão com o banco de dados
include("../IFRIEND/db/conexao.php");

// Obter a data e hora atuais
$dataAtual = date('Y-m-d H:i:s');

// Executar a consulta para pegar os dados da tarefa
$sql = "SELECT * FROM tbtarefas WHERE dataLembreteTarefa <= '$dataAtual'";
$result = $conexao->query($sql);

// Preparar a resposta
$tarefas = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tarefas[] = $row; // Adiciona a tarefa ao array
    }
}

echo json_encode($tarefas);

// Fechar a conexão
$conexao->close();
?>