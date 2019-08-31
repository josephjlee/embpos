<!-- Update Date_Required Modal -->
<div class="modal fade" id="requiredDateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
    <form action="<?= base_url('invoice/ajax_update_required_date')?>" method="post" id="required-date-form">
        <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['order_id']; ?>">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Pengambilan Pesanan</h5>
            <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="required-date"><small>Tanggal</small></label>
            <input type="date" name="required-date" id="required-date" class="form-control" value="<?= date('Y-m-d', strtotime($invoice_detail['required_date'])); ?>">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary" id="update-required-date-btn">Simpan data</button>
        </div>
        </div>
    </form>
    </div>
</div>