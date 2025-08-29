<?php
class CalculadoraController {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header("Location: ?url=login/index");
            exit;
        }

        require __DIR__ . '/../views/calculadora.php';
    }
}
