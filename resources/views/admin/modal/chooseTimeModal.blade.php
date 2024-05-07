<!-- Modal -->
<div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="timeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="timeModalLabel">Enter Time Slot</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Time Form -->
                        <form id="timeForm" action="{{ route('diaryTimeSlot') }}" method="POST">
                            @csrf
                            <!-- Start Time Field -->
                            <div class="form-group">
                                <label for="startTime">Start Time:</label>
                                <input type="number" class="form-control" id="startTime" name="startTime" min="1" max="24" placeholder="Hours" required>
                                <!-- Step value of 3600 seconds = 1 hour -->
                            </div>
                            <!-- End Time Field -->
                            <div class="form-group">
                                <label for="endTime">End Time:</label>
                                <input type="number" class="form-control" id="endTime" name="endTime" min="1" max="24" placeholder="Hours" required>
                                <!-- Step value of 3600 seconds = 1 hour -->
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>