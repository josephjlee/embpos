 <!-- Update Payment Modal -->
 <div class="modal fade" id="updatePaymentModal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
     <form action="<?= base_url('invoice/update_payment'); ?>" method="post" id="payment-update-form">
       <input type="hidden" name="order-number" id="order-number" value="<?= $invoice_detail['invoice_number']; ?>">
       <input type="hidden" name="payment-id" id="payment-id" value="">

       <div class="modal-content">
         <div class="modal-header">
           <h5 class="modal-title">Detil Pembayaran</h5>
           <button type="button" class="close" data-dismiss="modal">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <div class="modal-body">

           <div class="form-row">
             <div class="form-group col">
               <label for="payment-amount"><small>Nominal</small></label>
               <input type="text" name="payment-amount" id="payment-amount" class="form-control" value="">
             </div>
             <div class="form-group col">
               <label for="payment-method"><small>Metode Pembayaran</small></label>
               <select name="payment-method" id="payment-method" class="custom-select">
                 <option value="">Pilih...</option>

                 <?php

                  $this->db->select('payment_method_id, name');
                  $this->db->from('payment_method');
                  $payment_method_query = $this->db->get();

                  $payment_methods = $payment_method_query->result_array();

                  ?>

                 <?php foreach ($payment_methods as $payment_method) : ?>
                   <option value="<?= $payment_method['payment_method_id']; ?>"><?= $payment_method['name']; ?></option>
                 <?php endforeach; ?>

               </select>
             </div>
           </div>

           <div class="form-group">
             <label for="payment-date"><small>Tanggal</small></label>
             <input type="date" name="payment-date" id="payment-date" class="form-control" value="">
           </div>


         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
           <button type="submit" class="btn btn-primary" id="update-payment-btn">Perbarui data</button>
         </div>

       </div>
     </form>
   </div>
 </div>