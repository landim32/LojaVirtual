<style type="text/css">
    .ui-autocomplete {
        z-index: 2147483647!important;
    }
</style>
<div class="modal fade" id="mensagemModal">
    <div class="modal-dialog">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Nova mensagem</h4>
            </div>
            <div class="modal-body">
                <p>Envie uma mensagem provada para a pessoa abaixo.</p>
                <div class="form-group">
                    <label class="col-md-3 control-label">Para:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <div id="mensagem-controle">
                            <input type="text" class="form-control" id="mensagem-para" name="mensagem-para" placeholder="Busque por um amigo, parceiro ou colega...">
                            <span class="help-block">Busque seu amigo ou entre com o email para convida-lo.</span>
                        </div>
                        <ul id="mensagem-usuario" class="list-group" style="display: none">
                            <li class="list-group-item">
                                <a id="mensagem-remover" class="pull-right" style="margin: 10px 0px" href="#"><i class="fa fa-remove"></i></a>
                                <img id="mensagem-foto" class="img-circle" style="margin-right: 10px; width: 40px; height: 40px;">
                                <span id="mensagem-nome" title="Sem nome">Sem nome</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label">Mensagem:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <textarea class="form-control required limited" id="mensagem-conteudo" name="mensagem-conteudo" maxlength="200" placeholder="Preencha sua mensagem aqui" rows="5"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Cancelar</button>
                <button id="mensagem-submit" type="button" class="btn btn-primary">
                    <i class="fa fa-envelope"></i> Enviar
                </button>
            </div>
        </div>
    </div><!-- /.modal-dialog -->
</div>