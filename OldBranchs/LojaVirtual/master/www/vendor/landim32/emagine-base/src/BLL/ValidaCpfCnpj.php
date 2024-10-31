<?php

namespace Emagine\Base\BLL;

/**
 * ValidaCpfCnpj valida e formata CPF e CNPJ
 *
 * Exemplo de uso:
 * $cpf_cnpj  = new ValidaCpfCnpj('71569042000196');
 * $formatado = $cpf_cnpj->formata(); // 71.569.042/0001-96
 * $valida    = $cpf_cnpj->valida(); // True -> Válido
 *
 * @package  valida-cpf-cnpj
 * @author   Luiz Otávio Miranda <contato@todoespacoonline.com/w>
 * @version  v1.4
 * @access   public
 * @see      http://www.todoespacoonline.com/w/
 */
class ValidaCpfCnpj
{
    private $valor;

    /**
     * Configura o valor (Construtor)
     * Remove caracteres inválidos do CPF ou CNPJ
     * @param string $valor - O CPF ou CNPJ
     */
    function __construct ( $valor = null ) {
        // Deixa apenas números no valor
        $this->valor = preg_replace( '/[^0-9]/', '', $valor );

        // Garante que o valor é uma string
        $this->valor = (string)$this->valor;
    }

    /**
     * Verifica se é CPF ou CNPJ
     *
     * Se for CPF tem 11 caracteres, CNPJ tem 14
     *
     * @access protected
     * @return string CPF, CNPJ ou false
     */
    protected function verificarCpfCnpj () {
        // Verifica CPF
        if ( strlen( $this->valor ) === 11 ) {
            return 'CPF';
        }
        // Verifica CNPJ
        elseif ( strlen( $this->valor ) === 14 ) {
            return 'CNPJ';
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Verifica se todos os números são iguais
     * *
     * @access protected
     * @return bool true para todos iguais, false para números que podem ser válidos
     */
    protected function verificarIgualdade() {
        // Todos os caracteres em um array
        $caracteres = str_split($this->valor );

        // Considera que todos os números são iguais
        $todos_iguais = true;

        // Primeiro caractere
        $last_val = $caracteres[0];

        // Verifica todos os caracteres para detectar diferença
        foreach( $caracteres as $val ) {

            // Se o último valor for diferente do anterior, já temos
            // um número diferente no CPF ou CNPJ
            if ( $last_val != $val ) {
                $todos_iguais = false;
            }

            // Grava o último número checado
            $last_val = $val;
        }

        // Retorna true para todos os números iguais
        // ou falso para todos os números diferentes
        return $todos_iguais;
    }

    /**
     * Multiplica dígitos vezes posições
     * @access protected
     * @param  string $digitos Os digitos desejados
     * @param  int $posicoes A posição que vai iniciar a regressão
     * @param  int $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int Os dígitos enviados concatenados com o último dígito
     */
    protected function calcularDigitoPosicao($digitos, $posicoes = 10, $soma_digitos = 0 ) {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );

            // Subtrai 1 da posição
            $posicoes--;

            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ( $posicoes < 2 ) {
                // Retorno a posição para 9
                $posicoes = 9;
            }
        }

        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;

        // Verifica se $soma_digitos é menor que 2
        if ( $soma_digitos < 2 ) {
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        } else {
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        }

        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        $cpf = $digitos . $soma_digitos;

        // Retorna
        return $cpf;
    }

    /**
     * Valida CPF
     *
     * @author Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     * @return bool True para CPF correto - False para CPF incorreto
     */
    public function validarCpf() {
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($this->valor, 0, 9);

        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = $this->calcularDigitoPosicao( $digitos );

        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = $this->calcularDigitoPosicao( $novo_cpf, 11 );

        // Verifica se todos os números são iguais
        if ( $this->verificarIgualdade() ) {
            return false;
        }

        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ( $novo_cpf === $this->valor ) {
            // CPF válido
            return true;
        } else {
            // CPF inválido
            return false;
        }
    }

    /**
     * Valida CNPJ
     *
     * @author Luiz Otávio Miranda <contato@todoespacoonline.com/w>
     * @access protected
     * @return bool true para CNPJ correto
     */
    public function validarCnpj() {
        // O valor original
        $cnpj_original = $this->valor;

        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr( $this->valor, 0, 12 );

        // Faz o primeiro cálculo
        $primeiro_calculo = $this->calcularDigitoPosicao( $primeiros_numeros_cnpj, 5 );

        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = $this->calcularDigitoPosicao( $primeiro_calculo, 6 );

        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;

        // Verifica se todos os números são iguais
        if ( $this->verificarIgualdade() ) {
            return false;
        }

        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ( $cnpj === $cnpj_original ) {
            return true;
        }
        return false;
    }

    /**
     * Valida
     * Valida o CPF ou CNPJ
     * @access public
     * @return bool True para válido, false para inválido
     */
    public function validar() {
        // Valida CPF
        if ( $this->verificarCpfCnpj() === 'CPF' ) {
            // Retorna true para cpf válido
            return $this->validarCpf();
        }
        // Valida CNPJ
        elseif ( $this->verificarCpfCnpj() === 'CNPJ' ) {
            // Retorna true para CNPJ válido
            return $this->validarCnpj();
        }
        // Não retorna nada
        else {
            return false;
        }
    }

    /**
     * Formata CPF ou CNPJ
     *
     * @access public
     * @return string CPF ou CNPJ formatado
     */
    public function formatar() {
        // O valor formatado
        $formatado = false;

        // Valida CPF
        if ( $this->verificarCpfCnpj() === 'CPF' ) {
            // Verifica se o CPF é válido
            if ( $this->validarCpf() ) {
                // Formata o CPF ###.###.###-##
                $formatado  = substr( $this->valor, 0, 3 ) . '.';
                $formatado .= substr( $this->valor, 3, 3 ) . '.';
                $formatado .= substr( $this->valor, 6, 3 ) . '-';
                $formatado .= substr( $this->valor, 9, 2 ) . '';
            }
        }
        // Valida CNPJ
        elseif ( $this->verificarCpfCnpj() === 'CNPJ' ) {
            // Verifica se o CPF é válido
            if ( $this->validarCnpj() ) {
                // Formata o CNPJ ##.###.###/####-##
                $formatado  = substr( $this->valor,  0,  2 ) . '.';
                $formatado .= substr( $this->valor,  2,  3 ) . '.';
                $formatado .= substr( $this->valor,  5,  3 ) . '/';
                $formatado .= substr( $this->valor,  8,  4 ) . '-';
                $formatado .= substr( $this->valor, 12, 14 ) . '';
            }
        }

        // Retorna o valor
        return $formatado;
    }

    /**
     * @param double $dividendo
     * @param double $divisor
     * @return double
     */
    protected function mod($dividendo, $divisor) {
        return round($dividendo - (floor($dividendo / $divisor) * $divisor));
    }

    /**
     * Método para gerar CNPJ válido, com máscara ou não
     * @example cnpjRandom(0) para retornar CNPJ sem máscar
     * @param int $mascara
     * @return string
     */
    public function cnpjAleatorio($mascara = 1) {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = 0;
        $n10 = 0;
        $n11 = 0;
        $n12 = 1;
        $d1 = $n12 * 2 + $n11 * 3 + $n10 * 4 + $n9 * 5 + $n8 * 6 + $n7 * 7 + $n6 * 8 + $n5 * 9 + $n4 * 2 + $n3 * 3 + $n2 * 4 + $n1 * 5;
        $d1 = 11 - ($this->mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n12 * 3 + $n11 * 4 + $n10 * 5 + $n9 * 6 + $n8 * 7 + $n7 * 8 + $n6 * 9 + $n5 * 2 + $n4 * 3 + $n3 * 4 + $n2 * 5 + $n1 * 6;
        $d2 = 11 - ($this->mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        //$retorno = '';
        if ($mascara == 1) {
            $retorno = '' . $n1 . $n2 . "." . $n3 . $n4 . $n5 . "." . $n6 . $n7 . $n8 . "/" . $n9 . $n10 . $n11 . $n12 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $n10 . $n11 . $n12 . $d1 . $d2;
        }
        return $retorno;
    }

    /**
     * Método para gerar CPF válido, com máscara ou não
     * @example cpfRandom(0) para retornar CPF sem máscar
     * @param int $mascara
     * @return string
     */
    public function cpfAleatorio($mascara = 1) {
        $n1 = rand(0, 9);
        $n2 = rand(0, 9);
        $n3 = rand(0, 9);
        $n4 = rand(0, 9);
        $n5 = rand(0, 9);
        $n6 = rand(0, 9);
        $n7 = rand(0, 9);
        $n8 = rand(0, 9);
        $n9 = rand(0, 9);
        $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
        $d1 = 11 - ($this->mod($d1, 11) );
        if ($d1 >= 10) {
            $d1 = 0;
        }
        $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
        $d2 = 11 - ($this->mod($d2, 11) );
        if ($d2 >= 10) {
            $d2 = 0;
        }
        //$retorno = '';
        if ($mascara == 1) {
            $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
        } else {
            $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
        }
        return $retorno;
    }
}