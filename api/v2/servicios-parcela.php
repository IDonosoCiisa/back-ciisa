<?php
include_once '../config.php';
if ($_version == 'v2' && $_api == 'api' && $_endpoint == 'servicios-parcela') {
    switch ($_metodo) {
        case 'GET':
            if ($_header == $_token_get) {
                include_once 'controller.php';
                $control = new ControladorServiciosParcela();
                $lista = $control->getAllServiciosParcela();
                http_response_code(200);
                echo json_encode(["data" => $lista]);
            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion GET"]);
            }
            break;
        case "POST":
            if ($_header == $_token_post) {
                include_once "controller.php";
                $control = new ControladorServiciosParcela();
                $body = json_decode(file_get_contents("php://input", true));
                $rs = $control->newServicioParcela($body->nombre);
                if ($rs) {
                    http_response_code(201);
                    echo json_encode(["data" => $rs]);
                } else {
                    http_response_code(400);
                    echo json_encode(["data" => "data no válida"]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion POST"]);
            }
            break;
        case "PATCH":
            if ($_header == $_token_patch) {
                include_once "controller.php";
                $control = new ControladorServiciosParcela();
                $body = json_decode(file_get_contents("php://input", true));
                $rs = $control->patchServicioParcela($body);
                if ($rs) {
                    http_response_code(200);
                    echo json_encode(["data" => $rs]);
                } else {
                    http_response_code(400);
                    echo json_encode(["data" => "data no válida"]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion POST"]);
            }
            break;
        case "PUT":
            if ($_header == $_token_put) {
                include_once "controller.php";
                $control = new ControladorServiciosParcela();
                $body = json_decode(file_get_contents("php://input", true));
                $rs = $control->putServicioParcela($body);
                if ($rs) {
                    http_response_code(200);
                    echo json_encode(["data" => $rs]);
                } else {
                    http_response_code(400);
                    echo json_encode(["data" => "data no válida"]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion POST"]);
            }
            break;
        case "DELETE":
            if ($_header == $_token_delete) {
                include_once "controller.php";
                $control = new ControladorServiciosParcela();
                $body = json_decode(file_get_contents("php://input", true));
                $rs = $control->deleteServicioParcela($body->id);
                if ($rs) {
                    http_response_code(200);
                    echo json_encode(["data" => $rs]);
                } else {
                    http_response_code(400);
                    echo json_encode(["data" => "data no válida"]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["Error" => "No tiene autorizacion DELETE"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["Error" => "No implementado"]);
            break;
    }
}