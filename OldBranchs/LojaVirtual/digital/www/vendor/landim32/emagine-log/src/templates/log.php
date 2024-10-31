<?php
namespace Emagine\Log;

use Emagine\Base\EmagineApp;
use Emagine\Log\Model\LogInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var LogInfo $log
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 style="margin-top: 5px; margin-bottom: 5px;">
                        <i class="<?php echo "fa " . $log->getIcone(); ?>"></i>
                        <?php echo $log->getTitulo(); ?>
                    </h3>
                    <hr />
                    <dl class="dl-horizontal">
                        <dt>Tipo:</dt>
                        <dd><?php echo $log->getTipo(); ?></dd>
                        <dt>Data:</dt>
                        <dd>
                            <?php echo $log->getDataInclusaoStr(); ?>
                            <span class="text-muted">(<?php
                                echo date("d/m/Y H:i", strtotime($log->getDataInclusao()));
                            ?>)</span>
                        </dd>
                    </dl>
                    <hr />
                    <p style="font-family: 'Courier New', Courier, monospace; font-size: 10px">
                        <?php
                        $descricao = $log->getDescricao();
                        $descricao = str_replace("\t", " ", $descricao);
                        $descricao = str_replace(" ", "&nbsp;", $descricao);
                        $descricao = str_replace("\n", "<br />\n", $descricao);
                        echo $descricao;
                        ?>
                    </p>
                    <div class="text-right">
                        <a href="<?php echo $app->getBaseUrl() . "/log"; ?>" class="btn btn-lg btn-default">
                            <i class="fa fa-chevron-left"></i> Voltar
                        </a>
                        <a href="<?php echo $app->getBaseUrl() . "/log/excluir/" . $log->getId(); ?>" class="btn btn-lg btn-danger confirm">
                            <i class="fa fa-remove"></i> Excluir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>