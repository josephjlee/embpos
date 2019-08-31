<!-- Input Discount Modal -->
<div class="modal fade" id="discountModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('invoice/perbarui_diskon'); ?>" method="post" id="discount-form">
            <input type="hidden" name="order-id" id="order-id" value="<?= $invoice_detail['order_id']; ?>">
            <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambahkan Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="discount-amount"><small>Nominal</small></label>
                        <input type="text" name="discount-amount" id="discount-amount" class="form-control" value="<?= moneyStr($invoice_detail['discount']); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="save-discount-btn">Simpan data</button>
                </div>
            </div>
        </form>
    </div>
</div>