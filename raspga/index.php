<?php
/*configurações*/
$db_username = "nome de suário";
$db_password = "senha";
$db_name = "banco de dados";
$db_host = "localhost";
$domains_blacklist = ["rasp.ga"]; //dominios que não podem ser encurtados

$path = trim(strip_tags(addslashes(substr(urldecode(strtok($_SERVER['REQUEST_URI'], '?')), 1))));

/* carregar pagina inicial */
if (empty($_POST) && $path == "") {
    include './front.php';
    die();
}

function json_response($data) {
    header("Content-Type: application/json");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

/* iniciando banco de dados */
try {
    $pdo = new PDO("mysql:host={$db_host};dbname=$db_name", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /* descomentar essa linha se for a primeira execução */
    //$pdo->prepare("CREATE TABLE IF NOT EXISTS `links` (`id` INT AUTO_INCREMENT NOT NULL, `uuid` VARCHAR(32) UNIQUE NOT NULL, `url` TEXT NOT NULL, `sender_ip` VARCHAR(64), `created_at` DATETIME NOT NULL DEFAULT NOW(), PRIMARY KEY(`id`))")->execute();
} catch (PDOException $ex) {
    json_response(['success' => false, 'message' => 'erro ao inicializar o banco de dados']);
}

/* criando link encurtado */
if (!empty($_POST)) {
    $uuid = $_POST['uuid'];
    $url = $_POST['url'];
    if (preg_match("/[^\\w\\d]/", $uuid)) {
        json_response(['success' => false, 'message' => 'proibido caracteres especiais no nome']);
    }
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        json_response(['success' => false, 'message' => 'url inválida']);
    }
    if (in_array(parse_url($url)['host'], $domains_blacklist)) {
        json_response(['success' => false, 'message' => 'esse link não pode ser encurtado']);
    }
    if (strlen($uuid) > 32) {
        json_response(['success' => false, 'message' => 'nome não pode ser maior que 32 caracteres']);
    }
    try {
        $prepare = $pdo->prepare("SELECT `url` FROM `links` WHERE `uuid` = ?");
        $prepare->execute([$uuid]);
        $fetch = $prepare->fetchAll(PDO::FETCH_OBJ);
        if (!empty($fetch)) {
            json_response(["success" => false, "message" => "esse nome já está em uso"]);
        }
        $ip = isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'];
        $pdo->prepare("INSERT INTO `links` (`url`, `uuid`, `sender_ip`) VALUES (?, ?, ?)")->execute([$url, $uuid, $ip]);
        json_response(["success" => true, "message" => "Url encurtada com sucesso para {$uuid}"]);
    } catch (PDOException $ex) {
        json_response(["success" => false, "message" => "erro interno no banco de dados"]);
    }
    return;
}

/* acessando link encurtado */
$uuid = ($path != "/" && $path != "") ? substr($path, 0, 32) : null;
if (!is_null($uuid)) {
    try {
        $prepare = $pdo->prepare("SELECT `url` FROM `links` WHERE `uuid` = ?");
        $prepare->execute([$uuid]);
        $fetch = $prepare->fetchAll(PDO::FETCH_OBJ);
        if (!empty($fetch)) {
            header("Location: " . $fetch[0]->url);
            die();
        }
        json_response(["success" => false, "message" => "link não encontrado"]);
    } catch (PDOException $ex) {
        json_response(["success" => false, "message" => "erro interno no banco de dados"]);
    }
}
