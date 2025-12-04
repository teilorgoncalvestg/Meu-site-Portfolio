<?php
// Salva ou edita um projeto em um slot específico
$arquivo = __DIR__ . '/conteudo/projetos.json';

$maxSlots = 20;
$projetos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : array_fill(0, $maxSlots, null);

$idx = isset($_POST['idx']) ? intval($_POST['idx']) : null;
$nome = trim($_POST['nome'] ?? '');
$link = trim($_POST['link'] ?? '');
$capa = null;

// Upload da imagem de capa
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['capa']['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($ext, $permitidas)) {
        $novo_nome = 'capa_' . time() . '_' . rand(1000,9999) . '.' . $ext;
        $destino = 'conteudo/img/' . $novo_nome;
        if (move_uploaded_file($_FILES['capa']['tmp_name'], __DIR__ . '/' . $destino)) {
            $capa = $destino;
        }
    }
}
// Se não enviou nova capa, mantém a antiga
if ($idx !== null && isset($projetos[$idx]) && $projetos[$idx] && !$capa) {
    $capa = $projetos[$idx]['capa'] ?? null;
}

if ($idx !== null && $nome && $link) {
    $projetos[$idx] = [
        'nome' => $nome,
        'link' => $link,
        'capa' => $capa
    ];
    file_put_contents($arquivo, json_encode($projetos, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    header('Location: admin_panel.php#projetos');
    exit;
}
header('Location: admin_panel.php?erro=1');
exit;
