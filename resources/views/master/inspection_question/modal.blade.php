<div class="modal modal-blur fade" id="main-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id='modal-title'></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="main-form" method="POST" action="">
          <input type="hidden" name="id" id="input-id">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Question</label>
              <input type="text" class="form-control" name="question" id="input-question" />
            </div>
            <div class="mb-3">
                <label class="form-label">Answer Type</label>
                <select name="type_answer" id="input-type-answer" class="form-control">
                    <option value="" disabled selected>-- Choose Answer Type --</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="input">Input</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Answer Question</label>
                <select name="type_question" id="input-type-question" class="form-control">
                    <option value="" disabled selected>-- Choose Question Type --</option>
                    <option value="inbound">InBound</option>
                    <option value="outbound">OutBound</option>
                </select>
            </div>
            <div class="form-answers"> </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>