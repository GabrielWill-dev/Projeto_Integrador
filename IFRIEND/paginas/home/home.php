<?php
include_once '../../db/conexao.php';

 try {
    $sql = "SELECT * FROM cl203156.tbtarefas";
    $result = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Processar os dados aqui
        }            
        mysqli_free_result($result); // Libera o resultado da memória
    } else {
        echo "Não encontrado";
    }

    // Fecha a conexão após o uso
    mysqli_close($conexao);

 } catch(Exception $e) {
    die("Erro no SQL: " . $e->getMessage());
 }
?>
<head>
<link href="../../css/calendario.css" rel="stylesheet">
</head>
<body>
<script src="https://cdnjs.com/libraries/Chart.js"></script>
<header>
    <h3>Página Principal</h3>
</header>

    <div id='calendar'></div>

    <script src='./js/index.global.min.js'></script>
    <script src='./js/core/locales-all.global.min.js'></script>
    <script src='./js/custom.js'></script>

</body>
