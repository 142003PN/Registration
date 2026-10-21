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

        if ($programId !== null) {
            DB::table('courses')
                ->where('program_id', $programId)
                ->where('code', 'MAT151')
                ->delete();
        }
    }

    public function down(): void
    {
        // Do not recreate a course after registrations may have been made.
    }
};
