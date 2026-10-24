<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Batch;
use App\Models\PaymentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Metric Cards Data
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $activeBatches = Batch::count(); // Adjust column if needed
        $totalCollections = PaymentCollection::sum('paid_amount');

        // Revenue Chart (Monthly Collection for Current Year)
        $monthlyRevenue = PaymentCollection::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(paid_amount) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Format Monthly Data for Chart (Jan to Dec)
        $chartRevenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartRevenueData[] = $monthlyRevenue[$i] ?? 0;
        }

        // Student Enrollment by Class
        $enrollmentByClass = DB::table('classes')
            ->leftJoin('students', 'classes.id', '=', 'students.class_id')
            ->select('classes.class_name', DB::raw('count(students.id) as total'))
            ->groupBy('classes.id', 'classes.class_name')
            ->pluck('total', 'classes.class_name')
            ->toArray();

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'activeBatches',
            'totalCollections',
            'chartRevenueData',
            'enrollmentByClass'
        ));
    }
}