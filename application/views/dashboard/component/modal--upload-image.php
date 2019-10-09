<!-- Delete Order Modal-->
<div class="modal fade" id="uploadImageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form action="<?= base_url('pesanan/unggah_gambar'); ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8" id="upload-order-image-form">
            <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
            <input type="hidden" name="order-detail-id" id="order-detail-id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="close" type="button" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="custom-file">
                        <input type="file" name="artwork" class="custom-file-input" id="artwork">
                        <label class="custom-file-label" for="customFile">Pilih file</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">Unggah</button>
                </div>
            </div>
        </form>
    </div>
</div>