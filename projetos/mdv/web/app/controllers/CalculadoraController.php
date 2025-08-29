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

}
