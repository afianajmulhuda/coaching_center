@extends('layouts.master')

@section('title', 'Bulk Mark Entry')
@section('page_title', 'Bulk Enter Exam Marks')

@section('content')
<div class="row">
  <div class="col-12">

    <!-- Step 1: Select Exam Schedule -->
    <div class="card card-primary mb-4">
      <div class="card-header">
        <h3 class="card-title">Select Exam Schedule</h3>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ route('mark-entries.create') }}" class="row g-3">
          <div class="col-md-9">
            <select name="exam_schedule_id" class="form-select" onchange="this.form.submit()">
              <option value="">-- Choose Exam Schedule to Load Students --</option>
              @foreach($schedules as $schedule)
                <option value="{{ $schedule->id }}" {{ request('exam_schedule_id') == $schedule->id ? 'selected' : '' }}>
                  {{ $schedule->exam->title ?? 'N/A' }} | {{ $schedule->subject->subject_name ?? '' }} | Batch: {{ $schedule->batch->batch_name ?? '' }} (Date: {{ $schedule->exam_date ? $schedule->exam_date->format('M d, Y') : 'N/A' }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">Load Students</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Step 2: Input Marks Table -->
    @if($selectedSchedule)
      <form action="{{ route('mark-entries.store') }}" method="POST">
        @csrf
        <input type="hidden" name="exam_schedule_id" value="{{ $selectedSchedule->id }}">

        <div class="card card-outline card-success mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <div>
              <h3 class="card-title fw-bold">{{ $selectedSchedule->exam->title ?? '' }} - {{ $selectedSchedule->subject->subject_name ?? '' }}</h3>
              <span class="text-muted ms-2">(Batch: {{ $selectedSchedule->batch->batch_name ?? '' }})</span>
            </div>
            <div>
              <span class="badge text-bg-info">Total Marks: {{ number_format($selectedSchedule->total_marks, 2) }}</span>
              <span class="badge text-bg-warning">Pass Marks: {{ number_format($selectedSchedule->pass_marks, 2) }}</span>
            </div>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered table-striped align-middle mb-0">
                <thead>
                  <tr>
                    <th style="width: 80px">Roll</th>
                    <th>Student Name</th>
                    <th style="width: 180px">Obtained Marks</th>
                    <th style="width: 120px" class="text-center">Absent?</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($students as $index => $student)
                    @php
                      $existing = $existingMarks[$student->id] ?? null;
                      $obtained = old("marks.{$index}.obtained_marks", $existing->obtained_marks ?? '');
                      $isAbsent = old("marks.{$index}.is_absent", $existing->is_absent ?? false);
                      $remarks = old("marks.{$index}.remarks", $existing->remarks ?? '');
                    @endphp
                    <tr>
                      <input type="hidden" name="marks[{{ $index }}][student_id]" value="{{ $student->id }}">
                      <td class="fw-bold text-center">{{ $student->student_id_no }}</td>
                      <td>{{ $student->full_name }}</td>
                      <td>
                        <input type="number" step="0.01" 
                               name="marks[{{ $index }}][obtained_marks]" 
                               class="form-control mark-input" 
                               value="{{ $obtained }}" 
                               min="0" 
                               max="{{ $selectedSchedule->total_marks }}"
                               placeholder="Max {{ number_format($selectedSchedule->total_marks, 0) }}"
                               {{ $isAbsent ? 'disabled' : '' }} />
                      </td>
                      <td class="text-center">
                        <div class="form-check d-flex justify-content-center">
                          <input type="checkbox" 
                                 name="marks[{{ $index }}][is_absent]" 
                                 value="1" 
                                 class="form-check-input absent-checkbox" 
                                 {{ $isAbsent ? 'checked' : '' }} />
                        </div>
                      </td>
                      <td>
                        <input type="text" name="marks[{{ $index }}][remarks]" class="form-control" value="{{ $remarks }}" placeholder="Optional note..." />
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-4">No students found in this batch.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          @if($students->count() > 0)
            <div class="card-footer">
              <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Save All Marks</button>
              <a href="{{ route('mark-entries.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
          @endif
        </div>
      </form>
    @endif

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Disable/Enable marks input when 'Absent' checkbox toggles
    document.querySelectorAll('.absent-checkbox').forEach(function (checkbox) {
      checkbox.addEventListener('change', function () {
        const row = this.closest('tr');
        const markInput = row.querySelector('.mark-input');
        if (this.checked) {
          markInput.value = '';
          markInput.disabled = true;
        } else {
          markInput.disabled = false;
        }
      });
    });
  });
</script>
@endsection