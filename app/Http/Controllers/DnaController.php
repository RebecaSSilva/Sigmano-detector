<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DnaAnalise;

class DnaController extends Controller
{
    public function verificarDna(Request $request)
    {
        $sequenciaDna = strtoupper($request->input('sequencia_dna'));
    
        // Salva a sequência de DNA no banco de dados
        DnaAnalise::create(['sequencia' => $sequenciaDna]);
    
        // Verifica se é humano ou sigmano
        $resultado = $this->verificarDnaComConfig(explode("\n", $sequenciaDna));
    
        DnaAnalise::where('sequencia', $sequenciaDna)->update(['resultado' => $resultado]);
    
        return response()->json($resultado);
    }
    
    private function verificarDnaComConfig($sequenciaDna)
    {
        // Verifica se a sequência de DNA contém apenas as letras permitidas
        foreach ($sequenciaDna as $linha) {
            if (!preg_match('/^[ATCG]+$/', $linha)) {
                return 'Espécie Inválida';
            }
        }

        // Remove espaços em branco e quebras de linha das sequências
        foreach ($sequenciaDna as &$linha) {
            $linha = str_replace([' ', "\n", "\r"], '', $linha);
        }

        // Verifica sequências horizontais, verticais e diagonais
        for ($i = 0; $i < count($sequenciaDna); $i++) {
            for ($j = 0; $j < strlen($sequenciaDna[$i]); $j++) {
                if ($this->temSequenciaIgual($sequenciaDna, $i, $j, 1, 0, 4) ||  // horizontal
                    $this->temSequenciaIgual($sequenciaDna, $i, $j, 0, 1, 4) ||  // vertical
                    $this->temSequenciaIgual($sequenciaDna, $i, $j, 1, 1, 4) ||  // diagonal principal
                    $this->temSequenciaIgual($sequenciaDna, $i, $j, 1, -1, 4)) {  // diagonal secundária
                    return 'Sigmano';
                }
            }
        }

        return 'Humano';
    }

    private function temSequenciaIgual($sequenciaDna, $linha, $coluna, $deltaLinha, $deltaColuna, $comprimento)
    {
        $letra = $sequenciaDna[$linha][$coluna];
        $i = 0;
        $numLinhas = count($sequenciaDna);

        while ($linha >= 0 && $linha < $numLinhas && $coluna >= 0 && $coluna < strlen($sequenciaDna[$linha]) && $i < $comprimento) {
            if ($sequenciaDna[$linha][$coluna] !== $letra) {
                return false;
            }

            $linha += $deltaLinha;
            $coluna += $deltaColuna;
            $i++;
        }

        return $i === $comprimento;
    }

    public function exibirHistorico()
    {
        // Busca histórico de análises
        $historico = DnaAnalise::all();

        return response()->json($historico);
    }

}

