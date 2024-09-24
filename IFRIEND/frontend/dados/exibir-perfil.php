<?php
$nomeUser = isset($_GET['nomeUser']) ? $_GET['nomeUser'] : null;

$loginUser = $_SESSION["loginUser"];
$nomeUser = $_SESSION["nomeUser"];

if ($nomeUser === null) {
    die("ID do contato não foi fornecido.");
}

$sql = "SELECT * FROM cl203156.tbusuarios WHERE nomeUser = '{$nomeUser}'";
$rs = mysqli_query($conexao, $sql) or die("Erro ao recuperar os dados do registro." . mysqli_error($conexao));

$dados = mysqli_fetch_assoc($rs);

if ($dados === null) {
    die("Nenhum contato encontrado com esse ID.");
}
?>


<header>
    <h3>Editar Perfil</h3>
</header>
<div class="row">
<div class="col-6" >
    <form action="index.php?menuop=atualizar-perfil" method="post">
        <div class="mb-3">
            <label class="form-label" for="nomeUser">Nome</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-person-fill"></i>
                </span>
                <input class="form-control" type="text" name="nomeUser" value="<?=$dados['nomeUser']?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="emailUser">E-Mail</label>
            <div class="input-group">
                <span class="input-group-text">@</span>
                <input class="form-control" type="email" name="emailUser" value="<?=$dados['emailUser']?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="telefoneUser">Telefone</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-telephone-fill"></i>
                </span>
                <input class="form-control" type="text" name="telefoneUser" value="<?=$dados['telefoneUser']?>">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="enderecoUser">Endereço</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-mailbox2"></i>
                </span>
                <input class="form-control" type="text" name="enderecoUser" value="<?=$dados['enderecoUser']?>">
            </div>
        </div>
        <div class="row mb-3">
                        <div class="mb-3 col-3">
                            <label class="form-label" for="sexoUser">Sexo</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-gender-ambiguous"></i>
                                </span>
                            <select class="form-select"  name="sexoUser" id="sexoUser">
                                <option <?php echo ($dados['sexoUser']=='')?'selected':'' ?> value="">Selecione o genero</option>
                                <option <?php echo ($dados['sexoUser']=='M')?'selected':''?> value="M">Masculino</option>
                                <option <?php echo ($dados['sexoUser']=='F')?'selected':''?> value="F">Feminino</option>
                                <option <?php echo ($dados['sexoUser']=='O')?'selected':''?> value="O">Outros</option>
                            </select>
                            </div>
                        </div>
                        <div class="mb-3 col-3">
                            <label class="form-label" for="dataNascUser">Data de Nasc.</label>
                            <input class="form-control" type="date" name="dataNascUser" value="<?=$dados['dataNascUser']?>">
                        </div>
                        </div>
        <div class="mb-3">
            <input class="btn btn-warning" type="submit" value="Atualizar" name="btnAtualizar">
        </div>
    </form>
</div>
</div>
