<?php

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "eaa69cpxy2");
//define("DB_NAME", "smartapp");
define("DB_NAME", "emagine_loja");

define("APP_NAME", "Emagine Loja");

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

define('CACHE_DIR','/var/www/emagine.com.br/upload-loja/cache');
define("UPLOAD_PATH", "/var/www/emagine.com.br/upload-loja");

//define("TEMA_PATH", "/admin");
define("TEMA_PATH", "/loja-demo/admin");
//define("TEMA_PATH", "/emagine-erp");
define("MAX_PAGE_COUNT", 10);

define("PAGAMENTO_DEBUG", true);
define("PAGAMENTO_TIPO", "cielo");
define("CIELO_SANDBOX", true);
define("CIELO_MERCHANT_ID", "9a933857-1e6d-414f-8b0c-ad52d9e2becf");
define("CIELO_MERCHANT_KEY", "LGWFHHFFNVRJIJJJBTKFAKBIRADPCWKWGNHQNOZF");
/*
define("PAGAMENTO_TIPO", "iugu");
define("IUGU_ACCOUNT_ID", "206E464E14CD414F8C813968C53DF11B");
define("IUGU_TOKEN", "097af50209eece154f3fef5dee8d9ce7");
define("IUGU_EMAIL", "diegocarvalho.advogado@gmail.com");
*/

/*
define("MAIL_BASE_URL", "http://smartappcompras.com.br" . TEMA_PATH);
define("SITE_URL", "http://smartappcompras.com.br" . TEMA_PATH);
*/
define("MAIL_BASE_URL", "http://emagine.com.br" . TEMA_PATH);
define("SITE_URL", "http://emagine.com.br" . TEMA_PATH);

define("USUARIO_USA_FOTO", false);
define("USUARIO_VALIDA_EMAIL", false);
define("USUARIO_REQUER_VALIDACAO", false);
define("USUARIO_ENDERECO_OBRIGATORIO", false);
define("USUARIO_TELEFONE_OBRIGATORIO", false);
define("USUARIO_CPF_CNPJ_OBRIGATORIO", false);
define("USUARIO_CELULAR_OBRIGATORIO", false);

define("USA_PRODUTO_API", false);
define("PEDIDO_ENVIAR_EMAIL", true);
define("LOJA_UNICA", false);
define("LOJA_RETIRADA_MAPEADA", false);
define("LOJA_DEBITO_ONLINE", false);
define("GOOGLE_MAPS_API", "AIzaSyBgrWD-mJvKK7DJbRFKECMxxUYXJXgHp-I");

define("BLL_LOG", "Emagine\\Produto\\BLL\\ProdutoLogBLL");
define("BLL_USUARIO", "Emagine\\Produto\\BLL\\ProdutoUsuarioBLL");
define("BLL_USUARIO_PERFIL", "Emagine\\Produto\\BLL\\LojaPerfilBLL");

define("MENSAGEM_EXIBE_AVISO", true);
define("USUARIO_SOCIAL_USA_ROUTE", false);