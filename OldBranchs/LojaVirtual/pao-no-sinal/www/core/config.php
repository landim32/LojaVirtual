<?php

define("EM_DESENVOLVIMENTO", true);

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "eaa69cpxy2");

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

define("APP_NAME", "Pão no Sinal");
define("DB_NAME", "paonosinal");
define('CACHE_DIR','/var/www/emagine.com.br/upload/cache');
define("UPLOAD_PATH", "/var/www/emagine.com.br/upload");
//define("TEMA_PATH", "/emagine-loja");
define("TEMA_PATH", "/pao-no-sinal");
define("MAIL_BASE_URL", "http://emagine.com.br" . TEMA_PATH);
define("SITE_URL", "http://emagine.com.br" . TEMA_PATH);

define("MAX_PAGE_COUNT", 16);

define("GOOGLE_MAPS_API", "AIzaSyBgrWD-mJvKK7DJbRFKECMxxUYXJXgHp-I");

//Usuários
define("USUARIO_VALIDA_EMAIL", false);
define("USUARIO_ENDERECO_OBRIGATORIO", true);
define("USUARIO_TELEFONE_OBRIGATORIO", true);
define("USUARIO_CELULAR_OBRIGATORIO", false);
define("LOGIN_SIMPLES", false);
define("CADASTRO_SIMPLES", false);
define("UF_LIVRE", false);
define("CIDADE_LIVRE", false);
define("BAIRRO_LIVRE", false);