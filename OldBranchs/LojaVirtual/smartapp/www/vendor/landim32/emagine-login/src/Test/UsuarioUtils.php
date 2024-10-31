<?php
namespace Emagine\Login\Test;

use Emagine\Login\Factory\UsuarioFactory;
use Exception;
use joshtronic\LoremIpsum;
use Emagine\Base\BLL\ValidaCpfCnpj;
use Emagine\Base\Test\TesteUtils;
use Emagine\Login\BLL\UsuarioBLL;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Login\Model\UsuarioInfo;

class UsuarioUtils
{
    /**
     * @return string
     */
    public static function gerarNome() {
        $nome = array(
            "João", "Luiz", "Ricardo", "Rodrigo", "Mariana", "Maria", "Luciana", "Luíza", "Mariana",
            "Joaquim", "Roberto", "Marcos", "José", "Marcos", "Ana", "André", "Andrea", "Luan", "William",
            "Hiram", "Heitor", "Renata"
        );
        $sobrenome = array(
            "Pessoa", "da Silva", "Carneiro", "Pinto", "Leite", "Souza", "Pacheco", "Lobo", "Landim",
            "Carneiro", "Batista", "Araújo", "Andrade", "Barros", "Curado", "Dinís", "Dias", "dos Reis"
        );
        shuffle($nome);
        shuffle($sobrenome);
        return array_values($nome)[0] . " " . array_values($sobrenome)[0];
    }

    /**
     * @return UsuarioInfo
     */
    public static function criarUsuario() {
        $lipsum = new LoremIpsum();
        $validaCpf = new ValidaCpfCnpj();

        $nome = self::gerarNome();
        $usuario = new UsuarioInfo();
        $usuario->setNome($nome);
        $usuario->setEmail(TesteUtils::gerarEmail($nome));
        $usuario->setTelefone(TesteUtils::gerarNumeroAleatorio(11));
        $usuario->setCpfCnpj($validaCpf->cpfAleatorio());
        $usuario->setSenha($lipsum->words(1));
        $usuario->setCodSituacao(UsuarioInfo::ATIVO);
        return $usuario;
    }

    /**
     * @return UsuarioEnderecoInfo
     */
    public static function criarEndereco() {
        $lipsum = new LoremIpsum();

        $endereco = new UsuarioEnderecoInfo();
        $endereco->setLogradouro($lipsum->words(5));
        $endereco->setComplemento($lipsum->words(3));
        $endereco->setNumero(TesteUtils::gerarNumeroAleatorio(3));
        $endereco->setBairro($lipsum->words(2));
        $endereco->setCidade($lipsum->words(2));
        $endereco->setUf(substr($lipsum->words(1),0, 2));
        $endereco->setCep(TesteUtils::gerarNumeroAleatorio(8));
        return $endereco;
    }

    /**
     * @throws Exception
     * @return UsuarioInfo
     */
    public static function pegarAleatorio() {
        $regraUsuario = UsuarioFactory::create();
        $usuarios = $regraUsuario->listar(UsuarioInfo::ATIVO);
        shuffle($usuarios);
        return array_values($usuarios)[0];
    }
}