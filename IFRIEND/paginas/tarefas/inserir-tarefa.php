<header>
    <h3>
        <i class="bi bi-list-task"></i> Inserir Tarefa
    </h3>
</header>

<?php
// Captura e sanitiza os dados
$tituloTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['tituloTarefa']));
$descricaoTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['descricaoTarefa']));
$dataInicioTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['dataInicioTarefa']));
$dataConclusaoTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['dataConclusaoTarefa']));
$dataLembreteTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['dataLembreteTarefa']));
$recorrenciaTarefa = strip_tags(mysqli_real_escape_string($conexao, $_POST['recorrenciaTarefa']));

// Inserção da tarefa inicial
$sql = "INSERT INTO tbtarefas 
(
    tituloTarefa,
    descricaoTarefa,
    dataInicioTarefa,
    dataConclusaoTarefa,
    dataLembreteTarefa,
    recorrenciaTarefa
)
VALUES
(
    '{$tituloTarefa}',
    '{$descricaoTarefa}',
    '{$dataInicioTarefa}',
    '{$dataConclusaoTarefa}',
    '{$dataLembreteTarefa}',
    '{$recorrenciaTarefa}'
)";

$rs = mysqli_query($conexao, $sql);

if ($rs) {
    $idTarefa = mysqli_insert_id($conexao); // Pega o ID da tarefa inserida

    // Lógica de recorrência
    $intervalo = null;
    switch ($recorrenciaTarefa) {
        case '1': // Diariamente
            $intervalo = '1 day';
            break;
        case '2': // Semanalmente
            $intervalo = '1 week';
            break;
        case '3': // Mensalmente
            $intervalo = '1 month';
            break;
        case '4': // Anualmente
            $intervalo = '1 year';
            break;
    }

    // Se for uma tarefa recorrente
    if ($intervalo !== null) {
        // Variáveis acumulativas de data
        $dataInicioAtual = $dataInicioTarefa;
        $dataConclusaoAtual = $dataConclusaoTarefa;
        $dataLembreteAtual = !empty($dataLembreteTarefa) ? $dataLembreteTarefa : null;

        // Vamos gerar mais 5 ocorrências como exemplo
        for ($i = 1; $i <= 5; $i++) {
            // Atualiza as datas acumulando o intervalo
            $dataInicioAtual = date('Y-m-d H:i:s', strtotime($intervalo, strtotime($dataInicioAtual)));
            $dataConclusaoAtual = date('Y-m-d H:i:s', strtotime($intervalo, strtotime($dataConclusaoAtual)));
            $dataLembreteAtual = !empty($dataLembreteAtual) ? date('Y-m-d', strtotime($intervalo, strtotime($dataLembreteAtual))) : null;

            // Insere as tarefas recorrentes no banco de dados
            $sqlRecorrente = "INSERT INTO tbtarefas 
            (
                tituloTarefa,
                descricaoTarefa,
                dataInicioTarefa,
                dataConclusaoTarefa,
                dataLembreteTarefa,
                recorrenciaTarefa
            )
            VALUES
            (
                '{$tituloTarefa}',
                '{$descricaoTarefa}',
                '{$dataInicioAtual}',
                '{$dataConclusaoAtual}',
                '{$dataLembreteAtual}',
                '{$recorrenciaTarefa}'
            )";
            
            mysqli_query($conexao, $sqlRecorrente);
        }
    }
    
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
} else {
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
