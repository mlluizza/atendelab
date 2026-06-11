<?php

class UsuariosController
{
    private PDO $pdo;

    public function __construct()
    {
        require_once __DIR__ . '/../../config/database.php';
        $this->pdo = $pdo;

        header('Content-Type: application/json; charset=utf-8');
    }

    public function listar()
    {
        $sql = "SELECT id, nome, email, perfil, status FROM usuarios ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios);
    }

    public function buscarPorId()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['erro' => 'ID é obrigatório.']);
            return;
        }

        $sql = "SELECT id, nome, email, perfil, status FROM usuarios WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            http_response_code(404);
            echo json_encode(['erro' => 'Usuário não encontrado.']);
            return;
        }

        echo json_encode($usuario);
    }

    public function criar()
{
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $perfil = $_POST['perfil'] ?? 'atendente';
    $status = $_POST['status'] ?? 'ativo';

    if (empty($nome) || empty($email) || empty($senha)) {
        http_response_code(400);
        echo json_encode(['erro' => 'Nome, e-mail e senha são obrigatórios.']);
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['erro' => 'E-mail inválido.']);
        return;
    }

    if (!in_array($perfil, ['admin', 'aluno', 'atendente'], true)) {
        http_response_code(400);
        echo json_encode(['erro' => 'Perfil inválido.']);
        return;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, senha, perfil, status)
            VALUES (:nome, :email, :senha, :perfil, :status)";

    $stmt = $this->pdo->prepare($sql);

    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senhaHash);
    $stmt->bindValue(':perfil', $perfil);
    $stmt->bindValue(':status', $status);

    try {
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            'mensagem' => 'Usuário cadastrado com sucesso.',
            'id' => $this->pdo->lastInsertId()
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['erro' => 'Erro ao cadastrar usuário.']);
    }
}

    public function atualizar()
    {
        echo json_encode(['mensagem' => 'Método atualizar ainda será implementado.']);
    }

    public function excluir()
    {
        echo json_encode(['mensagem' => 'Método excluir ainda será implementado.']);
    }
}