<?php
namespace Emagine\Endereco\Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Emagine\Endereco\BLL\CepBLL;
use Emagine\Endereco\Model\UfInfo;
use Emagine\Endereco\Model\CidadeInfo;
use Emagine\Endereco\Model\BairroInfo;
use Emagine\Endereco\Model\EnderecoInfo;

class EnderecoUtils
{
    /**
     * @param TestCase $testCase
     * @return UfInfo
     * @throws Exception
     */
    public static function pegarUf(TestCase $testCase) {
        $regraCep = new CepBLL();
        $estados = $regraCep->listarUf();
        $testCase->assertGreaterThan(0, count($estados), "Nenhum usuário ativo disponível.");
        shuffle($estados);
        /** @var UfInfo $uf */
        $uf = array_values($estados)[0];
        $testCase->assertNotNull($uf, "Nenhum estado disponível.");
        return $uf;
    }

    /**
     * @throws Exception
     * @param TestCase $testCase
     * @param string $uf
     * @return CidadeInfo
     */
    public static function pegarCidade(TestCase $testCase, $uf) {
        $regraCep = new CepBLL();
        $cidade = $regraCep->pegarCidadeAleatoria($uf);
        $testCase->assertNotNull($cidade, "Nenhuma cidade disponível.");
        return $cidade;
    }

    /**
     * @param TestCase $testCase
     * @param CidadeInfo $cidade
     * @return BairroInfo
     * @throws Exception
     */
    public static function pegarBairro(TestCase $testCase, CidadeInfo $cidade) {
        $regraCep = new CepBLL();
        $bairros = $regraCep->buscarBairroPorIdCidade("", $cidade->getId());
        $mensagem = sprintf("Nenhum bairro na cidade %s.", $cidade->getNome());
        $testCase->assertGreaterThan(0, count($bairros), $mensagem);
        shuffle($bairros);
        /** @var BairroInfo $bairro */
        $bairro = array_values($bairros)[0];
        $testCase->assertNotNull($bairro, "Nenhum bairro disponível.");
        return $bairro;
    }

    /**
     * @param TestCase $testCase
     * @param BairroInfo $bairro
     * @return EnderecoInfo
     * @throws Exception
     */
    public static function pegarLogradouro(TestCase $testCase, BairroInfo $bairro) {
        $regraCep = new CepBLL();
        $logradouros = $regraCep->buscarLogradouroPorIdBairro("", $bairro->getId());
        $mensagem = sprintf("Nenhum logradouro para o bairro %s.", $bairro->getNome());
        $testCase->assertGreaterThan(0, count($logradouros), $mensagem);
        shuffle($logradouros);
        /** @var EnderecoInfo $logradouro */
        $logradouro = array_values($logradouros)[0];
        $testCase->assertNotNull($logradouro, "Nenhum logradouro disponível.");
        return $logradouro;
    }

    /**
     * @param string $uf
     * @return EnderecoInfo
     * @throws Exception
     */
    public static function pegarEnderecoAleatorio($uf) {
        $regraCep = new CepBLL();
        return $regraCep->pegarEnderecoAleatorio($uf);
    }

    /**
     * @deprecated Use o pegarEnderecoAleatorioOld
     * @param TestCase $testCase
     * @param UfInfo $uf
     * @param CidadeInfo $cidade
     * @return EnderecoInfo
     * @throws Exception
     */
    public static function pegarEnderecoAleatorioOld(TestCase $testCase, UfInfo $uf, CidadeInfo &$cidade) {
        $regraCep = new CepBLL();

        $tentativas = 0;
        $endereco = null;
        do {
            $tentativas++;

            $bairros = $regraCep->buscarBairroPorIdCidade("", $cidade->getId());
            if (count($bairros) == 0) {
                $cidade = self::pegarCidade($testCase, $uf->getUf());
                continue;
            }
            shuffle($bairros);
            /** @var BairroInfo $bairro */
            $bairro = array_values($bairros)[0];

            $logradouros = $regraCep->buscarLogradouroPorIdBairro("", $bairro->getId());
            if (count($logradouros) == 0) {
                continue;
            }
            shuffle($logradouros);
            /** @var EnderecoInfo $logradouro */
            $endereco = array_values($logradouros)[0];

        } while (is_null($endereco) && $tentativas < 50);

        $testCase->assertNotNull($endereco, "Nenhum endereço encontrado após 50 tentativas.");

        return $endereco;
    }
}