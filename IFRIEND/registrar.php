<?php
// Conexão com o banco de dados
include "./db/conexao.php";

// Variável para mensagens de erro
$msg_error = "";
$msg_success = "";

// Verificação no banco de dados
if (isset($_POST["loginUser"]) && isset($_POST["senhaUser"])) {
    $loginUser = mysqli_escape_string($conexao, $_POST["loginUser"]);
    $senhaUser = hash('sha256', $_POST["senhaUser"]);

    // Verifica se o usuário já existe
    $sql_check = "SELECT * FROM tbusuarios WHERE loginUser = '$loginUser'";
    $rs_check = mysqli_query($conexao, $sql_check);

    if (mysqli_num_rows($rs_check) > 0) {
        $msg_error = "<div class='alert alert-danger mt-3'>
                        <p>Usuário já existe!</p>
                      </div>";
    } else {
        // Insere o novo usuário
        $sql_insert = "INSERT INTO tbusuarios (loginUser, senhaUser) VALUES ('$loginUser', '$senhaUser')";
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title>Registro - Agendador</title>
</head>
<body class="bg-secondary">

    <div class="container">
        <div class="row vh-100 align-items-center justify-content-center">
            <div class="col-10 col-sm-8 col-md-6 col-lg-4 p-4 bg-white shadow rounded">
                <div class="row justify-content-center mb-4">
                    <img src="./img/logo_agendador.png" alt="Agendador">
                </div>
                <form class="needs-validation" action="register.php" method="post" novalidate>
                    <div class="form-group mb-4">
                        <label class="form-label" for="loginUser">Login</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input class="form-control" type="text" name="loginUser" id="loginUser" required>
                            <div class="invalid-feedback">
                                Informe o login.
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label" for="senhaUser">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-key-fill"></i>
                            </span>
                            <input class="form-control" type="password" name="senhaUser" id="senhaUser" required>
                            <div class="invalid-feedback">
                                Informe a senha.
                            </div>
                        </div>
                        <?php
                            echo $msg_error;
                            echo $msg_success;
                        ?>
                    </div>
                    <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right"></i> Registrar</button>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="./js/validation.js"></script>
</body>
</html>
