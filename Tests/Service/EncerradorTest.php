<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as leilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class leilaoDaoMock extends leilaoDao 
{   
    private $leiloes = [];

    public function salva(Leilao $leilao): void
    {
        $this->leiloes[] = $leilao;
    }

    public function recuperarNaoFinalizados(): array
    {
        return array_filter($this->leiloes, function (Leilao $leilao) {
            return !$leilao->estaFinalizado();
        });
    }

    public function recuperarFinalizados(): array
    {
        return array_filter($this->leiloes, function (Leilao $leilao) {
            return $leilao->estaFinalizado();
        });
    }

    public function atualiza(Leilao $leilao)
    {

    }
}

class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerFinalizados ()
    {
        $fiat147 = new Leilao('Fiat 147 0Km', new DateTimeImmutable('8 days ago'));
        $variante = new Leilao('Variante', new DateTimeImmutable('10 days ago'));

        $leilaoDao = new leilaoDaoMock();
        $leilaoDao->salva($fiat147);
        $leilaoDao->salva($variante);

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $leiloes = $leilaoDao->recuperarFinalizados();
        self::assertCount(2, $leiloes);
        self::assertEquals('Fiat 147 0Km', $leiloes[0]->recuperarDescricao());
        self::assertEquals('Variante', $leiloes[1]->recuperarDescricao());
    }
}
