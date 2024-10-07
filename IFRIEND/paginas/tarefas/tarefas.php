<?php

$txt_pesquisa = (isset($_POST['txt_pesquisa'])) ? $_POST['txt_pesquisa'] : '';

// Verifica se idTarefa e statusTarefa foram passados na URL
$idTarefa = isset($_GET['idTarefa']) && !empty($_GET['idTarefa']) ? (int)$_GET['idTarefa'] : null;
$statusTarefa = isset($_GET['statusTarefa']) && ($_GET['statusTarefa'] == '0' || $_GET['statusTarefa'] == '1') ? (int)$_GET['statusTarefa'] : null;

if (!is_null($idTarefa) && !is_null($statusTarefa)) {
    // Alterna o status da tarefa entre 0 e 1
    $novoStatusTarefa = $statusTarefa == 0 ? 1 : 0;

    // Executa o SQL de atualização
    $sql = "UPDATE cl203156.tbtarefas SET statusTarefa = {$novoStatusTarefa} WHERE idTarefa = {$idTarefa}";
    $rs = mysqli_query($conexao, $sql) or die("Erro ao atualizar a tarefa: " . mysqli_error($conexao));
}

// Função para verificar se a tarefa já está concluída pela data e hora
function verificarConclusaoAutomatica($conexao) {
    // Pega a data e hora atuais
    $dataAtual = date('Y-m-d');
    $horaAtual = date('H:i:s');

    // Seleciona tarefas não concluídas cuja data de conclusão já foi atingida
    $sql = "SELECT idTarefa, dataConclusaoTarefa FROM tbtarefas 
            WHERE statusTarefa = 0 
            AND dataConclusaoTarefa <= '{$dataAtual}'";

    $rs = mysqli_query($conexao, $sql) or die("Erro ao executar a consulta: " . mysqli_error($conexao));

    // Verifica as tarefas e atualiza aquelas cuja hora também já foi atingida
    while ($tarefa = mysqli_fetch_assoc($rs)) {
        $idTarefa = $tarefa['idTarefa'];
        $dataConclusao = $tarefa['dataConclusaoTarefa'];
  //      $horaConclusao = $tarefa['horaConclusaoTarefa'];

        // Se a data de conclusão for igual à data atual, verifica a hora
        if ($dataConclusao == $dataAtual && $dataConclusao <= $horaAtual) {
            // Atualiza o status da tarefa para "Concluído" (1)
            $sqlUpdate = "UPDATE tbtarefas SET statusTarefa = 1 WHERE idTarefa = {$idTarefa}";
            mysqli_query($conexao, $sqlUpdate) or die("Erro ao atualizar tarefa automaticamente: " . mysqli_error($conexao));
        }
        // Se a data de conclusão for anterior à data atual, a tarefa também deve ser concluída
        elseif ($dataConclusao < $dataAtual) {
            $sqlUpdate = "UPDATE tbtarefas SET statusTarefa = 1 WHERE idTarefa = {$idTarefa}";
            mysqli_query($conexao, $sqlUpdate) or die("Erro ao atualizar tarefa automaticamente: " . mysqli_error($conexao));
        }
    }
}

// Chama a função para verificar as conclusões automáticas
//verificarConclusaoAutomatica($conexao);

?>

<header>
    <h3><i class="bi bi-list-task"></i> Tarefas</h3>
</header>
<div>
    <a class="btn btn-outline-secondary mb-2" 
    href="?menuop=cad-tarefa"><i class="bi bi-list-task"></i> Nova Tarefa</a>
</div>
<div>
    <form action="index.php?menuop=tarefas" method="post">
        <div class="input-group">
            <input class="form-control" type="text" name="txt_pesquisa" value="<?=$txt_pesquisa?>" placeholder="Pesquise aqui...">
            <button class="btn btn-outline-success btn-sm" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
        </div>
       
    </form>
</div>
<div class="tabela">
<table class="table table-dark table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>Status</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Data/hora do Inicio</th>
            <th>Data/hora da Conclusão</th>
            <th>Atualizar</th>
            <th>Excluir</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $quantidade = 10;
            $pagina = ( isset($_GET['pagina']) ) ?(int)$_GET['pagina']:1;
            $inicio = ($quantidade * $pagina) - $quantidade;

            

            $sql = "SELECT
            idTarefa, 
            statusTarefa,
            tituloTarefa,
            descricaoTarefa,
            dataConclusaoTarefa,
            dataInicioTarefa
            FROM tbtarefas
            WHERE 
            tituloTarefa LIKE '%{$txt_pesquisa}%' OR 
            descricaoTarefa LIKE '%{$txt_pesquisa}%' OR
            dataInicioTarefa LIKE '%{$txt_pesquisa}%'
            ORDER BY statusTarefa, dataInicioTarefa 
            LIMIT $inicio, $quantidade
            ";
          
            $rs = mysqli_query($conexao,$sql) 
            or die("Erro ao executar a consulta! Erro:" . mysqli_error($conexao));

            while($dados = mysqli_fetch_assoc($rs)){

            

        ?>
        <tr>
            <td>
                <a class="btn btn-secondary btn-sm" href="index.php?menuop=tarefas&pagina=<?=$pagina?>&idTarefa=<?=$dados['idTarefa']?>&statusTarefa=<?=$dados['statusTarefa']?>" >
                    <?php
                        if($dados['statusTarefa']==0){
                            echo '<button type="button" class="btn btn-danger">Não Concluido</button>';
                        }else{
                            echo '<button type="button" class="btn btn-success">Concluido</button>';
                        }
                    ?>
                </a>   
            </td>
            <td class="text-nowrap"><?=$dados['tituloTarefa']?></td>
            <td class="text-nowrap"><?=$dados['descricaoTarefa']?></td>
            <td class="text-nowrap"><?=$dados['dataInicioTarefa']?></td>
            <td class="text-nowrap"><?=$dados['dataConclusaoTarefa']?></td>

            <td class="text-center">
                <a class="btn btn-outline-warning btn-sm" href="index.php?menuop=editar-tarefa&idTarefa=<?=$dados['idTarefa']?>"><i class="bi bi-pencil-square"></i></a>
                
            </td>
            <td class="text-center">
                <a class="btn btn-outline-danger btn-sm" href="index.php?menuop=excluir-tarefa&idTarefa=<?=$dados['idTarefa']?>"><i class="bi bi-trash-fill"></i></a>    
            </td>
            

        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
</div>

<ul class="pagination justify-content-center">
<?php

        $sqlTotal = "SELECT idTarefa FROM tbtarefas";
        $qrTotal = mysqli_query($conexao,$sqlTotal) or die(mysqli_error($conexao));
        $numTotal = mysqli_num_rows($qrTotal);

        $totalPagina = ceil($numTotal / $quantidade);

        echo "<li class='page-item'><span class='page-link'>Total de registros: " . $numTotal . " </span></li> ";

        echo '<li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=1">Primeira Pagina</a></li>';

        if($pagina>6){
            ?>
            <li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=<?php echo $pagina-1?>"><<</a></li>
            <?php
        }


        for($i=1;$i<=$totalPagina;$i++){
            
           if($i>=($pagina-5) && $i <= ($pagina+5)){
            if($i==$pagina){
                echo "<li class='page-item active'><span class='page-link'>$i</span></li>";
            }else{
                echo "<li class='page-item'><a class='page-link' href=\"?menuop=tarefas&pagina={$i}\"> {$i} </a></li>";

            }
           }
        }

        if($pagina<$totalPagina-5){
            ?>
            <li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=<?php echo $pagina+1?>">>></a></li>
            <?php
        }
        echo "<li class='page-item'> <a class='page-link' href=\"?menuop=tarefas&pagina=$totalPagina\">Ultima Pagina</a></li>";


?>
</ul>