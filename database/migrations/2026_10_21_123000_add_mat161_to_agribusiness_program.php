<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $programId = DB::table('programs')
            ->where('name', 'Bachelor of Science in Agribusiness Management and Entrepreneurship')
            ->value('id');

        if ($programId === null) {
            return;
        }

        DB::table('courses')->updateOrInsert(
            ['program_id' => $programId, 'code' => 'MAT161'],
            [
                'title' => 'Business Mathematics',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }

    public function down(): void
    {
        // Keep this course if registrations have been made against it.
    }
};
