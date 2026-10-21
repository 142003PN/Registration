<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $programId = DB::table('programs')
            ->where('name', 'Bachelor of Science Non-Quota Stream A')
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
        // Course metadata is restored only if Stream A still lacks MAT151.
        $programId = DB::table('programs')
            ->where('name', 'Bachelor of Science Non-Quota Stream A')
            ->value('id');

        if ($programId === null || DB::table('courses')->where('program_id', $programId)->where('code', 'MAT151')->exists()) {
            return;
        }

        DB::table('courses')->insert([
            'program_id' => $programId,
            'code' => 'MAT151',
            'title' => 'Foundation Mathematics and Statistics for Sciences I',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
