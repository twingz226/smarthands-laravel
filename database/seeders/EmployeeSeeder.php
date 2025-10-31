<?php

namespace Database\Seeders;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'employee_no' => 'TC-2024-001',
                'name' => 'Gibb L. Arban',
                'position' => 'Transport Coordinator',
                'phone' => '0992-157-8253',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2024-01-15',
                'photo_consent_given' => true,
                'photo_consent_date' => '2024-01-15',
                'photo_approved_at' => '2024-01-20',
                'photo_expires_at' => '2025-01-19',
                'photo_notes' => 'All documents verified and approved',
            ],
            [
                'employee_no' => 'SA-2024-001',
                'name' => 'Marisol S. Mendoza',
                'position' => 'Service Attendant',
                'phone' => '0992-483-7056',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2024-02-01',
                'photo_consent_given' => true,
                'photo_consent_date' => '2024-02-01',
                'photo_approved_at' => '2024-02-05',
                'photo_expires_at' => '2025-02-04',
                'photo_notes' => 'ID verification completed',
            ],
            [
                'employee_no' => 'SA-2024-002',
                'name' => 'Flordelyn B. Villegas',
                'position' => 'Service Attendant',
                'phone' => '0967-750-2059',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2024-02-15',
                'photo_consent_given' => true,
                'photo_consent_date' => '2024-02-15',
                'photo_approved_at' => '2024-02-20',
                'photo_expires_at' => '2025-02-19',
                'photo_notes' => 'Uniform photo approved',
            ],
            [
                'employee_no' => 'SA-2025-003',
                'name' => 'Joven B. Pedroso',
                'position' => 'Service Attendant',
                'phone' => '0994-792-6351',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2025-01-10',
                'photo_consent_given' => true,
                'photo_consent_date' => '2025-01-10',
                'photo_approved_at' => '2025-01-15',
                'photo_expires_at' => '2026-01-14',
                'photo_notes' => 'Recent hire - all documentation complete',
            ],
            [
                'employee_no' => 'SA-2025-004',
                'name' => 'Rhea Mae M. Benitua',
                'position' => 'Service Attendant',
                'phone' => '0991-059-1558',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2025-01-20',
                'photo_consent_given' => true,
                'photo_consent_date' => '2025-01-20',
                'photo_approved_at' => '2025-01-25',
                'photo_expires_at' => '2026-01-24',
                'photo_notes' => 'Background check completed',
            ],
            [
                'employee_no' => 'SA-2025-005',
                'name' => 'Cameli B. Pedroso',
                'position' => 'Service Attendant',
                'phone' => '0994-203-9588',
                'address' => 'Metro Manila, Philippines',
                'hire_date' => '2025-02-01',
                'photo_consent_given' => true,
                'photo_consent_date' => '2025-02-01',
                'photo_approved_at' => '2025-02-05',
                'photo_expires_at' => '2026-02-04',
                'photo_notes' => 'Training completed successfully',
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create([
                'employee_no' => $employee['employee_no'],
                'name' => $employee['name'],
                'position' => $employee['position'],
                'phone' => $employee['phone'],
                'address' => $employee['address'],
                'hire_date' => $employee['hire_date'],
                'photo_consent_given' => $employee['photo_consent_given'],
                'photo_consent_date' => $employee['photo_consent_date'],
                'photo_approved_at' => $employee['photo_approved_at'],
                'photo_expires_at' => $employee['photo_expires_at'],
                'photo_notes' => $employee['photo_notes'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
