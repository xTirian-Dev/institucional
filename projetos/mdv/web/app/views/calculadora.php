<?php
session_start();

// Protege contra acesso sem login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        form {
            max-width: 400px;
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"], input[type="date"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #CC092F !important;
            box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 10px 0px !important;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .resultado, .error {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1.25rem;
            text-align: center;
        }
        .resultado a, .error a {
            text-decoration: underline;
        }
        .error {
            color: red;
        }
        .resultado {
            color: #333;
        }
    </style>
</head>
<body>
    <!-- Botão de logout -->
    <button onclick="window.location.href='?url=login/logout'">Sair</button>
    <h2> Usuário logado:
        <?php
            echo $_SESSION['usuario']['nome'] ?? 'Não encontrado';
        ?>
    </h2>
    
    
    <form id="calculadoraForm">
        <label for="contrato">Número do Contrato:</label>
        <input type="text" id="contrato" name="contrato" required placeholder="Ex: 123456">

        <label for="credito">Crédito (valor do bem):</label>
        <input type="text" id="credito" name="credito" required placeholder="Ex: 100.000,00" disabled>

        <label for="percentualPago">Percentual Pago (%):</label>
        <input type="text" id="percentualPago" name="percentualPago" required placeholder="Ex: 50" disabled>

        <label for="encerramentoGrupo">Encerramento do Grupo (Data):</label>
        <input type="date" id="encerramentoGrupo" name="encerramentoGrupo" required disabled>

        <input type="submit" value="Calcular" disabled>
    </form>


    <p id="resultado" class="resultado"></p>
    <p id="erro" class="error"></p>

    <!-- Botão de PDF (inicialmente escondido) -->
    <button id="btnPdf" style="display:none;">Gerar PDF</button>

<script>
    const btnPdf = document.getElementById('btnPdf');

    document.getElementById('contrato').addEventListener('focusout', function(e) {
        const contrato = e.target.value.trim();
        if (!contrato) return;

        const formData = new FormData();
        formData.append("contrato", contrato);

        fetch("?url=calculadora/consultarContrato", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.existe) {
                // ✅ Já existe proposta → preencher e bloquear
                document.getElementById("credito").value = data.proposta.credito;
                document.getElementById("percentualPago").value = data.proposta.percentual;
                document.getElementById("encerramentoGrupo").value = data.proposta.encerramento;
                document.getElementById("resultado").innerText = "Proposta já existente: R$ " + data.proposta.resultado;

                // bloquear edição
                document.getElementById("credito").disabled = true;
                document.getElementById("percentualPago").disabled = true;
                document.getElementById("encerramentoGrupo").disabled = true;
                document.querySelector("input[type=submit]").disabled = true;
                btnPdf.style.display = "block"; // já pode gerar PDF
            } else {
                // 🚀 Não existe proposta → liberar campos
                document.getElementById("credito").disabled = false;
                document.getElementById("percentualPago").disabled = false;
                document.getElementById("encerramentoGrupo").disabled = false;
                document.querySelector("input[type=submit]").disabled = false;
                btnPdf.style.display = "none";
                document.getElementById("resultado").innerText = "";
            }
        })
        .catch(err => {
            console.error("Erro na consulta:", err);
        });
    });

    // Máscara para o campo de crédito (formato R$)
    document.getElementById('credito').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, "");
        value = (value / 100).toFixed(2);
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+\,)/g, "$1.");
        e.target.value = value;
    });

    // Máscara para o campo de percentual (limita a 100%)
    document.getElementById('percentualPago').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, "");
        value = (value / 100).toFixed(2);
        value = value.replace(".", ",");
        if (parseFloat(value) > 100)
            value = 100;
        e.target.value = value;
    });

    // Função principal ao enviar o formulário
    document.getElementById('calculadoraForm').addEventListener('submit', function(e) {
        e.preventDefault();

        var contrato = document.getElementById('contrato').value;
        var credito = document.getElementById('credito').value;
        var percentualPago = document.getElementById('percentualPago').value;
        var encerramentoGrupo = document.getElementById('encerramentoGrupo').value;

        try {
            var resultado = calcularProposta(credito, percentualPago, encerramentoGrupo);

            if (resultado <= 0 || isNaN(resultado)) {
                throw new Error("Valores inválidos ou proposta abaixo do mínimo");
            }

            const valorFormatado = formatarMoeda(resultado);
            const mensagemWhatsApp = encodeURIComponent(`Olá, gostaria de falar sobre minha proposta de R$ ${valorFormatado}`);

            document.getElementById('erro').textContent = "";
            document.getElementById('resultado').innerHTML = "Valor da Proposta: <strong>R$ " + valorFormatado + "</strong><br>" + "Mensagem que será enviada"
                            
            //`<a href='https://web.whatsapp.com/send?phone=5511934352133&text=${mensagemWhatsApp}' target='_blank'>Falar com consultor</a>`;

            // AQUI: mostrar o botão PDF
             btnPdf.style.display = "block";
            
            

        } catch (error) {
            document.getElementById('erro').innerHTML = error.message + ', <a href="https://web.whatsapp.com/send?phone=5511934352133&text=" target="_blank">converse com um consultor</a>.';
            document.getElementById('resultado').textContent = "";
            // Se deu erro, esconde o botão PDF
             btnPdf.style.display = "none";
        }
    });

    function parseValor(valor) {
        if (typeof valor === 'string') {
            valor = valor.replace(/\./g, '').replace(',', '.');
        }
        return parseFloat(valor) || 0;
    }

    function formatarMoeda(valor) {
        return valor.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calcularMesesRestantes(dataEncerramento) {
        const hoje = new Date();
        const dataFim = new Date(dataEncerramento);

        const inicioHoje = new Date(hoje.getFullYear(),hoje.getMonth(),1);
        const inicioFim = new Date(dataFim.getFullYear(),dataFim.getMonth(),1);

        const anos = inicioFim.getFullYear() - inicioHoje.getFullYear();
        const meses = inicioFim.getMonth() - inicioHoje.getMonth();

        const totalMeses = (anos * 12) + meses;

        return Math.max(0, totalMeses);
    }

    function arredondarParaMilhares(valor) {
        return Math.round(valor / 1000) * 1000;
    }

    function calcularProposta(credito, percentual, dataEncerramento) {
        var valorCredito = parseValor(credito);
        var percentualPago = parseValor(percentual);
        var mesesRestantes = calcularMesesRestantes(dataEncerramento);
        const valorRecebivel = valorCredito * (percentualPago / 100);

        let resultadoCalculo = 0;

        // Validações básicas
        if (valorCredito <= 0 || percentualPago <= 0) {
            return 0;
        }

        let percentualProposta = 0;

        if (mesesRestantes <= 91) {
            percentualProposta = 0.605 - (mesesRestantes * 0.005);
        } else {
            percentualProposta = 0.15;
        }

        resultadoCalculo = valorCredito * (percentualPago * percentualProposta / 100);

        // Garante que a proposta não seja negativa
        resultadoCalculo = Math.max(0, resultadoCalculo);

        // Arredonda para múltiplos de 1.000
        resultadoCalculo = arredondarParaMilhares(resultadoCalculo);

        // Validação do valor mínimo
        if (resultadoCalculo < 3000) {
            throw new Error("Proposta abaixo do mínimo permitido (R$ 3.000,00)");
        }

        return resultadoCalculo*0.5;
    }

    btnPdf.addEventListener("click", function() {
        const resultadoElemento = document.getElementById("resultado");
        // extrai apenas o valor numérico
        const valorTexto = resultadoElemento.textContent || resultadoElemento.innerText;
        const valorApenas = valorTexto.match(/[\d.,]+/)[0]; // pega "3.600,00"

        const formData = new FormData();
        formData.append("contrato", document.getElementById("contrato").value);
        formData.append("credito", document.getElementById("credito").value);
        formData.append("percentualPago", document.getElementById("percentualPago").value);
        formData.append("encerramentoGrupo", document.getElementById("encerramentoGrupo").value);
        formData.append("resultado", valorApenas); // somente o número
        formData.append("usuario", "<?= $_SESSION['usuario_nome'] ?? 'Usuário'; ?>");

        fetch("?url=calculadora/gerarPdf", {
            method: "POST",
            body: formData
        })
        .then(res => res.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            window.open(url, "_blank");
        })
        .catch(err => alert("Erro ao gerar PDF: " + err));
    });



</script>
</body>
</html>
