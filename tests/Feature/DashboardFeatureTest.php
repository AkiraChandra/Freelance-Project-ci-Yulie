<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ExportOrder;
use App\Models\ImportOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardFeatureTest extends TestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();

        // Force an isolated database before RefreshDatabase runs, regardless of .env.
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
        Carbon::setTestNow(Carbon::parse('2026-10-17 12:00:00'));
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
            $table->string('type');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
        Schema::create('import_orders', function (Blueprint $table) {
            $table->id();
            $table->string('import_order_number')->unique();
            $table->date('order_date');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('detention_deadline')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('export_orders', function (Blueprint $table) {
            $table->id();
            $table->string('export_order_number')->unique();
            $table->date('order_date');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->date('closing_date')->nullable();
            $table->date('detention_deadline')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });

        // Referenced only by the existing dashboard summary counters.
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unique();
            $table->string('company_name');
            $table->timestamps();
        });
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('order_type');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('operational_staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('operational_staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operational_staff_id');
            $table->string('order_type');
            $table->unsignedBigInteger('order_id');
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('assigned_by');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_staff_gets_real_customer_values_raw_statuses_and_correct_edit_links(): void
    {
        $user = $this->userWithRole('staff');
        $customer = Customer::create([
            'customer_code' => 'C001',
            'customer_name' => 'PT Customer Satu',
            'type' => 'impor',
        ]);
        $import = $this->importOrder($user, 'IMP-001', [
            'customer_id' => $customer->id,
            'status' => 'completed',
            'created_at' => now()->subHours(2),
        ]);
        $export = $this->exportOrder($user, 'EXP-001', [
            'status' => 'cancelled',
            'created_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk()->assertViewIs('dashboard');
        $this->assertSame([
            [
                'number' => 'EXP-001',
                'customer' => 'Tanpa customer',
                'type' => 'Ekspor',
                'status' => 'cancelled',
                'edit_url' => route('export-orders.edit', $export),
            ],
            [
                'number' => 'IMP-001',
                'customer' => 'PT Customer Satu',
                'type' => 'Impor',
                'status' => 'completed',
                'edit_url' => route('import-orders.edit', $import),
            ],
        ], $response->viewData('recentOrders')->all());
    }

    public function test_recent_orders_merge_types_sort_by_creation_and_limit_to_three(): void
    {
        $user = $this->userWithRole('staff');
        foreach (range(1, 5) as $index) {
            $this->importOrder($user, 'IMP-'.$index, ['created_at' => now()->subHours(10 - $index)]);
            $this->exportOrder($user, 'EXP-'.$index, ['created_at' => now()->subHours(9 - $index)->addMinute()]);
        }

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $this->assertSame(['EXP-5', 'EXP-4', 'IMP-5'], $response->viewData('recentOrders')->pluck('number')->all());
    }

    public function test_recent_orders_use_descending_id_when_creation_dates_tie(): void
    {
        $user = $this->userWithRole('staff');
        foreach (range(1, 5) as $index) {
            $this->importOrder($user, 'IMP-'.$index, ['created_at' => now()]);
        }

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $this->assertSame(['IMP-5', 'IMP-4', 'IMP-3'], $response->viewData('recentOrders')->pluck('number')->all());
    }

    public function test_deadlines_merge_types_sort_ascending_and_limit_to_three(): void
    {
        $user = $this->userWithRole('staff');
        $import = $this->importOrder($user, 'IMP-TODAY', ['detention_deadline' => Carbon::today()]);
        $export = $this->exportOrder($user, 'EXP-NEXT', ['closing_date' => Carbon::today()->addDay()]);
        $this->importOrder($user, 'IMP-THIRD', ['detention_deadline' => Carbon::today()->addDays(2)]);
        $this->exportOrder($user, 'EXP-FOURTH', ['closing_date' => Carbon::today()->addDays(3)]);

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $deadlines = $response->viewData('upcomingDeadlines');

        $this->assertSame(['IMP-TODAY', 'EXP-NEXT', 'IMP-THIRD'], $deadlines->pluck('number')->all());
        $this->assertSame('Batas Detention', $deadlines[0]['label']);
        $this->assertSame(route('import-orders.edit', $import), $deadlines[0]['edit_url']);
        $this->assertInstanceOf(Carbon::class, $deadlines[0]['date']);
        $this->assertTrue($deadlines[0]['date']->isSameDay(Carbon::today()));
        $this->assertSame('Closing Ekspor', $deadlines[1]['label']);
        $this->assertSame(route('export-orders.edit', $export), $deadlines[1]['edit_url']);
    }

    public function test_deadlines_include_day_seven_but_exclude_past_null_far_and_non_ongoing_orders(): void
    {
        $user = $this->userWithRole('staff');
        foreach (['import', 'export'] as $type) {
            $create = $type === 'import' ? 'importOrder' : 'exportOrder';
            $dateKey = $type === 'import' ? 'detention_deadline' : 'closing_date';
            $this->{$create}($user, $type.'-DAY7', [$dateKey => Carbon::today()->addDays(7)]);
            $this->{$create}($user, $type.'-PAST', [$dateKey => Carbon::yesterday()]);
            $this->{$create}($user, $type.'-FAR', [$dateKey => Carbon::today()->addDays(8)]);
            $this->{$create}($user, $type.'-NULL', [$dateKey => null]);
            foreach (['completed', 'cancelled'] as $status) {
                $this->{$create}($user, $type.'-'.$status, [$dateKey => Carbon::today(), 'status' => $status]);
            }
        }
        // Export detention is not an export closing deadline.
        $this->exportOrder($user, 'EXP-DETENTION-ONLY', ['detention_deadline' => Carbon::today()]);

        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();

        $this->assertSame(['import-DAY7', 'export-DAY7'], $response->viewData('upcomingDeadlines')->pluck('number')->all());
    }

    public function test_staff_dashboard_is_safe_with_empty_order_tables(): void
    {
        $response = $this->actingAs($this->userWithRole('staff'))->get(route('dashboard'));

        $response->assertOk()->assertViewIs('dashboard');
        foreach (['recentOrders', 'upcomingDeadlines'] as $key) {
            $this->assertInstanceOf(Collection::class, $response->viewData($key));
            $this->assertCount(0, $response->viewData($key));
        }
    }

    public function test_owner_and_accounting_do_not_receive_staff_only_data(): void
    {
        foreach (['owner', 'staff-accounting'] as $role) {
            $user = $this->userWithRole($role);
            $this->importOrder($user, $role.'-IMP', ['detention_deadline' => Carbon::today()]);

            $this->actingAs($user)->get(route('dashboard'))
                ->assertOk()
                ->assertViewIs('dashboard')
                ->assertViewMissing('recentOrders')
                ->assertViewMissing('upcomingDeadlines');
        }
    }

    public function test_pending_user_still_cannot_access_dashboard(): void
    {
        $user = $this->userWithRole('staff');
        $user->update(['status' => 'pending']);

        $this->actingAs($user)->get(route('dashboard'))->assertRedirect('/login');
    }

    public function test_staff_sidebar_shows_orders_and_customers_without_companies_or_privileged_links(): void
    {
        $sidebar = $this->dashboardSidebar($this->userWithRole('staff'));

        foreach (['dashboard', 'orders.index', 'customers.index'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $sidebar);
        }
        foreach (['companies.index', 'vendor.register', 'operational-staff.index', 'users.manage', 'owner-assignments.index', 'invoices.index', 'staff-assignments.index'] as $route) {
            $this->assertStringNotContainsString('href="'.route($route).'"', $sidebar);
        }
    }

    public function test_owner_sidebar_shows_management_and_invoices_without_staff_master_data_links(): void
    {
        // Inspect only the sidebar: owner dashboard cards can still link to companies.
        $sidebar = $this->dashboardSidebar($this->userWithRole('owner'));

        foreach (['dashboard', 'orders.index', 'vendor.register', 'operational-staff.index', 'users.manage', 'owner-assignments.index', 'invoices.index'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $sidebar);
        }
        foreach (['customers.index', 'companies.index', 'staff-assignments.index'] as $route) {
            $this->assertStringNotContainsString('href="'.route($route).'"', $sidebar);
        }
    }

    public function test_accounting_navigation_shows_allowed_links_without_order_creation_or_owner_links(): void
    {
        $this->actingAs($this->userWithRole('staff-accounting'));
        $navigation = (string) $this->view('layouts.navigation');

        foreach (['dashboard', 'orders.index', 'customers.index', 'staff-assignments.index', 'invoices.index'] as $route) {
            $this->assertStringContainsString('href="'.route($route).'"', $navigation);
        }
        foreach (['companies.index', 'orders.select-type', 'import-orders.create', 'export-orders.create', 'vendor.register', 'operational-staff.index', 'users.manage', 'owner-assignments.index'] as $route) {
            $this->assertStringNotContainsString('href="'.route($route).'"', $navigation);
        }
    }

    public function test_navigation_uses_authenticated_name_initials_role_and_current_indonesian_date(): void
    {
        foreach (['staff' => 'Staff', 'owner' => 'Owner', 'staff-accounting' => 'Staff Accounting'] as $role => $label) {
            $user = $this->userWithRole($role);
            $user->update(['name' => 'Nadia Putri Sari', 'email' => $role.'@example.test']);
            $this->actingAs($user);

            foreach (['2026-10-17 12:00:00' => 'Sabtu, 17 Oktober 2026', '2026-11-18 12:00:00' => 'Rabu, 18 November 2026'] as $now => $date) {
                Carbon::setTestNow(Carbon::parse($now));
                $navigation = (string) $this->view('layouts.navigation');

                foreach (['aside', 'header'] as $element) {
                    $this->assertSame(1, preg_match('/<'.$element.'\b[^>]*>.*?<\/'.$element.'>/s', $navigation, $matches));
                    $this->assertStringContainsString('Nadia Putri Sari', $matches[0]);
                    $this->assertMatchesRegularExpression('/>\s*NP\s*<\/span>/', $matches[0]);
                    $this->assertStringContainsString($user->email, $matches[0]);
                    $this->assertStringContainsString('>'.$label.'<', $matches[0]);
                }
                $this->assertStringContainsString($date, $navigation);
                $this->assertStringNotContainsString('John Doe', $navigation);
            }
        }
    }

    public function test_sidebar_and_header_logout_forms_use_post_and_session_csrf_token_for_each_role(): void
    {
        foreach (['staff', 'owner', 'staff-accounting'] as $role) {
            $this->actingAs($this->userWithRole($role));
            $this->withSession(['_token' => 'navigation-csrf-'.$role]);
            $navigation = (string) $this->view('layouts.navigation');

            $this->assertSame(2, preg_match_all('/<form\b(?=[^>]*\baction="'.preg_quote(route('logout'), '/').'")[^>]*>.*?<\/form>/s', $navigation, $forms));
            foreach ($forms[0] as $form) {
                $this->assertMatchesRegularExpression('/<form\b[^>]*\bmethod="POST"/i', $form);
                $this->assertStringContainsString('<input type="hidden" name="_token" value="navigation-csrf-'.$role.'"', $form);
                $this->assertMatchesRegularExpression('/<button\b[^>]*\btype="submit"/', $form);
            }
            $this->assertStringNotContainsString('href="'.route('logout').'"', $navigation);
        }
    }

    private function dashboardSidebar(User $user): string
    {
        $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
        $this->assertSame(1, preg_match('/<aside\b[^>]*\bid="app-sidebar"[^>]*>.*?<\/aside>/s', $response->getContent(), $matches));

        return $matches[0];
    }

    private function userWithRole(string $roleName): User
    {
        $role = Role::findOrCreate($roleName, 'web');
        $user = User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
        $user->assignRole($role);

        return $user;
    }

    private function importOrder(User $user, string $number, array $attributes = []): ImportOrder
    {
        return ImportOrder::forceCreate(array_merge([
            'import_order_number' => $number,
            'order_date' => Carbon::today(),
            'status' => 'on going',
            'created_by' => $user->id,
        ], $attributes));
    }

    private function exportOrder(User $user, string $number, array $attributes = []): ExportOrder
    {
        return ExportOrder::forceCreate(array_merge([
            'export_order_number' => $number,
            'order_date' => Carbon::today(),
            'status' => 'on going',
            'created_by' => $user->id,
        ], $attributes));
    }
}
