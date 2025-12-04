<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: admin_login.php');
    exit;
}
// Exemplo simples de salvamento

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sobre'])) {
        file_put_contents('conteudo/sobre.txt', $_POST['sobre']);
    }
    if (isset($_POST['habilidades'])) {
        file_put_contents('conteudo/habilidades.txt', $_POST['habilidades']);
    }
    if (isset($_POST['formacao'])) {
        file_put_contents('conteudo/formacao.txt', $_POST['formacao']);
    }
    if (isset($_POST['certificados'])) {
        file_put_contents('conteudo/certificados.txt', $_POST['certificados']);
    }
    // Salvar serviços
    if (isset($_POST['web'])) {
        file_put_contents('conteudo/servicos_web.txt', $_POST['web']);
    }
    if (isset($_POST['prog'])) {
        file_put_contents('conteudo/servicos_prog.txt', $_POST['prog']);
    }
    // Salvar títulos dos serviços
    if (isset($_POST['titulo_servicos'])) {
        file_put_contents('conteudo/titulo_servicos.txt', $_POST['titulo_servicos']);
    }
    if (isset($_POST['titulo_web'])) {
        file_put_contents('conteudo/titulo_web.txt', $_POST['titulo_web']);
    }
    if (isset($_POST['titulo_prog'])) {
        file_put_contents('conteudo/titulo_prog.txt', $_POST['titulo_prog']);
    }

    // Salvar títulos do perfil
    if (isset($_POST['titulo_sobre'])) {
        file_put_contents('conteudo/titulo_sobre.txt', $_POST['titulo_sobre']);
    }
    if (isset($_POST['titulo_habilidades'])) {
        file_put_contents('conteudo/titulo_habilidades.txt', $_POST['titulo_habilidades']);
    }
    if (isset($_POST['titulo_formacao'])) {
        file_put_contents('conteudo/titulo_formacao.txt', $_POST['titulo_formacao']);
    }
    if (isset($_POST['titulo_certificados'])) {
        file_put_contents('conteudo/titulo_certificados.txt', $_POST['titulo_certificados']);
    }
    header('Location: admin_panel.php?salvo=1');
    exit;
}
echo 'Nada para salvar.';
