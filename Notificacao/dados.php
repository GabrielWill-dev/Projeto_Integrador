<?php
include("../IFRIEND/db/conexao.php");
require_once("vendor/autoload.php");

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

    // Verificar a conexão
    if ($conexao->connect_error) {
        die("Erro de conexão: " . $conexao->connect_error);
    }

    // Obter a data e hora atuais
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date('Y-m-d H:i');

    // Executar a consulta para pegar os dados da tarefa
    $sql = "SELECT * FROM tbtarefas WHERE dataLembreteTarefa = '$dataAtual'";
    $result = $conexao->query($sql);

    // Verificar se há resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Pega os valores do banco de dados
            $titulo = $row['tituloTarefa'];
            $descricao = $row['descricaoTarefa'];
            $dataConclusao = $row['dataConclusaoTarefa']; // Pegando a data de conclusão da tarefa
    
            // Formatar o campo body com descricao e dataConclusaoTarefa
            $body = $descricao . " (Conclusão: " . $dataConclusao . ")";
    
            // Autenticação para envio de Push
            $auth = [
                'VAPID' => [
                    'subject' => 'mailto:me@website.com',
                    'publicKey' => 'BFmbASE5gxCruK3gt6won6XwqtsB6gR4NMuwbYUrL45UtzSZHLnPLaZk8xB6F_mvx3l8nKQWXM1xty0G8LpSYvY',
                    'privateKey' => 'tewdCyOiF4aDxAz4KrreUbUNPstsweCrUnhMuKjnnuE',
                ],
            ];

    
            $webPush = new WebPush($auth);
    
            // Criar a assinatura da notificação
            $subscription = Subscription::create(json_decode('{
                "endpoint":"https://fcm.googleapis.com/fcm/send/eETGjsOk0-I:APA91bGOTUT1h-E0hzl6C0A-4nvWtmKGW8BmixAWK343t4F-TQ3JzitakiqIBCiSnlqpWQfNA0shmrnTXSt5SLM9Gzob7OJZsqxg8Dldo_a_4MBFHW_Sn8wCnNZUpliuRc3xr60uzx7X",
                "expirationTime":null,
                "keys":{
                    "p256dh":"BHzhDNrBrmlKUZSAp_g8iDqyIpSbB7Pc2Y8jzsGuWlXG0gZkrmUnsWiSfFp99LoA2sGiMU_JsdsXTCKQiQBeH7s",
                    "auth":"q8J2RUaHr-xWCaK3tS-eYA"
                }
            }', true));
    
            // Enviar a notificação com o body (descricao + dataConclusao) e title (título)
            $report = $webPush->sendOneNotification(
                $subscription,
                json_encode([
                    'title' => $titulo,
                    'body' => $body,
                    'url' => '../Projeto_Integrador/IFRIEND/index.php?menuop=tarefas'
                ]),
                ['TTL' => 1000]
            );
            echo json_encode(["Enviado" => $row], JSON_PRETTY_PRINT);
            }
        }else {
        echo json_encode(["mensagem" => "Nenhum dado encontrado"], JSON_PRETTY_PRINT);
    }
    // Esperar 5 minutos antes de verificar novamente
    //sleep(5); // 5 segundos
?>
