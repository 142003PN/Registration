<?php

namespace Tests\Feature;

use App\Models\Courses;
use App\Models\Programs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class CoursesTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_pages_require_an_authenticated_admin(): void
    {
        $this->get(route('admin.courses.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.courses.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_view_update_and_delete_a_course(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);

        $this->get(route('admin.courses.index'))
            ->assertOk()
            ->assertSee('No courses have been added yet.');

        $this->post(route('admin.courses.store'), [
            'code' => 'CS101',
            'title' => 'Introduction to Computing',
            'program_id' => $program->id,
        ])->assertSessionHasNoErrors()
            ->assertSessionHas('success', 'Course created successfully.');

        $course = Courses::where('code', 'CS101')->firstOrFail();

        $this->get(route('admin.courses.show', $course))
            ->assertOk()
            ->assertSee('Swal.fire', false)
            ->assertSee('Course created successfully.')
            ->assertSee('CS101')
            ->assertSee('Introduction to Computing')
            ->assertSee('Computer Science');

        $this->get(route('admin.courses.edit', $course))->assertOk();

        $this->put(route('admin.courses.update', $course), [
            'code' => 'CS102',
            'title' => 'Programming Fundamentals',
            'program_id' => $program->id,
        ])->assertRedirect(route('admin.courses.show', $course))
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'code' => 'CS102',
            'title' => 'Programming Fundamentals',
            'program_id' => $program->id,
        ]);

        $this->delete(route('admin.courses.destroy', $course))
            ->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_course_code_must_be_unique_within_a_programme(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);
        $otherProgram = Programs::create(['name' => 'Mathematics']);
        Courses::create([
            'code' => 'CS101',
            'title' => 'Introduction to Computing',
            'program_id' => $program->id,
        ]);

        $this->from(route('admin.courses.create'))
            ->post(route('admin.courses.store'), [
                'code' => 'CS101',
                'title' => 'Another course',
                'program_id' => $program->id,
            ])
            ->assertRedirect(route('admin.courses.create'))
            ->assertSessionHasErrors('code');

        $this->post(route('admin.courses.store'), [
            'code' => 'CS101',
            'title' => 'Introduction to Mathematics',
            'program_id' => $otherProgram->id,
        ])->assertSessionHasNoErrors();
    }

    public function test_database_enforces_course_code_uniqueness_per_programme(): void
    {
        $program = Programs::create(['name' => 'Computer Science']);
        Courses::create([
            'code' => 'CS101',
            'title' => 'Introduction to Computing',
            'program_id' => $program->id,
        ]);

        $this->expectException(QueryException::class);

        Courses::create([
            'code' => 'CS101',
            'title' => 'Another course',
            'program_id' => $program->id,
        ]);
    }

    public function test_course_code_uniqueness_ignores_the_current_course_on_update(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $program = Programs::create(['name' => 'Computer Science']);
        $course = Courses::create([
            'code' => 'CS101',
            'title' => 'Introduction to Computing',
            'program_id' => $program->id,
        ]);

        $this->put(route('admin.courses.update', $course), [
            'code' => 'CS101',
            'title' => 'Updated course title',
            'program_id' => $program->id,
        ])->assertRedirect(route('admin.courses.show', $course))
            ->assertSessionHasNoErrors();
    }

    public function test_course_requires_valid_details_and_a_programme(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        $this->from(route('admin.courses.create'))
            ->post(route('admin.courses.store'), [
                'code' => str_repeat('C', 11),
                'title' => str_repeat('T', 51),
                'program_id' => 999,
            ])
            ->assertRedirect(route('admin.courses.create'))
            ->assertSessionHasErrors(['code', 'title', 'program_id']);
    }
}
