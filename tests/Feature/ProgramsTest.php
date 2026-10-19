<?php

namespace Tests\Feature;

use App\Models\Programs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function test_programme_pages_require_an_authenticated_admin(): void
    {
        $this->get(route('admin.programs.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.programs.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_view_update_and_delete_a_programme(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->get(route('admin.programs.index'))
            ->assertOk()
            ->assertSee('No programmes have been added yet.');

        $this->post(route('admin.programs.store'), ['name' => 'Computer Science'])
            ->assertSessionHasNoErrors();

        $program = Programs::where('name', 'Computer Science')->firstOrFail();

        $this->get(route('admin.programs.show', $program))
            ->assertOk()
            ->assertSee('Computer Science');

        $this->get(route('admin.programs.edit', $program))->assertOk();

        $this->put(route('admin.programs.update', $program), ['name' => 'Information Systems'])
            ->assertRedirect(route('admin.programs.show', $program))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('programs', ['id' => $program->id, 'name' => 'Information Systems']);

        $this->delete(route('admin.programs.destroy', $program))
            ->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
    }

    public function test_programme_names_are_required_unique_and_limited_to_50_characters(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Programs::create(['name' => 'Existing Programme']);

        $this->from(route('admin.programs.create'))
            ->post(route('admin.programs.store'), ['name' => 'Existing Programme'])
            ->assertRedirect(route('admin.programs.create'))
            ->assertSessionHasErrors('name');

        $this->from(route('admin.programs.create'))
            ->post(route('admin.programs.store'), ['name' => str_repeat('a', 51)])
            ->assertRedirect(route('admin.programs.create'))
            ->assertSessionHasErrors('name');
    }

    public function test_admin_can_import_programmes_from_an_excel_file_and_duplicates_are_skipped(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Programs::create(['name' => 'Existing Programme']);

        $this->post(route('admin.programs.import'), [
            'file' => $this->excelUpload([
                ['Programme Name'],
                ['Computer Science'],
                ['Existing Programme'],
                ['Computer Science'],
                ['computer science'],
                ['Information Systems'],
            ]),
        ])
            ->assertRedirect(route('admin.programs.index'))
            ->assertSessionHas('success', 'Imported 2 programme(s); skipped 3 duplicate(s).');

        $this->assertDatabaseHas('programs', ['name' => 'Computer Science']);
        $this->assertDatabaseHas('programs', ['name' => 'Information Systems']);
        $this->assertDatabaseCount('programs', 3);
    }

    public function test_import_rejects_overlong_names_without_importing_any_rows(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->from(route('admin.programs.index'))
            ->post(route('admin.programs.import'), [
                'file' => $this->excelUpload([
                    ['Programme Name'],
                    ['Valid Programme'],
                    [str_repeat('a', 51)],
                ]),
            ])
            ->assertRedirect(route('admin.programs.index'))
            ->assertSessionHasErrors('file');

        $this->assertDatabaseMissing('programs', ['name' => 'Valid Programme']);
        $this->assertDatabaseCount('programs', 0);
    }

    private function excelUpload(array $rows): UploadedFile
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($rows, null, 'A1');

        $path = tempnam(sys_get_temp_dir(), 'programmes');
        try {
            (new Xlsx($spreadsheet))->save($path);

            return UploadedFile::fake()->createWithContent(
                'programmes.xlsx',
                file_get_contents($path)
            );
        } finally {
            $spreadsheet->disconnectWorksheets();
            unlink($path);
        }
    }
}
