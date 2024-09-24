<header>
    <h3>Atualizar Contato</h3>
</header>

<?php
    $idContato = mysqli_real_escape_string($conexao,$_POST["idContato"]); 
    $nomeContato = mysqli_real_escape_string($conexao,$_POST["nomeContato"]); 
    $emailContato = mysqli_real_escape_string($conexao,$_POST["emailContato"]); 
    $telefoneContato = mysqli_real_escape_string($conexao,$_POST["telefoneContato"]); 
    $enderecoContato = mysqli_real_escape_string($conexao,$_POST["enderecoContato"]); 
    $sexoContato = mysqli_real_escape_string($conexao,$_POST["sexoContato"]); 
    $dataNascContato = mysqli_real_escape_string($conexao,$_POST["dataNascContato"]); 

    $sql = "UPDATE tbcontatos SET
    nomeContato = '{$nomeContato}',
    emailContato = '{$emailContato}',
    telefoneContato = '{$telefoneContato}',
    enderecoContato = '{$enderecoContato}',
    sexoContato = '{$sexoContato}',
    dataNascContato = '{$dataNascContato}'
    WHERE idContato = '{$idContato}'
    ";
    mysqli_query($conexao,$sql) or die("Erro ao executar a consulta. " . mysqli_error($conexao));
?>
<div class="alert alert-success" role="alert">
  <h4 class="alert-heading">Atualizar Contato</h4>
  <p>Contato atualizada com sucesso.</p>
  <hr>
  <p class="mb-0">
  <button type="button" class="btn btn-outline-secondary"><a class="link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="?menuop=contatos" style="text-decoration: none;">Voltar para a lista de Contatos</a>
    </button>
  </p>
</div>