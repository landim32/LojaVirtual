<div class="modal fade" id="grupoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Grupo de Usuário</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label for="grupo-nome" class="control-label col-md-3">Nome:</label>
                    <div class="col-md-9">
                        <input type="text" id="grupo-nome" name="nome" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="grupo-permissoes" class="control-label col-md-3">Permissões:</label>
                    <div class="col-md-9">
                        <select id="grupo-permissoes" name="permissoes" class="form-control multiselect" multiple="multiple"></select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Fechar
                </button>
                <button type="button" id="grupo-submit" class="btn btn-primary">
                    <i class="fa fa-check"></i> Gravar
                </button>
            </div>
        </div>
    </div>
</div>