<?php
include("./db/conexao.php");


session_start();
if (empty($_SESSION)) {
    header("Location login.php/");
}


if (isset($_SESSION["loginUser"]) and isset($_SESSION["senhaUser"])) {
    $loginUser = $_SESSION["loginUser"];
    $senhaUser = $_SESSION["senhaUser"];
    $nomeUser = $_SESSION["nomeUser"];

    $sql = "SELECT * FROM cl203156.tbusuarios WHERE loginUser = '{$loginUser}' and senhaUser = '{$senhaUser}'";
    $rs = mysqli_query($conexao, $sql);
    $dados = mysqli_fetch_assoc($rs);
    $linha = mysqli_num_rows($rs);

    if ($linha == 0) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
} else {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/estilo-padrao.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" type="" href="img/favicon.ico" />
    <title>iFriend</title>
    <script>
        setInterval(() => {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '../Notificacao/dados.php', true);

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300)
                    console.log(xhr.responseText);  // Processa a resposta
                else
                    console.error('Erro na requisição:', xhr.statusText);
            };

            xhr.onerror = () => console.error('Erro de rede.');

            xhr.send();

        }, 30000);
    </script>
</head>

<body>
    <header class="bg-dark">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <a href="#" class="navbar-brand">
                    <img src="./img/iFriendatt.png" alt="iFriend" width="120">
                </a>

                <div class="collapse navbar-collapse" id="conteudoNavbarSuportado">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item"><a href="index.php?menuop=home" class="nav-link active">Inicio</a></li>
                        <li class="nav-item"><a href="index.php?menuop=tarefas" class="nav-link">Tarefas</a></li>
                        <li class="nav-item"><a href="index.php?menuop=eventos" class="nav-link">Eventos</a></li>
                    </ul>
                    <div class="navbar-nav w-100 justify-content-end">
                        <a href="index.php?menuop=exibir-perfil" class="nav-link justify-content-end">
                            <button type="button" class="btn btn-dark"><i
                                    class="bi bi-person "></i><?= $nomeUser ?></button>
                        </a>
                        <a href="logout.php" class="nav-link">
                            <button type="button" class="btn btn-dark">Sair <i
                                    class="bi bi-box-arrow-right"></i></button>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
            $menuop = (isset($_GET['menuop'])) ? $_GET['menuop'] : 'home';
            switch ($menuop) {
                case 'home':
                    include("./paginas/home/teste.php");
                    break;
                case 'exibir-perfil':
                    include("./frontend/dados/exibir-perfil.php");
                    break;
                case 'editar-perfil':
                    include("./frontend/dados/editar-perfil.php");
                    break;
                case 'atualizar-perfil':
                    include("./backend/dados/atualizar-perfil.php");
                    break;
                case 'eventos':
                    include("./paginas/eventos/eventos.php");
                    break;
                case 'cad-evento':
                    include("./paginas/eventos/cad-evento.php");
                    break;
                case 'inserir-evento':
                    include("./paginas/eventos/inserir-evento.php");
                    break;
                case 'editar-evento':
                    include("./paginas/eventos/editar-evento.php");
                    break;
                case 'atualizar-evento':
                    include("./paginas/eventos/atualizar-evento.php");
                    break;
                case 'excluir-evento':
                    include("./paginas/eventos/excluir-evento.php");
                    break;
                case 'tarefas':
                    include("./paginas/tarefas/tarefas.php");
                    break;
                case 'cad-tarefa':
                    include("./paginas/tarefas/cad-tarefa.php");
                    break;
                case 'inserir-tarefa':
                    include("./paginas/tarefas/inserir-tarefa.php");
                    break;
                case 'editar-tarefa':
                    include("./paginas/tarefas/editar-tarefa.php");
                    break;
                case 'atualizar-tarefa':
                    include("./paginas/tarefas/atualizar-tarefa.php");
                    break;
                case 'excluir-tarefa':
                    include("./paginas/tarefas/excluir-tarefa.php");
                    break;

                default:
                    include("./paginas/home/home.php");
                    break;
            }

            ?>
        </div>
    </main>
    <script src="./js/jquery.js"></script>
    <script src="./js/jquery.form.js"></script>
    <script src="./js/upload.js"></script>
    <script src="./js/javascript-agendador.js"></script>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./js/validation.js"></script>

    <!-- Rodapé -->
    <div class="container">
        <footer class="text-center text-lg-start text-bg">
            <!-- Seção: Redes sociais -->
            <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
                <!-- Esquerda -->
                <!-- Direita -->
            </section>
            <!-- Seção: Redes sociais -->

            <!-- Seção: Links  -->
            <section class="">
                <div class="container text-center text-md-start mt-5">
                    <!-- Linha da grade -->
                    <div class="row mt-3">
                        <!-- Coluna da grade -->
                        <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                            <!-- Conteúdo -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                <i class="fas fa-gem me-3"></i>I Friend
                            </h6>
                            <p>
                                Aqui você pode usar linhas e colunas para organizar o conteúdo do rodapé. Lorem ipsum
                                dolor sit amet, consectetur adipisicing elit.
                            </p>
                        </div>
                        <!-- Coluna da grade -->

                        <!-- Coluna da grade -->
                        <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">
                                Links úteis
                            </h6>
                            <p>
                                <a href="index.php?menuop=home" class="text-reset">Inicio</a>
                            </p>
                            <p>
                                <a href="index.php?menuop=tarefas" class="text-reset">Tarefas</a>
                            </p>
                            <p>
                                <a href="index.php?menuop=eventos" class="text-reset">Eventos</a>
                            </p>
                            <p>
                                <a href="#!" class="text-reset">Sobre Nós</a> <!-- Precisa criar essa pagina -->
                            </p>
                        </div>
                        <!-- Coluna da grade -->

                        <!-- Coluna da grade -->
                        <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                            <!-- Links -->
                            <h6 class="text-uppercase fw-bold mb-4">Contato</h6>
                            <p><i class="fas fa-home me-3"></i> Limeira, SP 13484-431 , Brasil</p>
                            <p>
                                <i class="fas fa-envelope me-3"></i>
                                cl203156@g.unicamp.br
                            </p>
                            <p>
                                <i class="fas fa-envelope me-3"></i>
                                cl203151@g.unicamp.br
                            </p>
                            <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
                            <p><i class="fas fa-print me-3"></i> + 01 234 567 89</p>
                        </div>
                        <!-- Coluna da grade -->
                    </div>
                    <!-- Linha da grade -->
                </div>
            </section>
            <!-- Seção: Links  -->

            <!-- Direitos autorais -->
            <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
                © 2024 Direitos autorais:
                <a class="text-reset fw-bold" href="">link</a>
            </div>
            <!-- Direitos autorais -->
        </footer>
    </div>
    <!-- Rodapé -->
</body>



</html>