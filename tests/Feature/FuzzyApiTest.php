<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FuzzyApiTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Simulasikan file JSON di storage/app/fuzzy/db.json
        Storage::fake('local');
        Storage::put('fuzzy/db.json', json_encode([
            'penilaian' => [
                [
                    'id_penilaian' => 'NIL01',
                    'pelatihan_id' => 'PL01',
                    'list_peserta' => ['1', '2'],
                    'list_pelatihan_wajib' => ['PL01'],
                    'nilai' => ['LP001'],
                    'rumus_penilaian' => '',
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString(),
                ]
            ],
            'log_penilaian' => [
                [
                    'id' => 'LP001',
                    'id_penilaian' => 'NIL01',
                    'id_user' => '1',
                    'total_nilai' => 100,
                    'created_at' => now()->toISOString(),
                    'updated_at' => now()->toISOString(),
                ]
            ]
        ]));
    }

    /** @test */
    public function it_returns_all_penilaian()
    {
        $response = $this->getJson('/api/penilaian');
        $response->assertStatus(200)
            ->assertJsonFragment(['id_penilaian' => 'NIL01']);
    }

    /** @test */
    public function it_returns_detail_penilaian()
    {
        $response = $this->getJson('/api/penilaian/NIL01');
        $response->assertStatus(200)
            ->assertJsonFragment(['id_penilaian' => 'NIL01'])
            ->assertJsonFragment(['id' => 'LP001']);
    }

    /** @test */
    public function it_returns_log_by_user()
    {
        $response = $this->getJson('/api/log-penilaian/1');
        $response->assertStatus(200)
            ->assertJsonFragment(['id_user' => '1']);
    }

    /** @test */
    public function it_returns_404_if_penilaian_not_found()
    {
        $response = $this->getJson('/api/penilaian/INVALID');
        $response->assertStatus(404);
    }
}
