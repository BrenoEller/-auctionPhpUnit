<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as leilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
class EncerradorTest extends TestCase
{
    public function testLeiloesComMaisDeUmaSemanaDevemSerFinalizados ()
    {
        $fiat147 = new Leilao('Fiat 147 0Km', new DateTimeImmutable('8 days ago'));
        $variante = new Leilao('Variante', new DateTimeImmutable('10 days ago'));

        $leilaoDao = $this->getMockBuilder(LeilaoDao::class)
        ->disableOriginalConstructor()
        ->getMock();

        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$fiat147, $variante]);
        $leilaoDao->expects($this->exactly(2))->method('atualiza');

        $encerrador = new Encerrador($leilaoDao);
        $encerrador->encerra();

        $leiloes = [$fiat147, $variante];
        self::assertCount(2, $leiloes);
        self::assertTrue($fiat147->estaFinalizado());
        self::assertTrue($variante->estaFinalizado());
    }
}
