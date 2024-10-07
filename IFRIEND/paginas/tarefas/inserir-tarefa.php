<header>
    <h3>
        <i class="bi bi-list-task"></i> Inserir Tarefa
    </h3>
</header>

<?php
$tituloTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['tituloTarefa']));
$descricaoTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['descricaoTarefa']));
$dataInicioTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['dataInicioTarefa']));
$dataConclusaoTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['dataConclusaoTarefa']));
$dataLembreteTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['dataLembreteTarefa']));
$horaLembreteTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['horaLembreteTarefa']));
$recorrenciaTarefa = strip_tags( mysqli_real_escape_string($conexao,$_POST['recorrenciaTarefa']));

$sql = "INSERT INTO tbtarefas 
(
    tituloTarefa,
    descricaoTarefa,
    dataInicioTarefa,
    dataConclusaoTarefa,
    dataLembreteTarefa,
    horaLembreteTarefa,
    recorrenciaTarefa
)
VALUES
(
    '{$tituloTarefa}',
    '{$descricaoTarefa}',
    '{$dataInicioTarefa}',
    '{$dataConclusaoTarefa}',
    '{$dataLembreteTarefa}',
    '{$horaLembreteTarefa}',
    '{$recorrenciaTarefa}'
)
";

$rs = mysqli_query($conexao,$sql);

if($rs){
    ?>
    <div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Inserir Tarefa</h4>
  <p>Tarefa inserida com sucesso.</p>
  <hr>
  <p class="mb-0">
    <a href="?menuop=tarefas">Voltar para a lista de tarefas.</a>
  </p>
</div>
    <?php
}else{
    ?>
       <div class="alert alert-danger" role="alert">
  <h4 class="alert-heading">Erro</h4>
  <p>A tarefa não pode ser inserida.</p>
  <hr>
  <p class="mb-0">
    <a href="?menuop=tarefas">Voltar para a lista de tarefas.</a>
  </p>
</div>
    <?php
}

?>