<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verificador de DNA</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>

    <div class="custom-popup" id="customPopup">
        <h2>Erro</h2>
        <p>A sequência de DNA só pode conter as letras A, T, C e G.</p>
        <button class="fechar-icon" onclick="fecharPopupErro()">Fechar</button>
    </div>
    <div id="verificador">
        <h1>Verificador de DNA</h1>
        <form id="dnaForm">
            @csrf
            <label id="label-sequencia" for="sequencia_dna">
                <b>Insira a Sequência de DNA (6 linhas x 6 colunas):</b>
            </label>
            <table>
                <tbody id="inputTable"></tbody>
            </table>
            <div id="resultado"></div>
            <button type="button" onclick="verificarDna()">Verificar</button>
            <button type="button" onclick="exibirHistorico()">Histórico</button>
        </form>
    </div>

    <div id="historico">
        <div class="historico-header">
            <button onclick="voltarParaVerificador()">Verificador</button>
            <span class="historico-titulo">
                <b>
                    Histórico de sequências
                </b>
            </span>
        </div>
        <div id="historicoList" class="historico-list"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function fecharPopupErro() {
            document.getElementById('customPopup').classList.remove('show');
        }

        async function exibirHistorico() {
            // Oculta o formulário
            document.getElementById('verificador').style.display = 'none';

            try {
                const response = await fetch('/exibir-historico', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Erro ao obter o histórico.');
                }

                const historico = await response.json();

                // Processa e exibe o historico
                exibirHistoricoNaPagina(historico);
            } catch (error) {
                Swal.fire({
                    title: 'Erro',
                    text: 'Ocorreu um erro ao obter o histórico.',
                    icon: 'error',
                    position: 'center'
                });
            }
        }

        function voltarParaVerificador() {
            document.getElementById('verificador').style.display = 'block';
            document.getElementById('historico').style.display = 'none';
        }

        function exibirHistoricoNaPagina(historico) {
            const historicoList = document.getElementById('historicoList');

            historico.forEach(analise => {
                const historicoItem = document.createElement('div');
                historicoItem.classList.add('historico-item');

                const sequenciaDiv = document.createElement('div');
                sequenciaDiv.classList.add('historico-sequencia');
                sequenciaDiv.textContent = analise.sequencia;

                historicoItem.appendChild(sequenciaDiv);
                historicoList.appendChild(historicoItem);
            });

            document.getElementById('historico').style.display = 'block';
        }

        function gerarTabelaEntrada() {
            var tableBody = document.getElementById('inputTable');
            tableBody.innerHTML = '';

            for (var i = 0; i < 6; i++) {
                var row = document.createElement('tr');

                for (var j = 0; j < 6; j++) {
                    var cell = document.createElement('td');
                    var input = document.createElement('input');

                    input.type = 'text';
                    input.name = 'dna_input[' + i + '][' + j + ']';
                    input.maxLength = 1;
                    input.addEventListener('input', manipularEntrada);

                    cell.appendChild(input);
                    row.appendChild(cell);
                }

                tableBody.appendChild(row);
            }
        }

        window.onload = gerarTabelaEntrada;

        function manipularEntrada(evento) {
            var entradaAtual = evento.target;
            var valorEntrada = entradaAtual.value.toUpperCase();

            var caracteresPermitidos = /^[ATCG\n]+$/;
            if (!caracteresPermitidos.test(valorEntrada)) {
                document.getElementById('customPopup').classList.add('show');
                entradaAtual.value = '';

                // Adiciona um evento de clique à div de fundo para fechar o popup
                document.getElementById('customPopup').addEventListener('click', function() {
                    document.getElementById('customPopup').classList.remove('show');
                });
            } else {
                // Move para o próximo campo
                moverFocoProximaEntrada(entradaAtual);
            }
        }

        function moverFocoProximaEntrada(entradaAtual) {
            var todasAsEntradas = Array.from(document.querySelectorAll('input[name^="dna_input"]'));
            var indiceAtual = todasAsEntradas.indexOf(entradaAtual);

            if (indiceAtual < todasAsEntradas.length - 1) {
                var proximaEntrada = todasAsEntradas[indiceAtual + 1];
                proximaEntrada.focus();
            }
        }

        async function verificarDna() {
            var sequenciaDna = '';
            var isValido = true;

            var todasAsEntradas = document.querySelectorAll('input[name^="dna_input"]');
            todasAsEntradas.forEach(function (entrada) {
                var valorEntrada = entrada.value.toUpperCase();
                sequenciaDna += valorEntrada;

                var caracteresPermitidos = /^[ATCG]+$/;
                if (!caracteresPermitidos.test(valorEntrada)) {
                    isValido = false;
                    return;
                }
            });

            if (!isValido) {
                document.getElementById('customPopup').classList.add('show');
                document.getElementById('customPopup').addEventListener('click', function() {
                    document.getElementById('customPopup').classList.remove('show');
                });
                return;
            }

            // Envia dados para o servidor
            try {
                var resposta = await fetch('/verificar-dna', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ sequencia_dna: sequenciaDna }) 
                });

                if (!resposta.ok) {
                    throw new Error('Erro ao processar a requisição.');
                }

                var resultado = await resposta.json();
                // Processa o resultado
                manipularResultado(resultado);
            } catch (erro) {
                document.getElementById('customPopup').addEventListener('click', function() {
                    document.getElementById('customPopup').classList.remove('show');
                });
            }
        }

        function manipularResultado(resultado) {
            var divResultado = document.getElementById('resultado');
            divResultado.innerHTML = 'Resultado: ' + JSON.stringify(resultado);
        }
    </script>
</body>
</html>
