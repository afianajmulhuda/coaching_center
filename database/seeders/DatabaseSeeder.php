<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks to allow clean seeding
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 1. Seed Roles
        $roles = [
            ['id' => 1, 'name' => 'Admin'],
            ['id' => 2, 'name' => 'Teacher'],
            ['id' => 3, 'name' => 'Student'],
            ['id' => 4, 'name' => 'Accountant'],
        ];
        DB::table('roles')->truncate();
        DB::table('roles')->insert($roles);

        // 2. Seed Users
        $password = Hash::make('password123');
        $users = [
            // Admin
            ['id' => 1, 'username' => 'admin', 'email' => 'admin@coaching.com', 'phone' => '01700000001', 'password' => $password, 'role_id' => 1, 'status' => 'active'],
            // Accountant
            ['id' => 2, 'username' => 'accountant', 'email' => 'accounts@coaching.com', 'phone' => '01700000002', 'password' => $password, 'role_id' => 4, 'status' => 'active'],
            // Teachers
            ['id' => 3, 'username' => 'teacher_rahim', 'email' => 'rahim@coaching.com', 'phone' => '01711000001', 'password' => $password, 'role_id' => 2, 'status' => 'active'],
            ['id' => 4, 'username' => 'teacher_karim', 'email' => 'karim@coaching.com', 'phone' => '01711000002', 'password' => $password, 'role_id' => 2, 'status' => 'active'],
            // Students
            ['id' => 5, 'username' => 'student_tanvir', 'email' => 'tanvir@student.com', 'phone' => '01822000001', 'password' => $password, 'role_id' => 3, 'status' => 'active'],
            ['id' => 6, 'username' => 'student_samia', 'email' => 'samia@student.com', 'phone' => '01822000002', 'password' => $password, 'role_id' => 3, 'status' => 'active'],
        ];
        DB::table('users')->truncate();
        DB::table('users')->insert($users);

        // 3. Seed Classes
        $classes = [
            ['id' => 1, 'class_name' => 'Class 10 (SSC)'],
            ['id' => 2, 'class_name' => 'HSC 1st Year'],
        ];
        DB::table('classes')->truncate();
        DB::table('classes')->insert($classes);

        // 4. Seed Subjects
        $subjects = [
            ['id' => 1, 'class_id' => 1, 'subject_code' => 'PHY-101', 'subject_name' => 'Physics'],
            ['id' => 2, 'class_id' => 1, 'subject_code' => 'CHE-101', 'subject_name' => 'Chemistry'],
            ['id' => 3, 'class_id' => 1, 'subject_code' => 'MTH-101', 'subject_name' => 'Higher Mathematics'],
            ['id' => 4, 'class_id' => 2, 'subject_code' => 'PHY-201', 'subject_name' => 'Physics 1st Paper'],
        ];
        DB::table('subjects')->truncate();
        DB::table('subjects')->insert($subjects);

        // 5. Seed Batches
        $batches = [
            ['id' => 1, 'class_id' => 1, 'batch_name' => 'Morning Batch A', 'max_capacity' => 40],
            ['id' => 2, 'class_id' => 1, 'batch_name' => 'Evening Batch B', 'max_capacity' => 35],
            ['id' => 3, 'class_id' => 2, 'batch_name' => 'HSC Science Alpha', 'max_capacity' => 50],
        ];
        DB::table('batches')->truncate();
        DB::table('batches')->insert($batches);

        // 6. Seed Teachers Profile
        $teachers = [
            ['id' => 1, 'user_id' => 3, 'full_name' => 'Dr. Abdur Rahim', 'designation' => 'Senior Physics Instructor', 'salary_type' => 'monthly', 'base_salary' => 35000.00],
            ['id' => 2, 'user_id' => 4, 'full_name' => 'Prof. Nazmul Karim', 'designation' => 'Chemistry Lecturer', 'salary_type' => 'per_class', 'base_salary' => 800.00],
        ];
        DB::table('teachers')->truncate();
        DB::table('teachers')->insert($teachers);

        // 7. Seed Students Information
        $students = [
            ['id' => 1, 'user_id' => 5, 'student_id_no' => 'CC-2026-001', 'full_name' => 'Tanvir Hasan', 'guardian_name' => 'Rafiqul Hasan', 'guardian_phone' => '01799000001', 'class_id' => 1, 'batch_id' => 1, 'admission_date' => '2026-01-10'],
            ['id' => 2, 'user_id' => 6, 'student_id_no' => 'CC-2026-002', 'full_name' => 'Samia Sultana', 'guardian_name' => 'Kamrul Islam', 'guardian_phone' => '01799000002', 'class_id' => 1, 'batch_id' => 1, 'admission_date' => '2026-01-12'],
        ];
        DB::table('students')->truncate();
        DB::table('students')->insert($students);

        // 8. Seed Class Routines
        $classRoutines = [
            ['id' => 1, 'batch_id' => 1, 'subject_id' => 1, 'teacher_id' => 1, 'day_of_week' => 'Saturday', 'start_time' => '08:00:00', 'end_time' => '09:30:00', 'room_number' => '101'],
            ['id' => 2, 'batch_id' => 1, 'subject_id' => 2, 'teacher_id' => 2, 'day_of_week' => 'Monday', 'start_time' => '08:00:00', 'end_time' => '09:30:00', 'room_number' => '102'],
        ];
        DB::table('class_routines')->truncate();
        DB::table('class_routines')->insert($classRoutines);

        // 9. Seed Fee Types
        $feeTypes = [
            ['id' => 1, 'title' => 'Admission Fee', 'default_amount' => 5000.00],
            ['id' => 2, 'title' => 'Monthly Tuition Fee', 'default_amount' => 2500.00],
            ['id' => 3, 'title' => 'Model Test Fee', 'default_amount' => 1000.00],
        ];
        DB::table('fee_types')->truncate();
        DB::table('fee_types')->insert($feeTypes);

        // 10. Seed Student Fees
        $studentFees = [
            ['id' => 1, 'student_id' => 1, 'fee_type_id' => 2, 'billing_month_year' => '2026-07', 'amount' => 2500.00, 'discount' => 200.00, 'due_date' => '2026-07-10', 'status' => 'paid'],
            ['id' => 2, 'student_id' => 2, 'fee_type_id' => 2, 'billing_month_year' => '2026-07', 'amount' => 2500.00, 'discount' => 0.00, 'due_date' => '2026-07-10', 'status' => 'unpaid'],
        ];
        DB::table('student_fees')->truncate();
        DB::table('student_fees')->insert($studentFees);

        // 11. Seed Payment Collections
        $paymentCollections = [
            ['id' => 1, 'invoice_no' => 'INV-202607-001', 'student_fee_id' => 1, 'paid_amount' => 2300.00, 'payment_method' => 'bkash', 'transaction_ref' => 'TRX99881122', 'collected_by_user_id' => 2, 'created_at' => now()],
        ];
        DB::table('payment_collections')->truncate();
        DB::table('payment_collections')->insert($paymentCollections);

        // 12. Seed Account Transactions
        $accountTransactions = [
            ['id' => 1, 'transaction_type' => 'income', 'category' => 'Tuition Collection', 'amount' => 2300.00, 'description' => 'Collected from Tanvir Hasan (INV-202607-001)', 'created_at' => now()],
            ['id' => 2, 'transaction_type' => 'expense', 'category' => 'Electricity', 'amount' => 4500.00, 'description' => 'Monthly electricity bill for July 2026', 'created_at' => now()],
        ];
        DB::table('account_transactions')->truncate();
        DB::table('account_transactions')->insert($accountTransactions);

        // 13. Seed Exams
        $exams = [
            ['id' => 1, 'title' => 'Weekly Model Test 01 - 2026', 'class_id' => 1],
        ];
        DB::table('exams')->truncate();
        DB::table('exams')->insert($exams);

        // 14. Seed Exam Schedules
        $examSchedules = [
            ['id' => 1, 'exam_id' => 1, 'subject_id' => 1, 'batch_id' => 1, 'exam_date' => '2026-07-15', 'start_time' => '10:00:00', 'total_marks' => 100.00, 'pass_marks' => 40.00],
            ['id' => 2, 'exam_id' => 1, 'subject_id' => 2, 'batch_id' => 1, 'exam_date' => '2026-07-17', 'start_time' => '10:00:00', 'total_marks' => 100.00, 'pass_marks' => 40.00],
        ];
        DB::table('exam_schedules')->truncate();
        DB::table('exam_schedules')->insert($examSchedules);

        // 15. Seed Mark Entries
        $markEntries = [
            ['id' => 1, 'exam_schedule_id' => 1, 'student_id' => 1, 'obtained_marks' => 88.50, 'is_absent' => 0, 'remarks' => 'Excellent performance'],
            ['id' => 2, 'exam_schedule_id' => 1, 'student_id' => 2, 'obtained_marks' => 74.00, 'is_absent' => 0, 'remarks' => 'Good'],
        ];
        DB::table('mark_entries')->truncate();
        DB::table('mark_entries')->insert($markEntries);

        // 16. Seed Result Sheets
        $resultSheets = [
            ['id' => 1, 'exam_id' => 1, 'student_id' => 1, 'total_obtained' => 88.50, 'gpa' => 5.00, 'grade' => 'A+', 'position_in_batch' => 1, 'is_published' => 1, 'published_at' => now()],
            ['id' => 2, 'exam_id' => 1, 'student_id' => 2, 'total_obtained' => 74.00, 'gpa' => 4.00, 'grade' => 'A', 'position_in_batch' => 2, 'is_published' => 1, 'published_at' => now()],
        ];
        DB::table('result_sheets')->truncate();
        DB::table('result_sheets')->insert($resultSheets);

        // 17. Seed Notices Table
        $notices = [
            [
                'id' => 1,
                'title' => 'Weekly Test Schedule - July 2026',
                'description' => 'The Weekly Model Test 01 will begin on July 15, 2026. All students must attend in proper uniform.',
                'attachment_url' => 'notices/routine_july2026.pdf',
                'target_audience' => 'all',
                'batch_id' => null,
                'is_pinned' => 1,
                'created_by_user_id' => 1,
                'published_date' => '2026-07-01',
                'expiry_date' => '2026-07-20',
                'status' => 'published',
                'created_at' => now(),
            ],
            [
                'id' => 2,
                'title' => 'Extra Physics Class for Morning Batch A',
                'description' => 'An extra Physics class will take place on Sunday at 10:00 AM in Room 101.',
                'attachment_url' => null,
                'target_audience' => 'batch',
                'batch_id' => 1,
                'is_pinned' => 0,
                'created_by_user_id' => 3,
                'published_date' => '2026-07-05',
                'expiry_date' => '2026-07-12',
                'status' => 'published',
                'created_at' => now(),
            ],
        ];
        DB::table('notices')->truncate();
        DB::table('notices')->insert($notices);

        // 18. Seed SMS Logs
        $smsLogs = [
            ['id' => 1, 'recipient_phone' => '01799000001', 'message' => 'Dear Guardian, Tanvir Hasan scored 88.50 (A+) in Physics (Weekly Model Test 01).', 'type' => 'result', 'status' => 'sent', 'sent_at' => now()],
            ['id' => 2, 'recipient_phone' => '01799000001', 'message' => 'Payment Receipt: Received 2300 TK for Invoice INV-202607-001. Thank you!', 'type' => 'payment_receipt', 'status' => 'sent', 'sent_at' => now()],
        ];
        DB::table('sms_logs')->truncate();
        DB::table('sms_logs')->insert($smsLogs);

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}