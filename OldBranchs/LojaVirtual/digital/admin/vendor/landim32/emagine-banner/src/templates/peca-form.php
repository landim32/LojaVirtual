<?php
namespace Emagine\Banner;

use Emagine\Banner\Model\BannerInfo;
use Emagine\Base\EmagineApp;
use Emagine\Base\Utils\StringUtils;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Login\Controls\UsuarioPerfil;
use Emagine\Banner\BLL\BannerBLL;
use Emagine\Banner\Model\BannerPecaInfo;
use Emagine\Login\Model\UsuarioInfo;
use Emagine\Produto\Controls\LojaPerfil;
use Emagine\Produto\Model\LojaInfo;

/**
 * @var EmagineApp $app
 * @var UsuarioInfo $usuario
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var LojaInfo[] $lojas
 * @var BannerInfo $banner
 * @var BannerInfo[] $banners
 * @var BannerPecaInfo $peca
 * @var string $erro
 */

?>
<div class="container">
	<div class="row">
		<div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
			<?php echo $app->getMenu("lateral"); ?>
		</div><!--col-md-3-->
		<div class="col-md-6">
			<?php if (!StringUtils::isNullOrEmpty($erro)) : ?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<i class="fa fa-warning"></i> <?php echo $erro; ?>
				</div>
			<?php endif; ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-picture-o"></i> Peças</h3>
				</div>
				<div class="panel-body">
					<form method="post" action="<?php echo $app->getBaseUrl() . "/banner/" . $banner->getSlug(); ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" id="cod_destino" name="cod_destino" value="<?php echo $peca->getCodDestino(); ?>" />
                        <?php if ($peca->getId() > 0) : ?>
                            <input type="hidden" name="id_peca" value="<?php echo $peca->getId(); ?>" />
                        <?php endif; ?>
                        <input type="hidden" id="id_produto" name="id_produto" value="<?php echo $peca->getIdProduto(); ?>" />
                        <div class="form-group text-right">
							<label class="col-md-3 control-label" for="id_banner">Banner<span class="required">*</span>:</label>
							<div class="col-md-9">
								<select id="id_banner" name="id_banner" class="form-control">
								    <?php foreach ($banners as $item) : ?>
									    <option value="<?php echo $item->getId(); ?>"<?php
                                        echo ($item->getId() == $peca->getIdBanner()) ? " selected='selected'" : "";
                                        ?>><?php
                                        echo $item->getNome() . " (" . $item->getLargura() . "x" . $item->getAltura() . ")";
                                        ?></option>
								    <?php endforeach; ?>
								</select>
							</div>
						</div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="foto">Imagem<span class="required">*</span>:</label>
                            <div class="col-md-9">
                                <input type="file" id="foto" name="foto" class="form-control" />
                            </div>
                        </div>
						<div class="form-group text-right">
							<label class="col-md-3 control-label" for="nome">Nome<span class="required">*</span>:</label>
							<div class="col-md-9">
								<input type="text" id="nome" name="nome" class="form-control" value="<?php echo $peca->getNome(); ?>" />
							</div>
						</div>
                        <?php if (count($lojas) > 1) : ?>
                            <div class="form-group text-right">
                                <label class="col-md-3 control-label" for="id_loja">Loja:</label>
                                <div class="col-md-9">
                                    <select id="id_loja" name="id_loja" class="form-control">
                                        <option value="0">--Não vinculado a uma loja--</option>
                                        <?php foreach ($lojas as $loja) : ?>
                                            <option value="<?php echo $loja->getId(); ?>"<?php
                                            echo ($loja->getId() == $peca->getIdLoja()) ? " selected='selected'" : "";
                                            ?>><?php echo $loja->getNome(); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php elseif (count($lojas) == 1) : ?>
                                <?php
                                /** @var LojaInfo $loja */
                                $loja = array_values($lojas)[0];
                                ?>
                                <input type="hidden" name="id_loja" value="<?php echo $loja->getId(); ?>" />
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="nome">Clique abre:</label>
                            <div class="col-md-9">
                                <div class="btn-group" role="group" aria-label="...">
                                    <?php if (count($lojas) > 1) : ?>
                                    <button type="button" id="destino_loja" class="<?php
                                    echo ($peca->getCodDestino() == BannerPecaInfo::DESTINO_LOJA) ? "btn btn-primary" : "btn btn-default";
                                    ?>" data-destino="<?php echo BannerPecaInfo::DESTINO_LOJA; ?>">Loja</button>
                                    <?php endif; ?>
                                    <button type="button" id="destino_produto" class="<?php
                                    echo ($peca->getCodDestino() == BannerPecaInfo::DESTINO_PRODUTO) ? "btn btn-primary" : "btn btn-default";
                                    ?>" data-destino="<?php echo BannerPecaInfo::DESTINO_PRODUTO; ?>">Produto</button>
                                    <button type="button" id="destino_url" class="<?php
                                    echo ($peca->getCodDestino() == BannerPecaInfo::DESTINO_URL) ? "btn btn-primary" : "btn btn-default";
                                    ?>" data-destino="<?php echo BannerPecaInfo::DESTINO_URL; ?>">Url</button>
                                </div>
                            </div>
                        </div>
                        <?php if (count($lojas) > 1) : ?>
                        <div id="div_loja" class="form-group text-right"<?php
                        echo ($peca->getCodDestino() != BannerPecaInfo::DESTINO_LOJA) ? " style='display: none;'" : "";
                        ?>>
                            <label class="col-md-3 control-label" for="id_loja_destino">Loja Destino:</label>
                            <div class="col-md-9">
                                <select id="id_loja_destino" name="id_loja_destino" class="form-control">
                                    <option value="0">--Selecione a loja--</option>
                                    <?php foreach ($lojas as $loja) : ?>
                                        <option value="<?php echo $loja->getId(); ?>"<?php
                                        echo ($loja->getId() == $peca->getIdLojaDestino()) ? " selected='selected'" : "";
                                        ?>><?php echo $loja->getNome(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div id="div_produto" class="form-group text-right"<?php
                        echo ($peca->getCodDestino() != BannerPecaInfo::DESTINO_PRODUTO) ? " style='display: none;'" : "";
                        ?>>
                            <label class="col-md-3 control-label" for="nome_produto">Produto:</label>
                            <div class="col-md-9">
                                <input type="text" id="nome_produto" name="nome_produto" class="form-control" value="<?php echo $peca->getProdutoNome(); ?>" />
                            </div>
                        </div>
						<div  id="div_url"class="form-group text-right"<?php
                        echo ($peca->getCodDestino() != BannerPecaInfo::DESTINO_URL) ? " style='display: none;'" : "";
                        ?>>
							<label class="col-md-3 control-label" for="url">Url:</label>
							<div class="col-md-9">
								<input type="text" id="url" name="url" class="form-control" value="<?php echo $peca->getUrl(); ?>" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 text-right">
                                <?php if (!is_null($banner)) : ?>
							        <a href="<?php echo $app->getBaseUrl() . "/banner/" .  $banner->getSlug(); ?>" class="btn btn-lg btn-default">
                                        <i class="fa fa-chevron-left"></i> Voltar
                                   </a>
                                <?php else : ?>
                                    <a href="<?php echo $app->getBaseUrl() . "/banner/listar"; ?>" class="btn btn-lg btn-default">
                                        <i class="fa fa-chevron-left"></i> Voltar
                                    </a>
                                <?php endif; ?>
								 <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div><!--col-md-6-->
	</div><!--row-->
</div><!--container-->
