<?php

namespace Alura\Leilao\Service;

use Alura\Leilao\Model\Leilao;
use DomainException;

class SendEmail
{
    public function notificarTerminoLeilao(Leilao $leilao): void
    {
        $sucesso = mail('usuario@gmail.com', 'Leilão finalizado', 'O leilão para' . $leilao->recuperarDescricao() . 'está finalizado.');

        if(!$sucesso) {
            throw new \DomainException('Erro ao enviar email');
        }
    }
}
