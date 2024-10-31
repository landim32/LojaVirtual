<?php
namespace Emagine\Endereco;

use Emagine\Endereco\Model\EnderecoInfo;
use Emagine\Endereco\Model\UfInfo;

/**
 * @var EnderecoInfo $endereco
 * @var UfInfo[] $estados
 * @var string $formGroupClasse
 * @var bool $exibeCep
 * @var bool $exibePosicao
 * @var bool $exibeComplemento
 * @var bool $logradouroEditavel
 * @var bool $bairroEditavel
 * @var bool $cidadeEditavel
 * @var bool $ufEditavel
 * @var bool $posicaoEditavel
 */

?>
    <?php if ($exibeCep == true) : ?>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="cep">CEP:</label>
        <div class="col-md-3">
            <input type="text" id="cep" name="cep" class="form-control cep-busca" maxlength="8" value="<?php echo $endereco->getCep(); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="uf">UF:</label>
        <div class="col-md-3">
            <?php if ($ufEditavel) : ?>
                <select id="uf" name="uf" class="form-control cep-uf">
                    <option value="">--</option>
                    <?php foreach ($estados as $uf) : ?>
                        <option value="<?php echo $uf->getUf() ?>"<?php
                        echo ($endereco->getUf() == $uf->getUf()) ? " selected='selected'" : "";
                        ?>><?php echo $uf->getUf() ?></option>
                    <?php endforeach; ?>
                </select>
            <?php else : ?>
                <input type="text" id="uf" name="uf" class="form-control cep-uf"
                       value="<?php echo $endereco->getUf(); ?>" disabled="disabled" />
            <?php endif; ?>
        </div>
        <label class="col-md-2 control-label" for="cidade">Cidade:</label>
        <div class="col-md-4">
            <input type="text" id="cidade" name="cidade" class="form-control cep-cidade"
                   value="<?php echo $endereco->getCidade(); ?>" <?php echo (!$cidadeEditavel) ? "disabled='disabled'" : ""; ?> />
        </div>
    </div>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="bairro">Bairro:</label>
        <div class="col-md-9">
            <input type="text" id="bairro" name="bairro" class="form-control cep-bairro"
                   value="<?php echo $endereco->getBairro(); ?>" <?php echo (!$bairroEditavel) ? "disabled='disabled'" : ""; ?> />
        </div>
    </div>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="logradouro">Logradouro:</label>
        <div class="col-md-9">
            <input type="text" id="logradouro" name="logradouro" class="form-control cep-logradouro"
                   value="<?php echo $endereco->getLogradouro(); ?>" <?php echo (!$logradouroEditavel) ? "disabled='disabled'" : ""; ?> />
        </div>
    </div>
    <?php if ($exibeComplemento == true) : ?>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="complemento">Complemento:</label>
        <div class="col-md-5">
            <input type="text" id="complemento" name="complemento" class="form-control cep-complemento" value="<?php echo $endereco->getComplemento(); ?>" />
        </div>
        <label class="col-md-2 control-label" for="numero">Nº:</label>
        <div class="col-md-2">
            <input type="text" id="numero" name="numero" class="form-control cep-numero" value="<?php echo $endereco->getNumero(); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if ($exibePosicao == true) : ?>
    <div class="<?php echo $formGroupClasse; ?>">
        <label class="col-md-3 control-label" for="latitude">Latitude:</label>
        <div class="col-md-3">
            <input type="text" id="latitude" name="latitude" class="form-control cep-latitude"
                   value="<?php echo $endereco->getLatitude(); ?>" <?php echo (!$posicaoEditavel) ? "disabled='disabled'" : ""; ?> />
        </div>
        <label class="col-md-3 control-label" for="longitude">Longitude:</label>
        <div class="col-md-3">
            <input type="text" id="longitude" name="longitude" class="form-control cep-longitude"
                   value="<?php echo $endereco->getLongitude(); ?>" <?php echo (!$posicaoEditavel) ? "disabled='disabled'" : ""; ?> />
        </div>
    </div>
    <?php else : ?>
    <input type="hidden" id="latitude" name="latitude" value="<?php echo $endereco->getLatitude(); ?>" />
    <input type="hidden" id="longitude" name="longitude" value="<?php echo $endereco->getLongitude(); ?>" />
    <?php endif; ?>