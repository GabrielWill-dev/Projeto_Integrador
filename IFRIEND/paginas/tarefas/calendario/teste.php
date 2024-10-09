<head>

    <link rel="stylesheet" href="paginas/home/calen/fullcalendar/lib/main.min.css">
    
    <script src="paginas/home/calen/js/jquery-3.6.0.min.js"></script>
    <script src="paginas/home/calen/fullcalendar/lib/main.min.js"></script>

</head>

<body class="bg">


    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Calendário
    </button>

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
<?php 
if(isset($conexao)) $conexao->close();
?>
</body>

<script src="paginas/home/calen/js/pt-br.js"></script> <!--Idioma Português Fullcalendar-->
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="paginas/home/calen/js/script.js"></script>