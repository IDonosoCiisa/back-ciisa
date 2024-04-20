<?php
include_once '../config.php';
if ($_version == 'v1' && $_api == 'api' && $_endpoint == 'services') {
    switch ($_metodo) {
        case 'GET':
            if ($_header == $_token_get){
                include_once 'controller.php';
                $control = new Controlador();
                http_response_code(200);
                echo $control->getAllServices();
            }
            else {
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