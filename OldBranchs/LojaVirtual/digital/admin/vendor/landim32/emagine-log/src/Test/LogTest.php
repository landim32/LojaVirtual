<?php
namespace Emagine\Log\Test;

use Emagine\Log\BLL\LogBLL;
use Emagine\Log\Factory\LogFactory;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\Test\UsuarioUtils;
use Exception;
use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testIncluindoNovoLog() {
        $usuario = UsuarioUtils::pegarAleatorio();
        $this->assertNotNull($usuario);

        $log = LogUtils::gerarLog($usuario);

        $regraLog = LogFactory::create();
        $id_log = $regraLog->inserir($log);
        $this->assertGreaterThanOrEqual(0, $id_log);

        $log = $regraLog->pegar($id_log);

        $this->assertNotNull($log);
        $this->assertNotEmpty($log->getNome());
        $this->assertNotEmpty($log->getTitulo());
        $this->assertNotEmpty($log->getDescricao());
    }

    /**
     * @throws Exception
     */
    public function testIncluindoUmSegundoLog() {
        $usuario = UsuarioUtils::pegarAleatorio();
        $this->assertNotNull($usuario);

        $log = LogUtils::gerarLog($usuario);

        $regraLog = LogFactory::create();
        $id_log = $regraLog->inserir($log);
        $this->assertGreaterThanOrEqual(0, $id_log);

        $log = $regraLog->pegar($id_log);

        $this->assertNotNull($log);
        //$this->assertNotEmpty($log->getNome());
        $this->assertNotEmpty($log->getTitulo());
        $this->assertNotEmpty($log->getDescricao());
    }

    /**
     * @throws Exception
     */
    public function testListandoLogs() {
        $regraLog = LogFactory::create();
        $logs = $regraLog->listar();
        $this->assertGreaterThanOrEqual(0, count($logs));
        foreach ($logs as $log) {
            $this->assertNotNull($log);
            //$this->assertNotEmpty($log->getNome());
            $this->assertNotEmpty($log->getTitulo());
            $this->assertNotEmpty($log->getDescricao());
        }
    }

    /**
     * @throws Exception
     */
    public function testExcluindoUmLog() {
        $regraLog = LogFactory::create();
        $logs = $regraLog->listar();
        $this->assertGreaterThanOrEqual(0, count($logs));

        shuffle($logs);
        /** @var LogInfo $log */
        $log = array_values($logs)[0];

        $id_log = $log->getId();
        $regraLog->excluir($id_log);

        $log = $regraLog->pegar($id_log);
        $this->assertNull($log);
    }
}