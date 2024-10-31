<div class="modal fade" id="fotoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enviar Foto</h4>
            </div>
            <div class="modal-body form-horizontal">
                <div class="form-group">
                    <label for="relationship-fieldname" class="control-label col-md-3">Foto:</label>
                    <div class="col-md-9">
                        <input type="file" id="fotoEnviar" name="fotoEnviar" class="form-control" />
                    </div>
                </div>
                <div>
                    <img src="" id="fotoUsuario" style="display: none; max-width: 100%" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-remove"></i> Fechar
                </button>
                <button type="button" id="foto-submit" class="btn btn-primary">
                    <i class="fa fa-check"></i> Enviar Foto
                </button>
            </div>
        </div>
    </div>
</div>