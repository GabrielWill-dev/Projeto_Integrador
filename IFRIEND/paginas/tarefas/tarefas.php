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
    <link rel="stylesheet" href="paginas/tarefas/calendario/fullcalendar/lib/main.min.css">  
    <script src="paginas/tarefas/calendario/js/jquery-3.6.0.min.js"></script>
    <script src="paginas/tarefas/calendario/fullcalendar/lib/main.min.js"></script>
</header>
    <div class="row">
        <div class="col-md-auto">
            <a class="btn btn-outline-secondary mb-2" 
            href="?menuop=cad-tarefa"><i class="bi bi-list-task"></i> Nova Tarefa</a>
        </div>
    <!-- Botão Calendario Tarefa -->
    <!-- Button trigger modal -->
        <div class="col-md-auto">
            <button type="button" class="col btn btn-outline-secondary mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="bi bi-calendar-event"></i> Calendário
            </button>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="0" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Calendario de Tarefas</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="calendar"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Event Details Modal -->
    <div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 badge bg-success-subtle">
                <div class="modal-header rounded-0">
                    <h5 class="modal-title text-dark">Detalhes da Tarefa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body rounded-0">
                    <div class="container-fluid">
                        <dl>
                            <dt class="text-dark">Titulo</dt>
                            <dd id="title" class="fw-bold fs-4 text-muted"></dd>
                            <dt class="text-dark">Descrição</dt>
                            <dd id="descricao" class="text-muted"></dd>
                            <dt class="text-dark">Inicio</dt>
                            <dd id="start" class="text-muted"></dd>
                            <dt class="text-dark">Conclusão</dt>
                            <dd id="end" class="text-muted"></dd>
                        </dl>
                    </div>
                </div>
                <div class="modal-footer rounded-0">
                    <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-0" id="edit" data-id="">Atualizar</button>
                        <button type="button" class="btn btn-danger btn-sm rounded-0" id="delete" data-id="">Excluir</button>
                        <button type="button" class="btn btn-secondary btn-sm rounded-0" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Event Details Modal -->

<?php 
$schedules = $conexao->query("SELECT * FROM `tbtarefas`");
$sched_res = [];
foreach($schedules->fetch_all(MYSQLI_ASSOC) as $row){
    $row['descricaoTarefa'];
    $row['sdate'] = date("F d, Y h:i A",strtotime($row['dataInicioTarefa']));
    $row['edate'] = date("F d, Y h:i A",strtotime($row['dataConclusaoTarefa']));
    $sched_res[$row['idTarefa']] = $row;
}
?>
<div>
    <form action="index.php?menuop=tarefas" method="post">
        <div class="input-group">
            <input class="form-control" type="text" name="txt_pesquisa" value="<?=$txt_pesquisa?>" placeholder="Pesquise aqui...">
            <button class="btn btn-outline-success btn-sm" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
        </div>
       
    </form>
</div>

<div class="tabela table-responsive">
    <div class="col-md-auto">
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
<script src="paginas/tarefas/calendario/js/pt-br.js"></script> <!--Idioma Português Fullcalendar-->
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="paginas/tarefas/calendario/js/script.js"></script>