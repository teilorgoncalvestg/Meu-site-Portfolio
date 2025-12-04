<?php
// Remove um projeto do slot
$arquivo = __DIR__ . '/conteudo/projetos.json';
$maxSlots = 20;
$projetos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : array_fill(0, $maxSlots, null);
$idx = isset($_POST['idx']) ? intval($_POST['idx']) : null;
if ($idx !== null && isset($projetos[$idx])) {
    $projetos[$idx] = null;
    file_put_contents($arquivo, json_encode($projetos, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES));
    header('Location: admin_panel.php#projetos');
    exit;
}
header('Location: admin_panel.php?erro=1');
exit;
