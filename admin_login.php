<?php
session_start();
// Carregar variáveis do .env
$env = parse_ini_file(__DIR__ . '/.env');

// Usuário e senha vindos do .env
$usuario_correto = $env['ADMIN_USER'];
$senha_correta = $env['ADMIN_PASS'];

if (!isset($_SESSION['tentativas'])) {
    $_SESSION['tentativas'] = 0;
}
if (isset($_POST['reset_tentativas'])) {
    $_SESSION['tentativas'] = 0;
    $erro = 'Tentativas resetadas. Tente fazer login novamente.';
}

if (isset($_POST['usuario']) && isset($_POST['senha'])) {
    if ($_SESSION['tentativas'] >= 5) {
        $erro = 'Muitas tentativas. Tente novamente em alguns minutos.';
    } elseif ($_POST['usuario'] === $usuario_correto && $_POST['senha'] === $senha_correta) {
        $_SESSION['logado'] = true;
        $_SESSION['usuario'] = $_POST['usuario'];
        $_SESSION['tentativas'] = 0;
        header('Location: admin_panel.php');
        exit;
    } else {
        $_SESSION['tentativas']++;
        $erro = 'Usuário ou senha incorretos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Login do Painel</h1>
    <form method="post">
        <input type="text" name="usuario" placeholder="Nome de usuário" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
    <form method="post" style="margin-top:10px;">
        <input type="hidden" name="reset_tentativas" value="1">
        <button type="submit">Resetar tentativas de login</button>
    </form>
    <p style="font-size:12px;color:#888;">Dica: senha padrão é 'a quela que vc constuma usar'.</p>
    <?php if (isset($erro)) echo '<p style="color:red;">'.$erro.'</p>'; ?>
    <script src="js/main.js"></script>
</body>
</html>
