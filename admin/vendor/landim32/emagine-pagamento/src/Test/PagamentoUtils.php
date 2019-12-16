<?php
namespace Emagine\Pagamento\Test;

use stdClass;
use Exception;
use joshtronic\LoremIpsum;
use PHPUnit\Framework\TestCase;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\Test\TesteUtils;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Login\Test\UsuarioUtils;
use Emagine\Pagamento\Model\PagamentoInfo;
use Emagine\Pagamento\Model\PagamentoItemInfo;

class PagamentoUtils
{
    /**
     * @return stdClass[]
     */
    public static function listarCartao() {
        $content = file_get_contents(dirname(__DIR__) . "/JSON/cartao-teste.json");
        return json_decode($content);
    }


    /**
     * @param UsuarioInfo $usuario
     * @param double $preco
     * @return PagamentoInfo
     * @throws Exception
     */
    public static function gerarPagamento(UsuarioInfo $usuario, $preco = 0.0) {
        $lipsum = new LoremIpsum();
        $validaCpf = new ValidaCpfCnpj();

        $pagamento = new PagamentoInfo();
        $pagamento->setIdUsuario($usuario->getId());
        $pagamento->setCpf($validaCpf->cpfAleatorio());
        $pagamento->setDataVencimento(date("Y-m-d", time() + (60 * 60 * 24 * 3)));
        $pagamento->setObservacao($lipsum->words(15));
        $pagamento->setCodSituacao(PagamentoInfo::SITUACAO_ABERTO);

        if ($preco > 0) {
            $palavras = $lipsum->wordsArray(4);
            $item = new PagamentoItemInfo();
            $item->setDescricao(implode(" ", $palavras));
            $item->setValor($preco);
            $item->setQuantidade(1);
            $pagamento->adicionarItem($item);
        }
        else {
            $quantidade = rand(1, 10);
            for ($i = 1; $i <= $quantidade; $i++) {
                $palavras = $lipsum->wordsArray(4);
                $valor = rand(100, 20000) / 100;
                $item = new PagamentoItemInfo();
                $item->setDescricao(implode(" ", $palavras));
                $item->setValor($valor);
                $item->setQuantidade(rand(1, 8));
                $pagamento->adicionarItem($item);
            }
        }

        return $pagamento;
    }

    /**
     * @throws Exception
     * @param TestCase $testCase
     * @param double $preco
     * @return PagamentoInfo
     */
    public static function gerarPagamentoCredito(TestCase $testCase, $preco = 0.0) {

        $cartoes = PagamentoUtils::listarCartao();
        shuffle($cartoes);
        /** @var stdClass $cartao */
        $cartao = array_values($cartoes)[0];

        $usuario = UsuarioUtils::pegarAleatorio();
        $testCase->assertNotNull($usuario);

        $pagamento = self::gerarPagamento($usuario, $preco);
        $pagamento->setCodTipo(PagamentoInfo::CREDITO_ONLINE);
        //$pagamento->setNumeroCartao(TesteUtils::gerarNumeroAleatorio(16));
        //$pagamento->setCodBandeira(PagamentoInfo::VISA);
        $pagamento->setNomeCartao($usuario->getNome());
        $pagamento->setDataExpiracao("2020-01-01");
        //$pagamento->setCVV(TesteUtils::gerarNumeroAleatorio(3));

        switch (strtolower($cartao->bandeira)) {
            case "visa":
                $pagamento->setCodBandeira(PagamentoInfo::VISA);
                break;
            case "mastercard":
                $pagamento->setCodBandeira(PagamentoInfo::MASTERCARD);
                break;
            case "diners":
                $pagamento->setCodBandeira(PagamentoInfo::DINERS);
                break;
            case "amex":
                $pagamento->setCodBandeira(PagamentoInfo::AMEX);
                break;
            case "hipercard":
                $pagamento->setCodBandeira(PagamentoInfo::HIPERCARD);
                break;
            case "elo":
                $pagamento->setCodBandeira(PagamentoInfo::ELO);
                break;
        }
        $pagamento->setNumeroCartao($cartao->numero);
        $pagamento->setCVV($cartao->cvv);

        return $pagamento;
    }
}