<?php

session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header('Location: admin_login.php');
    exit;
}

define('UPLOAD_DIR', 'conteudo/img/');
define('FOTO_TXT', 'conteudo/foto_perfil.txt');
$foto_nome = isset($_SESSION['foto_perfil']) ? $_SESSION['foto_perfil'] : (file_exists(FOTO_TXT) ? trim(file_get_contents(FOTO_TXT)) : 'WhatsApp Image 2025-08-22 at 22.23.21.jpeg');
$erro = '';

// Aceita tanto 'nova_foto' (cropper) quanto 'foto' (form tradicional)
if ((isset($_FILES['nova_foto']) && $_FILES['nova_foto']['error'] === UPLOAD_ERR_OK) || (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK)) {
    $file = isset($_FILES['nova_foto']) ? $_FILES['nova_foto'] : $_FILES['foto'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($ext, $permitidas)) {
        $novo_nome = 'profile_' . date('YmdHis') . '_' . rand(1000,9999) . '.' . $ext;
        $destino = UPLOAD_DIR . $novo_nome;
        if (move_uploaded_file($file['tmp_name'], $destino)) {
            // Remover foto antiga se não for a padrão
            if ($foto_nome !== $novo_nome && $foto_nome !== 'WhatsApp Image 2025-08-22 at 22.23.21.jpeg' && file_exists(UPLOAD_DIR . $foto_nome)) {
                unlink(UPLOAD_DIR . $foto_nome);
            }
            $_SESSION['foto_perfil'] = $novo_nome;
            file_put_contents(FOTO_TXT, $novo_nome);
            header('Location: admin_panel.php?foto=ok');
            exit;
        } else {
            $erro = 'Erro ao salvar o arquivo.';
        }
    } else {
        $erro = 'Formato de imagem não permitido.';
    }
} else if (isset($_FILES['nova_foto']) || isset($_FILES['foto'])) {
    $erro = 'Erro no upload da imagem.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Upload Foto de Perfil</title>
</head>
<body>
    <?php if ($erro): ?>
        <p style="color:red;">Erro: <?= htmlspecialchars($erro) ?></p>
        <a href="admin_panel.php">Voltar</a>
    <?php endif; ?>
</body>
</html>
