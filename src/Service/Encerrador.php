<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Dao\Leilao as LeilaoDao;
use Alura\Leilao\Service\SendEmail;

class Encerrador
{
    private $dao;
    private $sendEmail;

    public function __construct(LeilaoDao $dao, SendEmail $sendEmail)
    {
        $this->dao = $dao;
        $this->sendEmail = $sendEmail;
    }

    public function encerra()
    {
        $leiloes = $this->dao->recuperarNaoFinalizados();

        foreach ($leiloes as $leilao) {
            if ($leilao->temMaisDeUmaSemana()) {
                try {
                    $leilao->finaliza();
                    $this->dao->atualiza($leilao);
                    $this->sendEmail->notificarTerminoLeilao($leilao);
                } catch (\DomainException $e){
                    error_log($e->getMessage());
                }
            }
        }
    }
}
