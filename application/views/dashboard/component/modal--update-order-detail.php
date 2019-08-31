<!-- Update Order Detail Modal -->
<div class="modal fade" id="orderDetailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">

    <form action="<?= base_url('invoice/update_single_order_detail'); ?>" method="post" id="order-detail-form">

      <input type="hidden" name="order-detail-id" id="order-detail-id" value="">
      <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
      <input type="hidden" name="item-id" id="item-id" value="">

      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title">Sunting Detil Barang</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label for="description"><small>Tentang barang</small></label>
            <input type="text" name="description" id="description" class="form-control description" value="" placeholder="Deskripsikan barang pesanan">
          </div>

          <div class="form-row">

            <div class="form-group col" data-priceconst="">

              <label for="item"><small>Jenis barang</small></label>
              <select name="item" id="item" class="items" data-priceconst="">
                <?php $items = $this->invoice->get_all_items(); ?>
                <?php foreach ($items as $item) : ?>
                  <option value="<?= $item['item_id']; ?>" data-priceconst='<?= json_encode(['item_pc' => $item['price_constant']]) ?>'><?= $item['item_name']; ?></option>
                <?php endforeach; ?>
              </select>

            </div>

            <div class="form-group col">

              <label for="position"><small>Posisi yang diinginkan</small></label>
              <select name="position" id="position" class="custom-select position">
                <?php $position_pairs = $this->invoice->get_all_positions(); ?>
                <?php foreach ($position_pairs as $position) : ?>
                  <option value="<?= $position['position_id']; ?>"><?= $position['position_name']; ?></option>
                <?php endforeach; ?>
              </select>

            </div>

          </div>

          <div class="form-row">

            <div class="form-group col">
              <label for="quantity"><small>Dimensi</small></label>
              <input type="text" name="dimension" id="dimension" class="form-control" value="">
            </div>
            <div class="form-group col">
              <label for=""><small>Warna</small></label>
              <input type="text" name="color" id="color" class="form-control" value="">
            </div>

          </div>

          <div class="form-row">

            <div class="form-group col">
              <label for="quantity"><small>Kuantitas</small></label>
              <input type="text" name="quantity" id="quantity" class="form-control quantity number" value="">
            </div>
            <div class="form-group col">
              <label for=""><small>Harga per kuantitas</small></label>
              <input type="text" name="price" id="price" class="form-control price number" value="">
            </div>

          </div>

          <div class="row px-2" id="stitch-row"></div>

          <div class="form-group">
            <label for=""><small>Harga total</small></label>
            <input type="text" name="" id="amount" class="form-control amount" value="" readonly>
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