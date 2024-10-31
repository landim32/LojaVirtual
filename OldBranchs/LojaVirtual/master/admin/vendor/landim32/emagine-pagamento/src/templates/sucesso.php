<?php
namespace Emagine\Pagamento;

use Emagine\Base\EmagineApp;
use Emagine\Pagamento\Model\PagamentoInfo;

/**
 * @var EmagineApp $app
 * @var PagamentoInfo $pagamento
 */

?>
<div class="row">
    <div class="col-md-9 text-center">
        <?php if ($pagamento->getCodSituacao() == PagamentoInfo::SITUACAO_PAGO) : ?>
        <div class="panel panel-success">
            <div class="panel-body">
                <i class="fa fa-5x fa-thumbs-up text-success"></i>
                <h1>Pagamento efetuado com sucesso!</h1>
                <br />
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit, leo ut pharetra imperdiet, quam
                    enim varius nunc, ultrices semper tellus arcu at ligula. Vestibulum bibendum convallis consequat. Fusce
                    blandit orci in laoreet dictum. Pellentesque lobortis convallis laoreet. Mauris rhoncus vitae enim sed mattis.
                </p>
                <br />
                <a href="<?php echo $app->getBaseUrl(); ?>" class="btn btn-lg btn-primary"><i class="fa fa-chevron-left"></i> Voltar</a>
            </div>
        </div>
        <?php elseif ($pagamento->getCodTipo() == PagamentoInfo::BOLETO) : ?>
            <div class="panel panel-info">
                <div class="panel-body">
                    <i class="fa fa-5x fa-clock-o text-info"></i>
                    <h1>Seu pagamento ainda não foi efetuado!</h1>
                    <br />
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit, leo ut pharetra imperdiet, quam
                        enim varius nunc, ultrices semper tellus arcu at ligula. Vestibulum bibendum convallis consequat. Fusce
                        blandit orci in laoreet dictum. Pellentesque lobortis convallis laoreet. Mauris rhoncus vitae enim sed mattis.
                    </p>
                    <p>
                        Clique no botão abaixo para imprimir o boleto. Pode demorar de um a dois dias para baixa no pagamento.
                    </p>
                    <br />
                    <a href="<?php echo $pagamento->getBoletoUrl(); ?>" target="_blank" class="btn btn-lg btn-primary"><i class="fa fa-print"></i> Imprimir Boleto</a>
                    <a href="<?php echo $app->getBaseUrl(); ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                </div>
            </div>
        <?php else : ?>
            <div class="panel panel-info">
                <div class="panel-body">
                    <i class="fa fa-5x fa-remove text-danger"></i>
                    <h1><?php echo $pagamento->getMensagem(); ?></h1>
                    <br />
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. In hendrerit, leo ut pharetra imperdiet, quam
                        enim varius nunc, ultrices semper tellus arcu at ligula. Vestibulum bibendum convallis consequat. Fusce
                        blandit orci in laoreet dictum. Pellentesque lobortis convallis laoreet. Mauris rhoncus vitae enim sed mattis.
                    </p>
                    <br />
                    <a href="<?php echo $app->getBaseUrl(); ?>" class="btn btn-lg btn-primary"><i class="fa fa-chevron-left"></i> Voltar</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>