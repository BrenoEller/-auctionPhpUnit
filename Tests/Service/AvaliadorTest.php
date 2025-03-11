<?php

namespace Alura\Leilao\Tests\Service;

use PHPUnit\Framework\TestCase;
use Alura\Leilao\Model\Lance;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Model\Usuario;
use Alura\Leilao\Service\Avaliador;

class AvaliadorTest extends TestCase
{
    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat');

        $maria = new Usuario('Maria');
        $joao = new Usuario('João');
        
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $maiorValor = $leiloeiro->getMaiorValor();
        
        $maiorValor = $leiloeiro->getMaiorValor();
        
        $this->assertEquals(2500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarOMaiorValorDeLancesEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0km');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));


        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $maiorValor = $leiloeiro->getMaiorValor();

        $this->assertEquals(2500, $maiorValor);
    }

    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemDecrescente()
    {
        $leilao = new Leilao('Fiat 147 0km');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));


        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        $this->assertEquals(2000, $menorValor);
    }

    public function testAvaliadorDeveEncontrarOMenorValorDeLancesEmOrdemCrescente()
    {
        $leilao = new Leilao('Fiat 147 0km');

        $maria = new Usuario('Maria');
        $joao = new Usuario('Joao');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        
        $leiloeiro = new Avaliador();

        $leiloeiro->avalia($leilao);

        $menorValor = $leiloeiro->getMenorValor();

        self::assertEquals(2000, $menorValor);

    }
}