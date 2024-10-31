<?php
namespace Emagine\Produto;

use Emagine\Base\EmagineApp;
use Emagine\Login\BLL\UsuarioPerfilBLL;
use Emagine\Produto\Model\LojaInfo;
use Emagine\Produto\Model\CategoriaInfo;

/**
 * @var EmagineApp $app
 * @var LojaInfo $loja
 * @var UsuarioPerfilBLL $usuarioPerfil
 * @var CategoriaInfo $categoria
 * @var CategoriaInfo[] $categorias
 */
?>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <?php echo $usuarioPerfil->render(); ?>
            <?php echo $app->getMenu("lateral"); ?>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-reorder"></i> Categorias</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categoria"; ?>" enctype="multipart/form-data" class="form-horizontal">
                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                        <input type="hidden" name="id_categoria" value="<?php echo $categoria->getId(); ?>" />
                        <div class="form-group">
                            <label class="col-md-3 control-label">Foto:</label>
                            <div class="col-md-9">
                                <input type="file" name="foto" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label" for="id_pai">Pai:</label>
                            <div class="col-md-9">
                                <select id="id_pai" name="id_pai" class="form-control">
                                    <option value="">--Raiz--</option>
                                    <?php foreach ($categorias as $categoriaPai) : ?>
                                        <option value="<?php echo $categoriaPai->getId(); ?>"<?php
                                            if ($categoriaPai->getId() == $categoria->getIdPai()) {
                                                echo " selected='selected'";
                                            }
                                        ?>><?php echo $categoriaPai->getNomeCompleto(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <label class="col-md-3 control-label">Nome:</label>
                            <div class="col-md-9">
                                <input type="text" name="nome" class="form-control" value="<?php echo $categoria->getNome(); ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                <a href="<?php echo $app->getBaseUrl() . "/" . $loja->getSlug() . "/categorias"; ?>" class="btn btn-lg btn-default"><i class="fa fa-chevron-left"></i> Voltar</a>
                                <button type="submit" class="btn btn-lg btn-primary"><i class="fa fa-check"></i> Gravar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>