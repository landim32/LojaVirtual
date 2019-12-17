<?php

define("EM_DESENVOLVIMENTO", true);

define("DB_HOST", "imarket.mysql.dbaas.com.br");
define("DB_USER", "imarket");
define("DB_PASS", "senha123");
define("DB_NAME", "imarket");
/*
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "eaa69cpxy2");
define("DB_NAME", "digital");
*/

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

define("APP_NAME", "Digital Supermercado");
//define('CACHE_DIR','/var/www/emagine.com.br/upload/cache');
//define("UPLOAD_PATH", "/var/www/emagine.com.br/upload");
//define("TEMA_PATH", "/digital");
//define("TEMA_PATH", "/emagine-loja");
define('CACHE_DIR','/home/imarketsupermercados/cache');
define("UPLOAD_PATH", "/home/imarketsupermercados/upload");
define("TEMA_PATH", "");

//define("MAIL_BASE_URL", "http://emagine.com.br" . TEMA_PATH);
//define("SITE_URL", "http://emagine.com.br" . TEMA_PATH);
define("MAIL_BASE_URL", "http://imarketsupermercados.com.br" . TEMA_PATH);
define("SITE_URL", "http://imarketsupermercados.com.br" . TEMA_PATH);

define("MAX_PAGE_COUNT", 16);

define("USA_LOJA_ROUTE", false);
define("USA_LOJA_FRETE_ROUTE", false);
define("USA_PRODUTO_ROUTE", false);
define("USA_PRODUTO_CATEGORIA_ROUTE", false);

define("LOJA_BUSCA_POR_CEP", true);
define("LOJA_DEBITO_ONLINE", false);

//Usuários
define("USUARIO_VALIDA_EMAIL", false);
define("USUARIO_ENDERECO_OBRIGATORIO", false);
define("USUARIO_TELEFONE_OBRIGATORIO", false);
define("USUARIO_CELULAR_OBRIGATORIO", false);

define("LOGIN_SIMPLES", false);
define("CADASTRO_SIMPLES", false);
define("UF_LIVRE", false);
define("CIDADE_LIVRE", false);
define("BAIRRO_LIVRE", false);

//Usuários
define("USUARIO_USA_FOTO", false);
define("USUARIO_REQUER_VALIDACAO", false);
define("USUARIO_CPF_CNPJ_OBRIGATORIO", false);

//Pedido
define("PEDIDO_ENVIAR_EMAIL", false);