<?php
$_metodo = $_SERVER['REQUEST_METHOD'];
$_ubicacion = $_SERVER['HTTP_HOST'];
$_path = $_SERVER['REQUEST_URI'];
$_partes = explode('/', $_path);
$_version = $_partes[3];
$_api = $_partes[2];
$_endpoint = explode('.', $_partes[4])[0];
$_parametros = $_partes[4];
if (strpos($_parametros, 'id') !== false) {
    $valorId = explode('=', $_parametros)[1];
}

//header
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header("Content-Type: application/json; charset=UTF-8");

//Authorization
$_header = null;
try {
    $_header = isset(getallheaders()['Authorization']) ? getallheaders()['Authorization'] : null;
    if ($_header === null) {
        throw new Exception("No tiene autorizacion");
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(['Error' => $e->getMessage()]);
}

//Tokens
$_token_ciisa = 'Bearer ciisa';
$_token_get = 'Bearer get';
$_token_post = 'Bearer post';
$_token_patch = 'Bearer patch';
$_token_put = 'Bearer put';
$_token_delete = 'Bearer delete';
