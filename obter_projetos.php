<?php
// Carrega projetos do arquivo JSON
$arquivo = __DIR__ . '/conteudo/projetos.json';
$projetos = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
header('Content-Type: application/json');
echo json_encode($projetos);
