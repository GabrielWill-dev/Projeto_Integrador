<?php
include("../../../db/conexao.php");
if(!isset($_GET['id'])){
    echo "<script> alert('Id da Tarefa não definido.'); location.replace('./') </script>";
    $conexao->close();
    exit;
}

$delete = $conexao->query("DELETE FROM `tbtarefas` WHERE idTarefa = '{$_GET['id']}'");
if($delete){
    echo "<script> alert('Tarefa excluida com sucesso.'); location.replace('../../../index.php?menuop=tarefas') </script>";
}else{
    echo "<pre>";
    echo "Ocorreu um Erro.<br>";
    echo "Erro: ".$conexao->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
}
$conexao->close();

?>