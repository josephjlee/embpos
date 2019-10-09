<!-- Delete Order Modal-->
<div class="modal fade" id="delOrderDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('invoice/hapus_pesanan'); ?>" method="post" id="delete-order-detail-form">
            <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
            <input type="hidden" name="order-detail-id" id="order-detail-id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin akan menghapus?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Klik "Hapus" jika Anda yakin untuk menghapus pesanan ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="delOrderBtn">Hapus</button>
                </div>
            </div>
        </form>
    </div>
</div>