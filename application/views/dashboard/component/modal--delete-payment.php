<!-- Delete Payment Modal-->
<div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('invoice/hapus_pembayaran'); ?>" method="post" id="delete-payment-form">
            <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
            <input type="hidden" name="payment-id" id="payment-id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin akan menghapus?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pembayaran ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="delPaymentBtn">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>