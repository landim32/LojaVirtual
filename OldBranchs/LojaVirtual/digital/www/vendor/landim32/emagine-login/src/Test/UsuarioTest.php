<?php
namespace Emagine\Login\Test;

use Emagine\Login\Factory\UsuarioFactory;
use Exception;
use PHPUnit\Framework\TestCase;
use Emagine\Login\BLL\UsuarioBLL;

class UsuarioTest extends TestCase {

    /**
     * @throws Exception
     */
    public function testCriandoNovoUsuarioComEndereco() {
        $regraUsuario = UsuarioFactory::create();
        $usuario = UsuarioUtils::criarUsuario();
        $usuario->adicionarEndereco(UsuarioUtils::criarEndereco());

        $id_usuario = $regraUsuario->inserir($usuario);
        $this->assertGreaterThan(0, $id_usuario);
    }

}