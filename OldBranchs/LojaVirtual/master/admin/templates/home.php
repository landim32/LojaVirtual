<?php
namespace Emagine\Pedido;

use Emagine\Base\EmagineApp;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div id="main-content">
                <div class="row">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pie-chart"></i> Gráfico de Teste</h3>
                            </div>
                            <div class="panel-body">
                                <img src="<?php echo $app->getBaseUrl() . "/grafico/teste/pizza.png"; ?>" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-pie-chart"></i> Progressão de Vendas</h3>
                            </div>
                            <div class="panel-body">
                                <img src="<?php echo $app->getBaseUrl() . "/grafico/teste/linha.png"; ?>" class="img-responsive" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>