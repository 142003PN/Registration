<?php

namespace Tests\Feature;

use App\Models\Programs;
use App\Models\Students;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_pages_require_an_authenticated_admin(): void
    {
        $this->get(route('admin.students.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.students.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_view_update_and_delete_a_student(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);
        $otherProgram = Programs::create(['name' => 'Information Systems']);

        $this->get(route('admin.students.index'))
            ->assertOk()
            ->assertSee('No students have been added yet.');

        $this->post(route('admin.students.store'), [
            'first_name' => 'Ada',
            'middle_name' => 'Augusta',
            'last_name' => 'Byron',
            'program_id' => $program->id,
        ])->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'Student created successfully.');

        $student = Students::where('first_name', 'Ada')->firstOrFail();

        $this->get(route('admin.students.show', $student))
            ->assertOk()
            ->assertSee('Ada')
            ->assertSee('Augusta')
            ->assertSee('Byron')
            ->assertSee('Computer Science');

        $this->get(route('admin.students.edit', $student))->assertOk();

        $this->put(route('admin.students.update', $student), [
            'first_name' => 'Augusta',
            'middle_name' => 'Ada',
            'last_name' => 'Byron',
            'program_id' => $otherProgram->id,
        ])->assertRedirect(route('admin.students.show', $student))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'first_name' => 'Augusta',
            'middle_name' => 'Ada',
            'last_name' => 'Byron',
            'program_id' => $otherProgram->id,
        ]);

        $this->delete(route('admin.students.destroy', $student))
            ->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    public function test_student_requires_first_and_last_names_within_limits_and_one_existing_programme(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->from(route('admin.students.create'))
            ->post(route('admin.students.store'), [
                'first_name' => str_repeat('A', 16),
                'last_name' => '',
                'program_id' => 999,
            ])
            ->assertRedirect(route('admin.students.create'))
            ->assertSessionHasErrors(['first_name', 'last_name', 'program_id']);
    }

    public function test_middle_name_is_optional(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);

        $this->post(route('admin.students.store'), [
            'first_name' => 'Ada',
            'last_name' => 'Byron',
            'program_id' => $program->id,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('students', [
            'first_name' => 'Ada',
            'middle_name' => null,
            'last_name' => 'Byron',
            'program_id' => $program->id,
        ]);
    }

    public function test_programme_with_assigned_students_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);
        Students::create([
            'first_name' => 'Ada',
            'last_name' => 'Byron',
            'program_id' => $program->id,
        ]);

        $this->delete(route('admin.programs.destroy', $program))
            ->assertRedirect(route('admin.programs.show', $program))
            ->assertSessionHas('error', 'This programme cannot be deleted while students are assigned to it.');

        $this->assertDatabaseHas('programs', ['id' => $program->id]);
    }
}
