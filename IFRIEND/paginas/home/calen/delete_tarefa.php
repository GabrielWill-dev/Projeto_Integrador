<?php
if(!isset($_GET['id'])){
    echo "<script> alert('Id da Tarefa não definido.'); location.replace('./') </script>";
    $conn->close();
    exit;
}

$delete = $conn->query("DELETE FROM `tbtarefas` WHERE idTarefa = '{$_GET['id']}'");
if($delete){
    echo "<script> alert('Tarefa excluida com sucesso.'); location.replace('./') </script>";
}else{
    echo "<pre>";
    echo "Ocorreu um Erro.<br>";
    echo "Erro: ".$conn->error."<br>";
    echo "SQL: ".$sql."<br>";
    echo "</pre>";
}
$conn->close();

?>