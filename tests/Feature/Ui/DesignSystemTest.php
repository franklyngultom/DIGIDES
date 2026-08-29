<?php

namespace Tests\Feature\Ui;

use App\Models\DesaProfile;
use App\Models\User;
use Database\Seeders\DesaProfileSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class DesignSystemTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(DesaProfileSeeder::class);
        $this->seed(UserSeeder::class);

        $this->admin = User::where('email', 'admin@desa.id')->first();
    }

    public function test_dashboard_renders_with_custom_ui_components(): void
    {
        $response = $this->actingAs($this->admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText('Working Productivity');
        $response->assertSeeText('Productive Time');
        $response->assertSeeText('Time at Work');
        $response->assertSeeText('Statistik & Ringkasan Sistem');
        $response->assertSeeText('Upcoming Schedule');
        $response->assertSeeText('Pelayanan Surat Keterangan Usaha (SKU)');
        $response->assertSeeText('Jam Operasional Pelayanan');
        $response->assertSeeText('Desa Sukamaju');
    }

    public function test_blade_ui_card_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.card variant="lime">Konten Card Lime</x-ui.card>');
        $this->assertStringContainsString('Konten Card Lime', $rendered);
        $this->assertStringContainsString('bg-gradient-to-br from-[#e2f48f]', $rendered);
    }

    public function test_blade_ui_button_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.button variant="primary" size="md">Simpan Data</x-ui.button>');
        $this->assertStringContainsString('Simpan Data', $rendered);
        $this->assertStringContainsString('bg-[#114443]', $rendered);

        $renderedLink = Blade::render('<x-ui.button href="/dashboard" variant="lime">Dashboard</x-ui.button>');
        $this->assertStringContainsString('href="/dashboard"', $renderedLink);
        $this->assertStringContainsString('bg-[#d4ed31]', $renderedLink);
    }

    public function test_blade_ui_badge_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.badge variant="lime" :dot="true">Aktif</x-ui.badge>');
        $this->assertStringContainsString('Aktif', $rendered);
        $this->assertStringContainsString('bg-[#e2f48f]', $rendered);
    }

    public function test_blade_ui_stat_card_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.stat-card day="Mon" date="18" percentage="86%" label="Productive" productiveTime="5h 12m" timeAtWork="5h 45m" variant="lime" />');
        $this->assertStringContainsString('Mon', $rendered);
        $this->assertStringContainsString('18', $rendered);
        $this->assertStringContainsString('86% Productive', $rendered);
        $this->assertStringContainsString('5h 12m', $rendered);
        $this->assertStringContainsString('5h 45m', $rendered);
    }

    public function test_blade_ui_progress_ring_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.progress-ring :percentage="82" label="Efisiensi" />');
        $this->assertStringContainsString('82%', $rendered);
        $this->assertStringContainsString('Efisiensi', $rendered);
    }

    public function test_blade_ui_modal_preview_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.modal-preview id="doc-modal" title="Pratinjau Dokumen">Konten Modal</x-ui.modal-preview>');
        $this->assertStringContainsString('Pratinjau Dokumen', $rendered);
        $this->assertStringContainsString('backdrop-blur-sm', $rendered);
    }

    public function test_blade_ui_privacy_toggle_component_renders_correctly(): void
    {
        $rendered = Blade::render('<x-ui.privacy-toggle :defaultActive="true" />');
        $this->assertStringContainsString('privacyMode', $rendered);
        $this->assertStringContainsString('Privacy Mode Aktif (NIK Disamarkan)', $rendered);
    }
}
