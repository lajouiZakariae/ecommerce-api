<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\History;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\HistoryController
 */
final class HistoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        History::factory()->count(3)->create();

        $response = $this->get(route('histories.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $history = History::factory()->create();

        $response = $this->delete(route('histories.destroy', $history));

        $response->assertNoContent();

        $this->assertModelMissing($history);
    }
}
