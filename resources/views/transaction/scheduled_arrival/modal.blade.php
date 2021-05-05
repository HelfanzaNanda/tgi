<div class="modal modal-blur fade" id="main-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id='modal-title'></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="main-form" method="POST" action="">
          <input type="hidden" name="id" id="input-id">
          <div class="modal-body">
            <div>
              <label class="form-label">Invoice Number</label>
              <input type="number" class="form-control" name="invoice_number" id="input-invoice-number" />
            </div>

            <div>
              <label class="form-label">Kode Produk</label>
              <select class="form-control" id="input-inventory-id" name="inventory_id">
                  <option value="" selected disabled>-- Pilih Produk --</option>
                  @foreach ($inventories as $inventory)
                      <option value="{{ $inventory->id }}">{{ $inventory->code }}</option>
                  @endforeach
              </select>
            </div>

            <div>
              <label class="form-label">Qty</label>
              <input type="number" class="form-control" name="qty" id="input-qty" />
            </div>

            <div>
              <label class="form-label">Customer Order Number</label>
              <input type="number" class="form-control" name="customer_order_number" id="input-customer-order-number" />
            </div>

            <div>
              <label class="form-label">Dispatch Date</label>
              <input type="text" class="form-control datepicker" name="dispatch_date" id="input-dispatch-date" />
            </div>

            <div>
              <label class="form-label">ETA</label>
              <input type="text" class="form-control datepicker" name="estimated_time_of_arrival" id="input-eta" />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>