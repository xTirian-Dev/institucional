<?php
require_once __DIR__ . '/../core/Database.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function verificarLogin($email, $senha) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha // em produção, usar hash (password_hash)
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
