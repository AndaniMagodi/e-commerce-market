<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_the_public_storefront(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('storefront.index');
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/admin')->assertRedirect(route('login'));
    }

    public function test_non_admin_is_forbidden_from_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_view_dashboard_statistics_and_shop_link(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)->get('/admin')
            ->assertOk()
            ->assertViewIs('admin.dashboard')
            ->assertViewHas('totalProducts', 0)
            ->assertViewHas('activeProducts', 0)
            ->assertViewHas('outOfStockProducts', 0)
            ->assertViewHas('totalCategories', 0)
            ->assertSee('href="'.route('storefront.index').'"', false);
    }
}
