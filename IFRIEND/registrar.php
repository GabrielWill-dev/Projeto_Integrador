<?php
// Conexão com o banco de dados
include "./db/conexao.php";

// Variável para mensagens de erro
$msg_error = "";
$msg_success = "";

// Verificação no banco de dados
if (isset($_POST["loginUser"]) && isset($_POST["senhaUser"]) && isset($_POST["emailUser"])) {
    $loginUser = mysqli_escape_string($conexao, $_POST["loginUser"]);
    $senhaUser = hash('sha256', $_POST["senhaUser"]);
    $emailUser = mysqli_escape_string($conexao, $_POST["emailUser"]);

    // Verifica se o usuário já existe
    $sql_check = "SELECT * FROM tbusuarios WHERE loginUser = '$loginUser'";
    $rs_check = mysqli_query($conexao, $sql_check);

    if (mysqli_num_rows($rs_check) > 0) {
        $msg_error = "<div class='alert alert-danger mt-3'>
                        <p>Usuário já existe!</p>
                      </div>";
    } else {
        // Insere o novo usuário
        $sql_insert = "INSERT INTO tbusuarios (loginUser, senhaUser, emailUser) VALUES ('$loginUser', '$senhaUser', '$emailUser')";
        if (mysqli_query($conexao, $sql_insert)) {
            $msg_success = "<div class='alert alert-success mt-3'>
                            <p>Usuário registrado com sucesso!</p>
                            </div>";
        } else {
            $msg_error = "<div class='alert alert-danger mt-3'>
                            <p>Erro ao registrar usuário: " . mysqli_error($conexao) . "</p>
                            </div>";
        }
    }
}

// Fecha a conexão
mysqli_close($conexao);
?>
