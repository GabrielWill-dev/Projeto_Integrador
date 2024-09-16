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
                    <img src="../../img/logo_agendador.png" alt="Agendador">
                </div>
                <form class="needs-validation" action="../../backend/register/registrar.php" method="post" novalidate>
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
                        <label class="form-label" for="nomeUser">Nome</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>
                            <input class="form-control" type="text" name="nomeUser" id="nomeUser" required>
                            <div class="invalid-feedback">
                                Informe o nome.
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
                    </div>
                    <button class="btn btn-success w-100"><i class="bi bi-box-arrow-in-right"></i> Registrar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Função para obter parâmetros da URL
        function getQueryParams() {
            const params = new URLSearchParams(window.location.search);
            return {
                success: params.get('success'),
                error: params.get('error')
            };
        }

        // Exibe mensagens de erro e sucesso
        function displayMessages() {
            const { success, error } = getQueryParams();

            if (success) {
                alert(success);
                // Redireciona após 3 segundos
                setTimeout(() => {
                    window.location.href = '../../login.php';
                }, 500);
            }

            if (error) {
                alert(error);
            }
        }

        // Chama a função para exibir mensagens
        displayMessages();
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="../../js/validation.js"></script>
</body>
</html>
