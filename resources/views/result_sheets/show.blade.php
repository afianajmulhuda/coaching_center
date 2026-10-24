@extends('layouts.master')

@section('title', 'Student Result Card')
@section('page_title', 'Official Result Card')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-10">

    <div class="card border-secondary mb-4">
      <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-bold">{{ $resultSheet->exam->title ?? 'Examination Report' }}</h4>
          <small class="text-muted">Class: {{ $resultSheet->exam->ClassModel->class_name ?? 'N/A' }} | Batch: {{ $resultSheet->student->batch->batch_name ?? 'N/A' }}</small>
        </div>
        <button onclick="window.print()" class="btn btn-outline-dark btn-sm d-print-none">
          <i class="bi bi-printer me-1"></i> Print Report Card
        </button>
      </div>

      <div class="card-body">
        <!-- Student Info Header -->
        <div class="row mb-4 pb-3 border-bottom">
          <div class="col-md-6">
            <h5><strong>Student Name:</strong> {{ $resultSheet->student->full_name }}</h5>
            <p class="mb-1"><strong>Roll Number:</strong> {{ $resultSheet->student->student_id_no }}</p>
          </div>
          <div class="col-md-6 text-md-end">
            <p class="mb-1"><strong>Batch Position:</strong> <span class="badge text-bg-primary fs-6">#{{ $resultSheet->position_in_batch }}</span></p>
            <p class="mb-1"><strong>Final Grade:</strong> <span class="fw-bold">{{ $resultSheet->grade }}</span> (GPA: {{ number_format($resultSheet->gpa, 2) }})</p>
          </div>
        </div>

        <!-- Marks Breakdown Table -->
        <div class="table-responsive mb-4">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Subject</th>
                <th style="width: 120px" class="text-center">Total Marks</th>
                <th style="width: 120px" class="text-center">Pass Marks</th>
                <th style="width: 140px" class="text-center">Obtained Marks</th>
                <th style="width: 100px" class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schedules as $schedule)
                @php
                  $entry = $marks[$schedule->id] ?? null;
                  $obtained = $entry ? $entry->obtained_marks : 0;
                  $isAbsent = $entry ? $entry->is_absent : false;
                  $isPassed = !$isAbsent && ($obtained >= $schedule->pass_marks);
                @endphp
                <tr>
                  <td><strong>{{ $schedule->subject->subject_name ?? 'N/A' }}</strong></td>
                  <td class="text-center">{{ number_format($schedule->total_marks, 2) }}</td>
                  <td class="text-center">{{ number_format($schedule->pass_marks, 2) }}</td>
                  <td class="text-center fw-bold">
                    @if($isAbsent)
                      <span class="text-danger">ABSENT</span>
                    @else
                      {{ number_format($obtained, 2) }}
                    @endif
                  </td>
                  <td class="text-center">
                    @if($isAbsent)
                      <span class="badge text-bg-secondary">Absent</span>
                    @elseif($isPassed)
                      <span class="badge text-bg-success">Pass</span>
                    @else
                      <span class="badge text-bg-danger">Fail</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot class="table-light">
              <tr>
                <th class="text-end">Aggregate Totals:</th>
                <th class="text-center">{{ number_format($schedules->sum('total_marks'), 2) }}</th>
                <th></th>
                <th class="text-center fw-bold fs-6">{{ number_format($resultSheet->total_obtained, 2) }}</th>
                <th class="text-center">
                  @if($resultSheet->grade === 'F')
                    <span class="badge text-bg-danger">FAILED</span>
                  @else
                    <span class="badge text-bg-success">PASSED</span>
                  @endif
                </th>
              </tr>
            </tfoot>
          </table>
        </div>

        <div class="d-flex justify-content-between text-muted align-items-center pt-3 border-top">
          <small>Publication Status: {{ $resultSheet->is_published ? 'Published on ' . $resultSheet->published_at?->format('M d, Y') : 'Draft' }}</small>
          <small>Generated on: {{ date('M d, Y') }}</small>
        </div>
      </div>

      <div class="card-footer d-print-none">
        <a href="{{ route('result-sheets.index') }}" class="btn btn-secondary btn-sm">Back to Results</a>
      </div>
    </div>

  </div>
</div>
@endsection