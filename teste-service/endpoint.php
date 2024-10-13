<?php

// Exemplo de como processar a assinatura no service worker
// A lógica pode variar dependendo de como você está implementando o registro do service worker

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Salvar a assinatura em um banco de dados ou arquivo
    // (implementação depende da sua necessidade)

    // Responder que a assinatura foi salva
    echo json_encode(["status" => "success", "message" => "Assinatura recebida"]);
}
?>