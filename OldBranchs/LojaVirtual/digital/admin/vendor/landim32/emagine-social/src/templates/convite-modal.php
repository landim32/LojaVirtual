<?php

namespace Emagine\Social;

use Imobsync\BLL\ImovelBLL;
use Imobsync\BLL\ContaBLL;
?>
<div class="modal fade" id="conviteModal">
    <div class="modal-dialog">
        <form method="POST" action="ajax-convite" class="form-horizontal no-validate">
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo _('Invite'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?php echo sprintf(_('Invite a friend to help expand your %s.'), ContaBLL::pegarNomeBolsaImoveis()); ?></p>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo _('Name'); ?>:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control required" name="nome" placeholder="<?php echo _('Your friend\'s name'); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-md-3 control-label"><?php echo _('Email'); ?>:<span class="required">*</span></label>
                    <div class="col-md-9">
                        <input type="email" class="form-control email required" name="email" placeholder="<?php echo _('Your friend\'s email'); ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _('Cancel'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _('Send invite'); ?></button>
            </div>
        </div>
        </form>
    </div><!-- /.modal-dialog -->
</div>