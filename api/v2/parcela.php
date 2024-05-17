<?php
include_once '../config.php';

if (strpos($_parametros, 'accion') !== false) {
    $valorAccion = explode('=', $_parametros)[1];
}
if (strpos($_parametros, 'id') !== false) {
    $valorId = explode('=', $_parametros)[1];
}

if ($_version == 'v2' && $_api == 'api' && $_endpoint == 'parcela') {
    switch ($_metodo) {
        case 'GET':
            if ($_header == $_token_get) {
                include_once 'controller.php';
                $control = new ControladorParcela();
                if (isset($valorId)) {
                    $data = new stdClass();
                    $data->terreno = $control->getParcelaById($valorId);
                    $data->services = $control->getParcelaServicesById($valorId);
                    http_response_code(200);
                    echo json_encode(["data" => $data]);
                } else {
                    $lista = $control->getAllParcelas();
                    http_response_code(200);
                    echo json_encode(["data" => $lista]);
                }

            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion GET"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["Error" => "No implementado"]);
            break;
    }
}