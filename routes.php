<?php

$controller = $_GET['controller'] ?? '';
$action = $_GET['action'] ?? '';

if ($controller === 'usuarios') {

    require_once __DIR__ . '/app/Controllers/UsuariosController.php';

    $usuariosController = new UsuariosController();

    switch ($action) {
        case 'listar':
            $usuariosController->listar();
            break;

        case 'buscar':
        case 'buscarPorId':
            $usuariosController->buscarPorId();
            break;

        case 'criar':
            $usuariosController->criar();
            break;

        case 'atualizar':
            $usuariosController->atualizar();
            break;

        case 'excluir':
            $usuariosController->excluir();
            break;

        default:
            http_response_code(404);
            echo json_encode(['erro' => 'Ação inválida.']);
            break;
    }

} else {
    echo '<h1>AtendeLab</h1>';
    echo '<p>Projeto em execução. Use ?controller=usuarios&action=listar para testar.</p>';
}