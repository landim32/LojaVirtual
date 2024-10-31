<?php

namespace Emagine\Loja;

use Emagine\Base\EmagineApp;
use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Login\Model\UsuarioEnderecoInfo;
use Emagine\Pedido\BLL\PedidoBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\ProdutoInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var ProdutoInfo[] $produtos
 * @var EnderecoInfo $endereco
 * @var UsuarioEnderecoInfo[] $enderecos
 * @var double $valorFrete
 * @var string[] $pagamentos
 */
$urlBase = $app->getBaseUrl() . "/" . $loja->getSlug();
?>

                    </div>
                </div>
                <hr />
                <div class="text-right">
                    <a href="<?php echo $urlBase . "/carrinho"; ?>" class="btn btn-lg btn-default">
                        <i class="fa fa-chevron-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-lg btn-primary">
                        Efetuar Pagamento <i class="fa fa-chevron-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
