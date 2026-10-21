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

        if ($programId === null) {
            return;
        }

        DB::table('courses')->updateOrInsert(
            ['program_id' => $programId, 'code' => 'MAT151'],
            [
                'title' => 'Foundation Mathematics and Statistics for Sciences I',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        DB::table('courses')
            ->where('program_id', $programId)
            ->where('code', 'MAT161')
            ->delete();
    }

    public function down(): void
    {
        // No automatic rollback: preserve any registrations made after this change.
    }
};
