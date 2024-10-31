<?php
namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Produto\Model\LojaInfo;
/**
 * @var EmagineApp $app
 * @var string $cep
 */
$urlBuscaPorCep = $app->getBaseUrl() . "/busca-por-endereco";
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="text-center">
                <img src="<?php echo $app->getBaseUrl() . "/images/logo.png"; ?>" alt="<?php echo APP_NAME; ?>" class="img-responsive" style="max-height: 120px; margin: 5px auto;" />
            </div>
            <h3 class="text-center">Informe o endereço para entrega.</h3>
            <div class="panel panel-default">
                <div class="panel-body">
                    <form method="POST" class="form-horizontal" action="<?php echo $urlBuscaPorCep; ?>">
                        <input type="hidden" name="cep" class="cep-busca" value="<?php echo $cep; ?>" />
