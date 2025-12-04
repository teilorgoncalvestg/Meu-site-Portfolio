<?php
// Arquivo: enviar_email.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $mensagem = isset($_POST['message']) ? trim($_POST['message']) : '';

    if ($nome && $email && $mensagem) {
        $to = 'teilorgoncalvestg@gmail.com'; // Troque para seu e-mail
        $subject = 'Novo contato do site';
        $body = "Nome: $nome\nE-mail: $email\nMensagem:\n$mensagem";
        $headers = "From: $email\r\nReply-To: $email\r\n";

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao enviar e-mail.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos.']);
    }
    exit;
}
echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
exit;
