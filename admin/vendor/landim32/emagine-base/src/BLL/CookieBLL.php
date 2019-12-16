<?php
namespace Emagine\Base\BLL;

use Exception;

class CookieBLL
{
    const SITE_COOKIE_PATH = "/";
    const SITE_SECRET_KEY = "dk;l1894!851éds-fghjg4lui:è3afàzgq_f4fá.";

    /**
     * @param string $nome
     * @return string|null
     */
    public function pegar($nome) {
        list( $valor, $expiration, $hmac ) = explode( '|', $_COOKIE[$nome] );
        if ( $expiration < time() ) {
            return 0;
        }
        $key = hash_hmac( 'md5', $valor . $expiration, CookieBLL::SITE_SECRET_KEY );
        $hash = hash_hmac( 'md5', $valor . $expiration, $key );

        if ( $hmac != $hash ) {
            return null;
        }
        return $valor;
    }

    /**
     * @param string $nome
     * @param string $valor
     * @param bool $lembrar
     * @throws Exception
     */
    public function gravar( $nome, $valor, $lembrar = false ) {
        if ( $lembrar ) {
            $expiration = time() + (86400 * 30);
        }
        else {
            $expiration = time() + (86400 * 2);
        }

        $key = hash_hmac( 'md5', $valor . $expiration, CookieBLL::SITE_SECRET_KEY );
        $hash = hash_hmac( 'md5', $valor . $expiration, $key );

        $cookie = $valor . '|' . $expiration . '|' . $hash;

        $cookiedomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        if ( !setcookie( $nome, $cookie, $expiration, CookieBLL::SITE_COOKIE_PATH, $cookiedomain, false, true ) ) {
            throw new Exception("Não foi possível gravar o Cookie.");
        }
    }

    /**
     * @param string $nome
     */
    public function remover($nome) {
        $cookiedomain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie( $nome, "", time() - 1209600, CookieBLL::SITE_COOKIE_PATH, $cookiedomain, false, true );
    }
}