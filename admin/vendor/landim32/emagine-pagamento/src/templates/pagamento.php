<?php
namespace Emagine\Pagamento;

use Emagine\Base\EmagineApp;
use Emagine\Pagamento\Controls\PagamentoForm;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var EmagineApp $app
 * @var string[] $bandeiras
 * @var PagamentoInfo $pagamento
 * @var string $conteudo
 * @var string $erro
 */
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php if (isset($erro)) : ?>
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="fa fa-warning"></i> <?php echo $erro; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?php echo $app->getBaseUrl() . "/pagamento/pagar"; ?>" class="form-horizontal">
                    <?php echo $conteudo; ?>
                    <div class="text-right">
                        <button type="submit" class="btn btn-lg btn-primary">PAGAR <i class="fa fa-chevron-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>