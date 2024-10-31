Emagine\Base
=========

Bibliteca de base para o Framework Emagine. O núcleo do framework gira em torno da class [EmagineApp](wiki/Emagine\Base-Core-EmagineApp.md).

## Usando a classe EmagineApp

A classe [EmagineApp](wiki/Emagine\Base-Core-EmagineApp.md) e uma classe herdada do \Slim\App. A forma básica de uso é praticamente a mesma.

## Trabalhando com Menus

Trabalhando com menus. Segue um exemplo onde se pegar o menu principal e se inclui alguns submenus:

```php
<?php
$app = EmagineApp::getApp();
$mainMenu = $app->getMenu("main");
if (!is_null($mainMenu)) {
    $mainMenu->addMenu(new SlimMenu("Home", "/", "fa fa-house"));
    $cadastroMenu = $mainMenu->addMenu(new SlimMenu("Cadastro", "#", "fa fa-cog"));
    $cadastroMenu->addSubMenu(new SlimMenu("Clientes", "/clientes", "fa fa-user-circle"));
}
```

Segue abaixo a estrutura das classes relacionadas com menus:

* [SlimMainMenu](wiki/Emagine\Base-Controls-SlimMainMenu.md)
* [SlimMenu](wiki/Emagine\Base-Controls-SlimMenu.md)
* [SlimMenuBase](wiki/Emagine\Base-Controls-SlimMenuBase.md)
* [SlimMenuSeparator](wiki/Emagine\Base-Controls-SlimMenuSeparator.md)

### Montando a estrutura do projeto

Segue abaixo a estrutura que você deve quer no projeto que irá criar. 

```
/Raiz
|-- .htaccess
|-- index.php
+-- /less
|   +-- main.less
+-- /core
|   +-- config.php
|   +-- routes.php
|   +-- dependencies.php
+-- /css
|   +-- *.css
+-- /js
|   +-- *.js
+-- /templates
|   +-- header.php
|   +-- footer.php
|   +-- home.php
|   +-- menu-principal.php

```

### Estrutura dos diretórios dos módulos

A estrutura abaixo serve para os submodulos criados com base no Emagine\Base.

```
/src
+-- /Model
|   +-- *Info.php
+-- /BLL
|   +-- *BLL.php
+-- /DAL
|   +-- *DAL.php
+-- /Core
|   +-- routes.php
|   +-- dependencies.php
|   +-- api.php
|   +-- routes-*.php
|   +-- dep-*.php
|   +-- api-*.php
+-- /css
|   +-- *.css
+-- /js
|   +-- *.js
+-- /templates
|   +-- *.php
|   +-- *-modal.php
```

### index.php

```php
<?php
require __DIR__ . "/core/config.php";
$loader = require __DIR__ . "/vendor/autoload.php";
// Inclua aqui qualquer modulo extra que deseja usando
$loader->addPsr4('Emagine\Base\\', __DIR__ . '/src');

use Emagine\Base\Core\EmagineApp;

$config = EmagineApp::getConfig(__DIR__);
$app = new EmagineApp($config);
$app->setDatatableEnabled(true);
EmagineApp::setApp($app);
// ATENÇÃO: Inclua aqui todos os módulos EMAGINE
//$app->includeModule(__DIR__ . "/src", $app->getBaseUrl() . "/src");

$app->doDependencies();
$app->doRoutes();

require __DIR__ . "/core/routes.php";
require __DIR__ . "/core/dependencies.php";

$app->run();
```

### .htaccess

```
php_flag display_errors on
php_value error_reporting 6143
<IfModule mod_rewrite.c>
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule . index.php [L]
</IfModule>
```