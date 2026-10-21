<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const PROGRAM = 'Bachelor of Science in Agribusiness Management and Entrepreneurship';

    private const COURSES = [
        ['BIO111', 'Introduction to Cell and Molecular Biology I'],
        ['BIO112', 'Introduction to Cell and Molecular Biology II'],
        ['CHE111', 'Introduction to Chemistry I'],
        ['CHE112', 'Introduction to Chemistry II'],
        ['CIS121', 'Basic Computer Skills'],
        ['COM122', 'Communication Skills'],
        ['MAT151', 'Foundation Mathematics and Statistics for Sciences I'],
        ['PHY101', 'Foundation Physics I'],
        ['PHY102', 'Foundation Physics II'],
    ];

    public function up(): void
    {
        $now = now();
        DB::transaction(function () use ($now): void {
            DB::table('programs')->updateOrInsert(
                ['name' => self::PROGRAM],
                ['updated_at' => $now, 'created_at' => $now],
            );

            $programId = DB::table('programs')->where('name', self::PROGRAM)->value('id');
            foreach (self::COURSES as [$code, $title]) {
                DB::table('courses')->updateOrInsert(
                    ['program_id' => $programId, 'code' => $code],
                    ['title' => $title, 'updated_at' => $now, 'created_at' => $now],
                );
            }

            DB::table('courses')->where('program_id', $programId)->where('code', 'MAT161')->delete();
        });
    }

    public function down(): void
    {
        $programId = DB::table('programs')->where('name', self::PROGRAM)->value('id');
        if ($programId === null) {
            return;
        }

        DB::table('courses')->where('program_id', $programId)->whereIn('code', array_column(self::COURSES, 0))->delete();
        DB::table('programs')->where('id', $programId)->delete();
    }
};
