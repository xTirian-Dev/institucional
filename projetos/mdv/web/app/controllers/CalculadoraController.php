<?php
require_once __DIR__ . '/../../libs/fpdf186/fpdf.php';

class CalculadoraController {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header("Location: ?url=login/index");
            exit;
        }

        require __DIR__ . '/../views/calculadora.php';
    }

    public function gerarPdf() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header("Location: ?url=login/index");
            exit;
        }

        $contrato = $_POST['contrato'] ?? '';
        $credito = $_POST['credito'] ?? '';
        $percentual = $_POST['percentualPago'] ?? '';
        $encerramento = $_POST['encerramentoGrupo'] ?? '';
        $resultado = $_POST['resultado'] ?? '';
        $usuario = $_SESSION['usuario']['nome'] ?? 'Usuário';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(0,10,"Relatório da Proposta",0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(0,10,"Número do contrato: $contrato",0,1);
        $pdf->Cell(0,10,"Crédito: R$ $credito",0,1);
        $pdf->Cell(0,10,"Percentual Pago: $percentual%",0,1);
        $pdf->Cell(0,10,"Encerramento do Grupo: $encerramento",0,1);
        $pdf->Cell(0,10,"Valor da Proposta: R$ $resultado",0,1);
        $pdf->Ln(5);
        $pdf->Cell(0,10,"Gerado por: $usuario",0,1);

        $pdf->Output("D", "proposta-$contrato.pdf"); // força download
    }

    public function consultarContrato() {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header("HTTP/1.1 401 Unauthorized");
            echo json_encode(["erro" => "Não autorizado"]);
            exit;
        }

        $contrato = $_POST['contrato'] ?? '';

        if (empty($contrato)) {
            header("Content-Type: application/json");
            echo json_encode(["erro" => "Contrato não informado"]);
            exit;
        }

        require_once __DIR__ . '/../../app/core/Database.php';
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT contrato, credito, percentual, encerramento, resultado 
                            FROM propostas 
                            WHERE contrato = :contrato 
                            ORDER BY criado_em DESC LIMIT 1");
        $stmt->execute([':contrato' => $contrato]);
        $proposta = $stmt->fetch(PDO::FETCH_ASSOC);

        header("Content-Type: application/json");
        if ($proposta) {
            // Já existe proposta → devolve os dados
            echo json_encode([
                "existe"     => true,
                "proposta"   => $proposta
            ]);
        } else {
            // Não existe proposta → libera form
            echo json_encode([
                "existe" => false
            ]);
        }
    }


}
