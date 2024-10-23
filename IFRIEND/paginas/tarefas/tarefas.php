<?php
// Configurações de data
date_default_timezone_set('America/Sao_Paulo');
$dataAtual = $_POST['data_pesquisa'] ?? date('Y-m-d'); // Usa a data do POST ou a data atual
$txt_pesquisa = $_POST['txt_pesquisa'] ?? '';

// Verifica se idTarefa e statusTarefa foram passados na URL
$idTarefa = isset($_GET['idTarefa']) ? (int)$_GET['idTarefa'] : null;
$statusTarefa = isset($_GET['statusTarefa']) && in_array($_GET['statusTarefa'], ['0', '1']) ? (int)$_GET['statusTarefa'] : null;

if ($idTarefa !== null && $statusTarefa !== null) {
    // Alterna o status da tarefa
    $novoStatusTarefa = $statusTarefa == 0 ? 1 : 0;
    $sql = "UPDATE cl203156.tbtarefas SET statusTarefa = $novoStatusTarefa WHERE idTarefa = $idTarefa";
    mysqli_query($conexao, $sql) or die("Erro ao atualizar a tarefa: " . mysqli_error($conexao));
}

// Carrega tarefas
$schedules = $conexao->query("SELECT * FROM tbtarefas");
$sched_res = [];
while ($row = $schedules->fetch_assoc()) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['dataInicioTarefa']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['dataConclusaoTarefa']));
    $sched_res[$row['idTarefa']] = $row;
}

// Paginação
$quantidade = 10;
$pagina = (int)($_GET['pagina'] ?? 1);
$inicio = ($quantidade * $pagina) - $quantidade;

// Consulta com filtros
$sql = "SELECT idTarefa, statusTarefa, tituloTarefa, descricaoTarefa, dataConclusaoTarefa, dataInicioTarefa
        FROM tbtarefas
        WHERE dataInicioTarefa LIKE '%$dataAtual%' 
          AND (tituloTarefa LIKE '%$txt_pesquisa%' OR descricaoTarefa LIKE '%$txt_pesquisa%')
        ORDER BY statusTarefa, dataInicioTarefa 
        LIMIT $inicio, $quantidade";
$rs = mysqli_query($conexao, $sql) or die("Erro ao executar a consulta! Erro:" . mysqli_error($conexao));
?>

<header>
    <h3><i class="bi bi-list-task"></i> Tarefas</h3>
    <link rel="stylesheet" href="paginas/tarefas/calendario/fullcalendar/lib/main.min.css">
    <script src="paginas/tarefas/calendario/js/jquery-3.6.0.min.js"></script>
    <script src="paginas/tarefas/calendario/fullcalendar/lib/main.min.js"></script>
</header>

<div class="row">
    <div class="col-md-auto">
        <a class="btn btn-outline-secondary mb-2" href="?menuop=cad-tarefa"><i class="bi bi-list-task"></i> Nova Tarefa</a>
    </div>
    <div class="col-md-auto">
        <button type="button" class="col btn btn-outline-secondary mb-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <i class="bi bi-calendar-event"></i> Calendário
        </button>
    </div>
</div>

<!-- Modal de Calendário -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="0" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-dark">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Calendário de Tarefas</h1>
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

<!-- Modal de Detalhes da Tarefa -->
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
                        <dt class="text-dark">Título</dt>
                        <dd id="title" class="fw-bold fs-4 text-muted"></dd>
                        <dt class="text-dark">Descrição</dt>
                        <dd id="descricao" class="text-muted"></dd>
                        <dt class="text-dark">Início</dt>
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

<div>
<div class="d-flex align-items-center">
    <form method="post" action="index.php?menuop=tarefas">
        <input type="date" name="data_pesquisa" class="form-control text-bg-secondary me-2" value="<?= htmlspecialchars($dataAtual) ?>">
        <button type="submit" class="btn btn-outline-success">Filtrar por Data</button>
    </form>
    </div>
    <form action="index.php?menuop=tarefas" method="post">
        <div class="input-group">
            <input class="form-control" type="text" name="txt_pesquisa" value="<?= htmlspecialchars($txt_pesquisa) ?>" placeholder="Pesquise aqui...">
            <button class="btn btn-outline-success btn-sm" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
        </div>
    </form>
</div>

<div class="tabela table-responsive">
    <table class="table table-dark table-striped table-bordered table-sm">
        <thead>
            <tr>
                <th>Status</th>
                <th>Título</th>
                <th>Descrição</th>
                <th>Data/hora do Início</th>
                <th>Data/hora da Conclusão</th>
                <th>Atualizar</th>
                <th>Excluir</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($dados = mysqli_fetch_assoc($rs)): ?>
                <tr>
                    <td>
                        <a class="btn btn-secondary btn-sm" href="index.php?menuop=tarefas&pagina=<?= $pagina ?>&idTarefa=<?= $dados['idTarefa'] ?>&statusTarefa=<?= $dados['statusTarefa'] ?>">
                            <button type="button" class="btn <?= $dados['statusTarefa'] == 0 ? 'btn-danger' : 'btn-success' ?>">
                                <?= $dados['statusTarefa'] == 0 ? 'Não Concluído' : 'Concluído' ?>
                            </button>
                        </a>
                    </td>
                    <td class="text-nowrap"><?= htmlspecialchars($dados['tituloTarefa']) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($dados['descricaoTarefa']) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($dados['dataInicioTarefa']) ?></td>
                    <td class="text-nowrap"><?= htmlspecialchars($dados['dataConclusaoTarefa']) ?></td>
                    <td class="text-center">
                        <a class="btn btn-outline-warning btn-sm" href="index.php?menuop=editar-tarefa&idTarefa=<?= $dados['idTarefa'] ?>"><i class="bi bi-pencil-square"></i></a>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-outline-danger btn-sm" href="index.php?menuop=excluir-tarefa&idTarefa=<?= $dados['idTarefa'] ?>"><i class="bi bi-trash-fill"></i></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<ul class="pagination justify-content-center">
    <?php
    $sqlTotal = "SELECT idTarefa FROM tbtarefas";
    $qrTotal = mysqli_query($conexao, $sqlTotal) or die(mysqli_error($conexao));
    $numTotal = mysqli_num_rows($qrTotal);
    $totalPagina = ceil($numTotal / $quantidade);

    echo "<li class='page-item'><span class='page-link'>Total de registros: $numTotal</span></li>";
    echo '<li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=1">Primeira Página</a></li>';

    if ($pagina > 1) {
        echo '<li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=' . ($pagina - 1) . '"><<</a></li>';
    }

    for ($i = 1; $i <= $totalPagina; $i++) {
        if ($i >= ($pagina - 5) && $i <= ($pagina + 5)) {
            if ($i == $pagina) {
                echo "<li class='page-item active'><span class='page-link'>$i</span></li>";
            } else {
                echo "<li class='page-item'><a class='page-link' href=\"?menuop=tarefas&pagina={$i}\">{$i}</a></li>";
            }
        }
    }

    if ($pagina < $totalPagina) {
        echo '<li class="page-item"><a class="page-link" href="?menuop=tarefas&pagina=' . ($pagina + 1) . '">>></a></li>';
    }
    echo "<li class='page-item'><a class='page-link' href=\"?menuop=tarefas&pagina=$totalPagina\">Última Página</a></li>";
    ?>
</ul>

<script src="paginas/tarefas/calendario/js/pt-br.js"></script>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>');
</script>
<script src="paginas/tarefas/calendario/js/script.js"></script>
