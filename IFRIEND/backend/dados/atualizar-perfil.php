<header>
    <h3>Atualizar Perfil</h3>
</header>

<?php
    $nomeUser = mysqli_real_escape_string($conexao,$_POST["nomeUser"]); 
    $emailUser = mysqli_real_escape_string($conexao,$_POST["emailUser"]); 
    $telefoneUser = mysqli_real_escape_string($conexao,$_POST["telefoneUser"]); 
    $enderecoUser = mysqli_real_escape_string($conexao,$_POST["enderecoUser"]); 
    $sexoUser = mysqli_real_escape_string($conexao,$_POST["sexoUser"]); 
    $dataNascUser = mysqli_real_escape_string($conexao,$_POST["dataNascUser"]); 

    $sql = "UPDATE cl203156.tbusuarios SET
    nomeUser = '{$nomeUser}',
    emailUser = '{$emailUser}',
    telefoneUser = '{$telefoneUser}',
    enderecoUser = '{$enderecoUser}',
    sexoUser = '{$sexoUser}',
    dataNascUser = '{$dataNascUser}'
    ";
    mysqli_query($conexao,$sql) or die("Erro ao executar a consulta. " . mysqli_error($conexao));
?>
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Atualizar Perfil</h4>
  <p>Perfil atualizada com sucesso.</p>
  <hr>
  <p class="mb-0">
  <button type="button" class="btn btn-outline-secondary"><a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
   href="?menuop=exibir-perfil" style="text-decoration: none;">Voltar para o Perfil</a>
    </button>
  </p>
</div>