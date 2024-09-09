<?php
// Conexão com o banco de dados
include "../../db/conexao.php";

// Variáveis para mensagens de erro e sucesso
$msg_error = "";
$msg_success = "";

// Verificação no banco de dados
if (isset($_POST["loginUser"]) && isset($_POST["senhaUser"]) && isset($_POST["nomeUser"])) {
    $loginUser = mysqli_escape_string($conexao, $_POST["loginUser"]);
    $senhaUser = hash('sha256', $_POST["senhaUser"]);
    $nomeUser = mysqli_escape_string($conexao, $_POST["nomeUser"]);

    // Verifica se o usuário já existe
    $sql_check = "SELECT * FROM tbusuarios WHERE loginUser = '$loginUser'";
    $rs_check = mysqli_query($conexao, $sql_check);

    if (mysqli_num_rows($rs_check) > 0) {
        $msg_error = "Usuário já existe!";
    } else {
        // Insere o novo usuário
        $sql_insert = "INSERT INTO tbusuarios (loginUser, senhaUser, nomeUser) VALUES ('$loginUser', '$senhaUser', '$nomeUser')";
        if (mysqli_query($conexao, $sql_insert)) {
            $msg_success = "Usuário registrado com sucesso!";
        } else {
            $msg_error = "Erro ao registrar usuário: " . mysqli_error($conexao);
        }
    }
}

// Fecha a conexão
mysqli_close($conexao);

// Redireciona para o formulário com parâmetros de mensagem
header("Location: ../../frontend/register/registrar.php?" . http_build_query([
    'success' => $msg_success,
    'error' => $msg_error
]));
exit();
?>
