@extends('layouts.pageLayout')

@section('title', 'Daily Planner')

@section('content')

<div class="container">
    <div class="time-table">
        <form id="diary_form" action="{{ route('admin.diary.store') }}" method="POST">
            @csrf
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#timeModal">
                Open Time Modal
            </button>
            @include('admin/modal/chooseTimeModal')
            <div class="days">
                <p>{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p>
            </div>

            <br>
            
            <div class="date">
                @if(isset($startTime) && isset($endTime))

                    <div class="time-slot">
                        <br>
                        <br>
                        <br>
                        <p>DATE</p>
                    </div>

                    <?php $counter = 0; ?>
                    @for ($hour = $startTime; $hour < $endTime; $hour++)
                        <?php $counter++; ?>
                        <div class="time-slot">
                            <p>{{ sprintf('%02d', $hour) }}:00 - {{ sprintf('%02d', $hour + 1) }}:00</p>
                            <!-- Debug output to see $hour value -->
                            <textarea name="event_{{ $hour }}"></textarea>
                        </div>
                        @if ($counter >= 24)
                            @break
                        @endif
                    @endfor

                @endif
            </div>
            
            </div>
            <div class="matrix-container">
                <table class="matrix">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Urgent</th>
                            <th>Not Urgent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Important</th>
                            <td><input type="text" name="urgent_important" placeholder="Do First"></td>
                            <td><input type="text" name="not_urgent_important" placeholder="Decide When"></td>
                        </tr>
                        <tr>
                            <th>Not Important</th>
                            <td><input type="text" name="urgent_not_important" placeholder="Delegate"></td>
                            <td><input type="text" name="not_urgent_not_important" placeholder="Delete"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <br>

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#priorityModal">
            Edit Priorities
            </button>

            <!-- Modal -->
            @include('admin/modal/priorityModel')
            <br>
            <br>

            <!-- Display priorities -->
            <div class="priorities">
                @if (isset($priority1) && isset($priority2) && isset($priority3))
                    <h2>TOP 3 PRIORITIES</h2>
                    <input type="checkbox">{{ $priority1 }}<br>
                    <input type="checkbox">{{ $priority2 }}<br>
                    <input type="checkbox">{{ $priority3 }}<br>
                @endif    
            </div>



            <br>

            <div class="right-side">
                <div class="reminder">
                    <label>REMINDER</label>
                    <textarea></textarea>
                </div>
                <div class="daily-affirmations">
                    <label>DAILY AFFIRMATIONS</label>
                    <textarea></textarea>
                </div>
                <div class="notes">
                    <label>FOR TOMORROW</label>
                    <textarea></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
</div>



@endsection

<script>
  // JavaScript to clear the form when modal is closed
  $('#priorityModal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
  });
</script>
