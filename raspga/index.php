<?php
/* 
 * Encurtador de links simples para quem tá aprendendo php
 * Esse codigo aborda algumas dicas de segurança, verificação de dados e outros
 * Lembrer-se de descomentar a linha do banco de dados para criar a tabela
 */

/*configurações*/
$db_username = "nome de suário";
$db_password = "senha";
$db_name = "banco de dados";
$db_host = "localhost";
$domains_blacklist = ["rasp.ga"]; //dominios que não podem ser encurtados
$names_blacklist = ["src"]; //nomes que não podem ser usados para encurtar links

$path = trim(strip_tags(addslashes(substr(urldecode(strtok($_SERVER['REQUEST_URI'], '?')), 1))));

/* carregar pagina inicial */
if (empty($_POST) && $path == ""): ?>
<!DOCTYPE html>
<html>
    <head>
        <title>RaspGa encurtador de links</title>
        <link rel="stylesheet" href="/src/css/estilo.css"/>
    </head>
    <body>
        <div class="row-center">
            <div class="form-center">
                <h1>Encurtador de links</h1>
                <div>
                    <label for="id">Nome da url</label><br>
                    <input type="text" id="id" placeholder="Ex: google" class="form-input">
                </div>
                <div>
                    <label for="url">Link a ser encurtado</label><br>
                    <input type="text" id="url" placeholder="Ex: https://google.com" class="form-input">
                </div>
                <button class="btn btn-green" onclick="shortLink()">Encurtar</button>
            </div>
            <div class='alert' style="opacity: 0">
                <p class="alert-message"></p>
            </div>
        </div>
    </body>
    <script src="/src/js/jquery-3.5.1.min.js"></script>
    <script src="/src/js/app.js"></script>
</html>
    <?php
    die();
endif;

function json_response($data) {
    header("Content-Type: application/json");
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

/* iniciando banco de dados */
try {
    $pdo = new PDO("mysql:host={$db_host};dbname=$db_name", $db_username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /* descomentar essa linha se for a primeira vez a primeira execução */
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
    if (in_array($uuid, $names_blacklist)) {
        json_response(['success' => false, 'message' => 'esse nome não pode ser usado']);
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
        json_response(["success" => true, "message" => "url encurtada com sucesso para {$uuid}"]);
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
