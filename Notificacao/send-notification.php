<?php
declare(strict_types=1);

// Log do que foi recebido
$input = file_get_contents('php://input');
file_put_contents('input_log.txt', $input);

include("../IFRIEND/db/conexao.php");
require_once("vendor/autoload.php");

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

// Defina o cabeçalho para permitir requisições
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Obter os dados recebidos
$data = json_decode($input, true);
if (empty($data)) {
    error_log("Dados recebidos: " . file_get_contents('php://input'));
    echo json_encode(["status" => "error", "message" => "Nenhum dado recebido"]);
    exit;
}

// Decodificar o JSON
$subscriptionData = json_decode($input, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["status" => "error", "message" => "Erro ao decodificar JSON: " . json_last_error_msg()]);
    exit;
}

// Criar a assinatura
try {
    $subscription = Subscription::create($subscriptionData);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Erro ao criar assinatura: " . $e->getMessage()]);
    exit;
}

// Configurar a autenticação para envio de push
$auth = [
    'VAPID' => [
        'subject' => 'mailto:me@website.com',
        'publicKey' => 'BFmbASE5gxCruK3gt6won6XwqtsB6gR4NMuwbYUrL45UtzSZHLnPLaZk8xB6F_mvx3l8nKQWXM1xty0G8LpSYvY',
        'privateKey' => 'tewdCyOiF4aDxAz4KrreUbUNPstsweCrUnhMuKjnnuE',
    ],
];

$webPush = new WebPush($auth);

// Enviar a notificação
$report = $webPush->sendOneNotification(
    $subscription,
    json_encode([
        'title' => 'Título da Notificação',
        'body' => 'Corpo da Notificação',
        'url' => '../Projeto_Integrador/IFRIEND/index.php?menuop=tarefas'
    ]),
    ['TTL' => 5000]
);

// Exibir o resultado do envio
$responseStatus = [];
foreach ($report as $subscriptionId => $response) {
    if ($response->isSuccess()) {
        $responseStatus[] = ["status" => "success", "message" => "Notificação enviada com sucesso!"];
    } else {
        $responseStatus[] = ["status" => "error", "message" => "Erro ao enviar notificação: {$response->getReason()}"];
    }
}

echo json_encode($responseStatus);
?>
