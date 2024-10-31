<?php
namespace Emagine\Base\Test;


class TesteUtils
{
    /**
     * @return string
     */
    public static function gerarNome() {
        $primeiroNome = array(
            "Rodrigo", "João", "Maria", "Renata", "Pedro", "Luiz", "Augusto", "Eduardo", "José", "Luciana",
            "Mariana", "Daniel", "Luíza", "Camila", "Caroline", "Joana", "Miguel", "ALice", "Gabriel", "Julia",
            "Laura", "Heitor", "Hiram", "Nicolas", "Felipe", "Samuel", "Antônio", "Thiago"
        );
        $sobrenomes = array(
            "Silva", "Santos", "Oliveira", "Souza", "Rodrigues", "Ferreira", "Alves", "Pereira", "Lima", "Gomes",
            "Almeida", "Andrade", "Barbosa", "Barros", "Batista", "Borges", "Campos", "Carvalho"
        );
        shuffle($primeiroNome);
        $nome = array_values($primeiroNome)[0];
        if (rand(0, 1) == 1) {
            shuffle($primeiroNome);
            $nome .= " " . array_values($primeiroNome)[0];
        }
        shuffle($sobrenomes);
        $nome .= " " . array_values($sobrenomes)[0];
        return $nome;
    }

    /**
     * @param string $nome
     * @return string
     */
    public static function gerarEmail($nome) {
        $dominio = array("yahoo.com.br", "ig.com.br", "gmail.com", "hotmail.com");
        $email = trim(strtolower(sanitize_slug($nome)));
        $email = str_replace(" ", ".", $email);
        $email = str_replace("-", ".", $email);
        $email .= "@" . array_values($dominio)[0];
        return $email;
    }

    /**
     * @param int $tamanho
     * @return string
     */
    public static function gerarNumeroAleatorio($tamanho) {
        $telefone = "";
        for ($i = 0; $i < $tamanho; $i++) {
            $telefone .= rand(0, 9) . "";
        }
        return $telefone;
    }
}