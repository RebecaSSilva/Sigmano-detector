<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\DnaController;

class DnaControllerTest extends TestCase
{
    public function testVerificarDna()
    {
        $controller = new DnaController();

        // Simula uma requisição com uma sequência válida
        $request = $this->createMock(\Illuminate\Http\Request::class);
        $request->expects($this->once())->method('input')->with('sequencia_dna')->willReturn("ATCG\nATCG\nATCG\nATCG\n");

        $this->assertEquals('Humano', $controller->verificarDna($request)->getOriginalContent());
    }

    public function testExibirHistorico()
    {
        $controller = new DnaController();

        $this->assertEquals([], $controller->exibirHistorico()->getOriginalContent());
    }

}
