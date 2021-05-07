<div class="modal modal-blur fade" id="ba-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id='modal-title'></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ba-form" method="POST" action="">
                <input type="hidden" name="id" id="input-id">
                <input type="hidden" name="model_id" id="input-model-id">
                <input type="hidden" name="type" value="inbound">
                <input type="hidden" name="model" value="{{ App\Models\IncomingInventories::class }}">

                <div class="modal-body">
                    <div>
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control datepicker" name="date" 
                        id="input-date" />
                    </div>

                    <div>
                        <label class="form-label">Supplier</label>
                        <select class="form-control single-select" id="input-received-by" 
                        name="received_by">
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" id="input-description" 
                        class="form-control summernote" rows="10"></textarea>
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
