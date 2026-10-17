<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use App\Support\IndonesiaCities;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerCityFeatureTest extends TestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        // Force isolated SQLite before any database access, regardless of .env.
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite.database', ':memory:');
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->createTestSchema();
    }

    private function createTestSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('role_id')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            $table->unique(['name', 'guard_name']);
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });
        Schema::create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });
        Schema::create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('customer_name');
            $table->string('city')->default('MEDAN');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function test_create_and_edit_pages_show_city_dropdown_with_medan_first_and_default(): void
    {
        $user = $this->userWithRole('staff');

        $createResponse = $this->actingAs($user)->get(route('customers.create'));

        $createResponse->assertOk()->assertViewIs('customers.create');
        $this->assertSame('MEDAN', $createResponse->viewData('cityOptions')[0]['value']);
        $this->assertCount(514, $createResponse->viewData('cityOptions'));
        $this->assertDropdownHasCity($createResponse->getContent(), 'MEDAN');

        $customer = $this->customer(['city' => 'SURABAYA']);
        $editResponse = $this->get(route('customers.edit', $customer));

        $editResponse->assertOk()->assertViewIs('customers.edit');
        $this->assertSame('MEDAN', $editResponse->viewData('cityOptions')[0]['value']);
        $this->assertDropdownHasCity($editResponse->getContent(), 'SURABAYA');
    }

    public function test_city_catalog_has_514_records_with_medan_first_and_key_cities(): void
    {
        $options = IndonesiaCities::options();

        $this->assertCount(514, $options);
        $this->assertSame('MEDAN', $options[0]['value']);
        foreach (['JAKARTA PUSAT', 'BANDUNG', 'SURABAYA', 'MAKASSAR'] as $city) {
            $this->assertContains($city, array_column($options, 'value'));
        }
    }

    public function test_store_normalizes_city_and_uses_medan_for_blank_city(): void
    {
        $user = $this->userWithRole('staff');

        $this->actingAs($user)->post(route('customers.store'), [
            'customer_name' => 'Customer Surabaya',
            'type' => 'impor',
            'city' => ' surabaya ',
        ])->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'customer_name' => 'Customer Surabaya',
            'city' => 'SURABAYA',
            'created_by' => $user->id,
        ]);

        $this->post(route('customers.store'), [
            'customer_name' => 'Customer Default',
            'type' => 'ekspor',
            'city' => '   ',
        ])->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'customer_name' => 'Customer Default',
            'city' => 'MEDAN',
            'created_by' => $user->id,
        ]);
    }

    public function test_update_saves_normalized_city(): void
    {
        $user = $this->userWithRole('staff');
        $customer = $this->customer(['customer_name' => 'Customer Lama']);

        $this->actingAs($user)->patch(route('customers.update', $customer), [
            'customer_name' => 'Customer Lama',
            'type' => 'impor',
            'city' => 'surabaya',
        ])->assertRedirect(route('customers.index'));

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'city' => 'SURABAYA',
        ]);
    }

    public function test_index_shows_city_column_and_customer_city(): void
    {
        $this->actingAs($this->userWithRole('staff'));
        $this->customer([
            'customer_code' => '12345',
            'customer_name' => 'Customer Bandung',
            'city' => 'BANDUNG',
        ]);

        $this->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Kota')
            ->assertSee('BANDUNG');
    }

    private function assertDropdownHasCity(string $html, string $selectedCity): void
    {
        $this->assertMatchesRegularExpression('/<select\\b[^>]*\\bname="city"[^>]*>/', $html);
        $this->assertMatchesRegularExpression(
            '/<option\\b(?=[^>]*\\bvalue="'.preg_quote($selectedCity, '/').'"(?=\\s|>))(?=[^>]*\\bselected)[^>]*>\\s*'.preg_quote($selectedCity, '/').'\\s*<\\/option>/s',
            $html,
        );
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
        $user->assignRole($role);

        return $user;
    }

    private function customer(array $attributes = []): Customer
    {
        static $nextCode = 10000;

        return Customer::create(array_merge([
            'customer_code' => (string) $nextCode++,
            'customer_name' => 'Customer Test',
            'type' => 'impor',
            'city' => 'MEDAN',
        ], $attributes));
    }
}
