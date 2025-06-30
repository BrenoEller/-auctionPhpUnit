<?php

namespace Alura\Leilao\Tests\Service;

use Alura\Leilao\Dao\Leilao as leilaoDao;
use Alura\Leilao\Model\Leilao;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\SendEmail;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
class EncerradorTest extends TestCase
{
    private $encerrador;
    private $leilaoFiat147;
    private $leilaoVariante;
    
    /** @var \Alura\Leilao\Service\SendEmail&\PHPUnit\Framework\MockObject\MockObject */
    private $sendEmail;

    protected function setUp(): void
    {
        $this->leilaoFiat147 = new Leilao('Fiat 147 0Km', new DateTimeImmutable('8 days ago'));
        $this->leilaoVariante = new Leilao('Variante', new DateTimeImmutable('10 days ago'));

        /** @var \Alura\Leilao\Dao\Leilao&\PHPUnit\Framework\MockObject\MockObject */
        $leilaoDao = $this->getMockBuilder(LeilaoDao::class)
        ->disableOriginalConstructor()
        ->getMock();

        $leilaoDao->method('recuperarNaoFinalizados')->willReturn([$this->leilaoFiat147, $this->leilaoVariante]);
        $leilaoDao->expects($this->exactly(2))->method('atualiza');

        $this->sendEmail = $this->getMockBuilder(SendEmail::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->encerrador = new Encerrador($leilaoDao, $this->sendEmail);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerFinalizados ()
    {
        $this->encerrador->encerra();

        $leiloes = [$this->leilaoFiat147, $this->leilaoVariante];
        self::assertCount(2, $leiloes);
        self::assertTrue($this->leilaoFiat147->estaFinalizado());
        self::assertTrue($this->leilaoVariante->estaFinalizado());
    }

    public function testEmailDeveSerEnviadoMesmoComExcessoes()
    {
        $e = new DomainException('Erro ao enviar email');

        $this->sendEmail->expects($this->exactly(2))
        ->method('notificarTerminoLeilao')->willThrowException($e);
        $this->encerrador->encerra();
    }

    public function testSoDeveEnviarLeilaoAposFinalizado()
    {
        $this->sendEmail->expects($this->exactly(2))->method('notificarTerminoLeilao')->willReturnCallback(function (Leilao $leilao) {
            static::assertTrue($leilao->estaFinalizado());
        });

        $this->encerrador->encerra();
    }
}
