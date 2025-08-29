<?php
class LoginController {
    public function index() {
        session_start();

        // Se já estiver logado, redireciona para calculadora
        if (isset($_SESSION['usuario'])) {
            header("Location: ?url=calculadora/index");
            exit;
        }

        require __DIR__ . '/../views/login.php';
    }

    public function autenticar() {
        session_start();
        require_once __DIR__ . '/../core/Database.php';
        $db = Database::getConnection();

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->execute([':email' => $email, ':senha' => $senha]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $_SESSION['usuario'] = $usuario;
            header("Location: ?url=calculadora/index");
            exit;
        } else {
            $_SESSION['erro'] = "Email ou senha inválidos!";
            header("Location: ?url=login/index");
            exit;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ?url=login/index");
        exit;
    }
}
