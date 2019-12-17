<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "eaa69cpxy2");
define("DB_NAME", "pao_no_sinal");

define("APP_NAME", "Pão no Sinal");

define("EMAIL_REMETENTE", "rodrigo@emagine.com.br");
define("NOME_REMETENTE", "Rodrigo Landim");

define("MAILJET_HOST", "in.mailjet.com");
define("MAILJET_EMAIL", "contato@imobsync.com.br");
define("MAILJET_USERNAME", "2e46011b1b85a6de9e6f8220ae5eb0ab");
define("MAILJET_PASSWORD", "a837abb0dedec02c536719161020068d");

define("MAIL_HOST", "smtp.gmail.com");
define("MAIL_EMAIL", "no-reply@emagine.com.br");
define("MAIL_USERNAME", "no-reply@emagine.com.br");
define("MAIL_PASSWORD", "eaa69cpxy2");

/*
define("DB_NAME", "emagine_loja");
define('CACHE_DIR','/Applications/XAMPP/htdocs/upload/cache');
define("UPLOAD_PATH", "/Applications/XAMPP/htdocs/upload");
*/

define('CACHE_DIR','/var/www/emagine.com.br/upload/cache');
define("UPLOAD_PATH", "/var/www/emagine.com.br/upload");

define("TEMA_PATH", "/pao-no-sinal");
//define("TEMA_PATH", "/emagine-erp");
define("MAX_PAGE_COUNT", 10);

define("MAIL_BASE_URL", "http://emagine.com.br" . TEMA_PATH);
define("SITE_URL", "http://emagine.com.br" . TEMA_PATH);

define("PEDIDO_ENVIAR_EMAIL", false);
define("LOJA_UNICA", true);
define("USUARIO_USA_FOTO", false);
define("RETIRADA_MAPEADA_TEXTO", "Pão no Sinal");
define("GOOGLE_MAPS_API", "AIzaSyBgrWD-mJvKK7DJbRFKECMxxUYXJXgHp-I");

define("PAGAMENTO_TIPO", "mundi-pagg");
define("MUNDIPAGG_CHAVE_PUBLICA", "sk_test_6e01EZigGHNVvpqP");
define("MUNDIPAGG_CHAVE_PRIVADA", "pk_test_keP7oAeFVTWomw3v");
define("MUNDIPAGG_BOLETO_CODIGO", "033");