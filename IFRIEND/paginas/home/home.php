<?php
 try {
    $sql = "SELECT * FROM cl203156.tbtarefas";
    $result = mysqli_query($conexao, $sql);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            // Processar os dados aqui
        }            
        mysqli_free_result($result); // Libera o resultado da memória
    } else {
        echo "Não encontrado";
    }
 } catch(Exception $e) {
    die("Erro no SQL: " . $e->getMessage());
 }
?>
<script src="https://cdnjs.com/libraries/Chart.js"></script>
<header>
    <h3>Página Principal</h3>
</header>
