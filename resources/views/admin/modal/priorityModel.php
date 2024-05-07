<div class="modal fade" id="priorityModal" tabindex="-1" role="dialog" aria-labelledby="priorityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="priorityModalLabel">Edit Priorities</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <!-- Your form for entering priorities -->
            <form id="priorityForm" action="{{ route('diaryPrioritySlot') }}" method="POST">
                <div class="form-group">
                    <label for="priority1">Priority 1</label>
                    <input type="text" class="form-control" id="priority1" name="priority1">
                </div>
                <div class="form-group">
                    <label for="priority2">Priority 2</label>
                    <input type="text" class="form-control" id="priority2" name="priority2">
                </div>
                <div class="form-group">
                    <label for="priority3">Priority 3</label>
                    <input type="text" class="form-control" id="priority3" name="priority3">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        </div>
    </div>
    </div>