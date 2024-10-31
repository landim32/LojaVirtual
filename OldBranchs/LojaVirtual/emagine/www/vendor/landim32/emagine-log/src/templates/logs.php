<?php
namespace Emagine\Log;

use Emagine\Base\EmagineApp;
use Emagine\Log\Model\LogFiltroInfo;
use Emagine\Log\Model\LogRetornoInfo;
use Emagine\Login\Controls\UsuarioPerfil;

/**
 * @var EmagineApp $app
 * @var LogFiltroInfo $filtro
 * @var LogRetornoInfo $logs
 * @var string $paginacao
 */

?>
<style type="text/css">
    .pagination {
        margin: 0;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo UsuarioPerfil::render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 style="margin: 0px auto"><i class="fa fa-history"></i> Logs</h3>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" class="form-horizontal">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" name="ini" class="form-control datepicker" placeholder="Início"
                                           value="<?php echo $filtro->getDataInicioStr()?>" />
                                    <span class="input-group-addon">até</span>
                                    <input type="text" name="fim" class="form-control datepicker" placeholder="Termíno"
                                           value="<?php echo $filtro->getDataFimStr()?>" />
                                    <span class="input-group-btn">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr />
                    <div class="text-right"><?php echo $paginacao; ?></div>
                    <table class="table table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th><a href="#">Data</a></th>
                            <th><a href="#">Usuário</a></th>
                            <th><a href="#">Log</a></th>
                            <th class="text-right"><a href="#"><i class="fa fa-cog"></i></a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (count($logs->getLogs()) > 0) : ?>
                            <?php foreach ($logs->getLogs() as $log) : ?>
                                <?php $urlLog = $app->getBaseUrl() . "/log/" . $log->getId(); ?>
                                <tr class="<?php echo $log->getClasse(); ?>">
                                    <td>
                                        <a href="<?php echo $urlLog; ?>">
                                            <i class="<?php echo "fa fa-fw " . $log->getIcone(); ?>"></i>
                                            <?php echo $log->getDataInclusaoStr(); ?>
                                        </a>
                                    </td>
                                    <td><a href="<?php echo $urlLog; ?>"><?php echo $log->getNome(); ?></a></td>
                                    <td><a href="<?php echo $urlLog; ?>"><?php echo $log->getTitulo(); ?></a></td>
                                    <td class="text-right">
                                        <a class="confirm" href="<?php echo $app->getBaseUrl() . "/log/excluir/" . $log->getId(); ?>">
                                            <i class="fa fa-remove"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4"><i class="fa fa-warning"></i> Nenhum logs cadastrado.</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="text-right"><?php echo $paginacao; ?></div>
                </div>
            </div>
        </div>
    </div>
</div>