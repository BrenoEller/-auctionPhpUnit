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

        $this->assertEquals(2000, $menorValor);

    }

    public function testAvaliadorDeveBuscarOsTresMaioresValores() 
    {
        $leilao = new Leilao('Audi');

        $joao = new Usuario('João');
        $maria = new Usuario('Maria');
        $ana = new Usuario('Ana');
        $jorge = new Usuario('Jorge');

        $leilao->recebeLance(new Lance($joao, 1500));
        $leilao->recebeLance(new Lance($maria, 500));
        $leilao->recebeLance(new Lance($ana, 2500));
        $leilao->recebeLance(new Lance($jorge, 1200));

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $maioresLances = $leiloeiro->getMaioresLances();

        $this->assertCount(3, $maioresLances);
        $this->assertEquals(2500, $maioresLances[0]->getValor());
        $this->assertEquals(1500, $maioresLances[1]->getValor());
        $this->assertEquals(1200, $maioresLances[2]->getValor());
    }
}