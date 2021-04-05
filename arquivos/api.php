<?php

function send_json($data, $code = 200) {
    header('HTTP/1.1 ' . $code);
    header("Content-Type: application/json");
    header("Accept: application/json");
    header("Access-Control-Allow-Headers: api-token");
    header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
    header("Access-Control-Allow-Origin: *");
    echo json_encode($data);
    die();
}

function getFiles($path) {
    $files = [];
    $opn_dir = opendir($path);
    while ($rfile = readdir($opn_dir)) {
        if (substr($rfile, 0, 1) != "_" && substr($rfile, 0, 1) != "." && $rfile != "lost+found") {
            $files[$rfile] = ['name' => $rfile, 'is_dir' => is_dir($path . '/' . $rfile)];
        }
    }
    ksort($files, SORT_NATURAL);
    return $files;
}

/* Main path */
$main_path = "./files";
$main_real_path = realpath($main_path);

/* Path from url */
$path = substr(trim(urldecode(strtok($_SERVER['REQUEST_URI'], '?'))), 4);
$real_path = realpath($main_path . $path);

/* Verificando se a pasta existe */
if (!$real_path) {
    send_json(['success' => false, 'message' => 'Not found'], 404);
}

/* Para evitar sair da pasta principal */
if (substr($real_path, 0, strlen($main_real_path)) !== $main_real_path) {
    send_json(['error']);
}

/* Verificando se o path é um dir */
if (!is_dir($real_path)) {
    send_json([
        'success' => true,
        'url' => (substr($real_path, strlen(realpath("./")))),
    ]);
}

/* Eviando arquivos para o front */
$listed_files = getFiles($real_path);
send_json([
    'success' => true,
    'path' => ($path !== "/" ? $path : ""),
    'url' => (substr($real_path, strlen(realpath("./")))),
    'files' => $listed_files
]);
