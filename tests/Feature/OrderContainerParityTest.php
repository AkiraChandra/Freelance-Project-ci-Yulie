<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\ExportOrder;
use App\Models\ImportOrder;
use App\Models\Order;
use App\Models\OrderContainer;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderContainerParityTest extends TestCase
{
    public function createApplication(): Application
    {
        $app = parent::createApplication();

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
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->string('type');
            $table->date('order_date');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('import_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('import_order_number')->unique();
            $table->date('order_date');
            $table->string('bl_number')->nullable();
            $table->string('product_name')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voy_number')->nullable();
            $table->date('vessel_arrival_date')->nullable();
            $table->string('pib_number')->nullable();
            $table->string('party')->nullable();
            $table->string('port')->nullable();
            $table->date('do_date')->nullable();
            $table->date('detention_deadline')->nullable();
            $table->date('demurrage_date')->nullable();
            $table->date('release_date')->nullable();
            $table->date('container_return_date')->nullable();
            $table->string('trucking_vendor')->nullable();
            $table->text('issue')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('export_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('export_order_number')->unique();
            $table->date('order_date');
            $table->string('shipping_number')->nullable();
            $table->string('do_number')->nullable();
            $table->string('product_name')->nullable();
            $table->string('shipping_line')->nullable();
            $table->string('vessel_name')->nullable();
            $table->string('voy_number')->nullable();
            $table->date('closing_date')->nullable();
            $table->date('detention_deadline')->nullable();
            $table->string('peb_number')->nullable();
            $table->string('depo')->nullable();
            $table->date('pickup_date')->nullable();
            $table->date('return_date')->nullable();
            $table->text('issue')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
        });
        Schema::create('order_containers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('order_type');
            $table->unsignedInteger('container_group');
            $table->string('container_size');
            $table->string('container_number')->nullable();
            $table->string('container_type')->nullable();
            $table->string('vendor')->nullable();
            $table->unsignedBigInteger('combo_with')->nullable();
            $table->unsignedBigInteger('combine_with_container_id')->nullable();
            $table->date('release_date')->nullable();
            $table->date('return_date')->nullable();
            $table->timestamps();
        });
    }

    public function test_create_import_and_export_save_grouped_containers_for_same_customer_without_customer_type_filter(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $this->activeVendor('Vendor Aktif');

        foreach (['import', 'export'] as $type) {
            $response = $this->actingAs($user)->post(route($type.'-orders.store'), array_merge(
                $this->orderRequest($customer),
                ['groups' => $this->containerGroupsPayload()],
            ));

            $response->assertRedirect(route('orders.index'));
            $master = Order::where('type', $type)->latest('id')->firstOrFail();
            $detail = $type === 'import'
                ? ImportOrder::where('order_id', $master->id)->firstOrFail()
                : ExportOrder::where('order_id', $master->id)->firstOrFail();
            $containers = OrderContainer::where('order_id', $master->id)->where('order_type', $type)->orderBy('id')->get();

            $this->assertSame($customer->id, $detail->customer_id);
            $this->assertCount(4, $containers);
            $this->assertSame([1, 1, 2, 2], $containers->pluck('container_group')->all());
            $this->assertSame(["20'", "20'", 'LCL', 'LCL'], $containers->pluck('container_size')->all());
            $this->assertSame(['C-ONE', 'C-TWO', null, null], $containers->pluck('container_number')->all());
            $this->assertSame(['GP', 'GP', null, null], $containers->pluck('container_type')->all());
            $this->assertSame(['Vendor Aktif', 'Vendor Aktif', 'Vendor Aktif', 'Vendor Aktif'], $containers->pluck('vendor')->all());
            $this->assertSame($containers[1]->id, $containers[0]->combo_with);
            $this->assertSame($containers[0]->id, $containers[1]->combo_with);
        }
    }

    public function test_export_update_preserves_container_ids_and_edit_shows_existing_groups_and_dates(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $this->activeVendor('Vendor Aktif');
        $order = $this->createOrder('export', $user, $customer, 'EXP-001');
        $first = $this->container($order->id, 'export', "20'", 'OLD-ONE', 'GP', 'Vendor Aktif', 1);
        $second = $this->container($order->id, 'export', "20'", 'OLD-TWO', 'GP', 'Vendor Aktif', 1);
        $lclFirst = $this->container($order->id, 'export', 'LCL', null, null, 'Vendor Aktif', 2);
        $lclSecond = $this->container($order->id, 'export', 'LCL', null, null, 'Vendor Aktif', 2);
        $first->update(['release_date' => '2026-01-02', 'return_date' => '2026-01-10']);

        $edit = $this->actingAs($user)->get(route('export-orders.edit', $order->exportOrder));
        $edit->assertOk()->assertSee('Container Details')->assertViewHas('existingGroups')->assertViewHas('availableCombineContainers');
        $groups = $edit->viewData('existingGroups');
        $this->assertCount(2, $groups);
        $this->assertSame('OLD-ONE', $groups[0]['containers'][0]['number']);

        $groups = $this->containerGroupsPayload();
        $groups[0]['containers'][0]['number'] = 'OLD-ONE';
        $groups[0]['containers'][0]['combo'] = 'OLD-TWO';
        $groups[0]['containers'][1]['number'] = 'OLD-TWO';
        $groups[0]['containers'][1]['combo'] = 'OLD-ONE';
        $groups[0]['containers'][0]['id'] = $first->id;
        $groups[0]['containers'][0]['release_date'] = '2026-01-03';
        $groups[0]['containers'][0]['return_date'] = '2026-01-11';
        $groups[0]['containers'][1]['id'] = $second->id;
        $groups[1]['ids'] = [$lclFirst->id, $lclSecond->id];
        $request = array_merge($this->orderRequest($customer), [
            'product_name' => 'Edited product',
            'groups' => $groups,
        ]);

        $response = $this->patch(route('export-orders.update', $order->exportOrder), $request);

        $response->assertRedirect(route('orders.index'));
        $this->assertDatabaseHas('order_containers', [
            'id' => $first->id,
            'release_date' => '2026-01-03 00:00:00',
            'return_date' => '2026-01-11 00:00:00',
        ]);
        $this->assertDatabaseHas('order_containers', ['id' => $second->id]);
        $this->assertSame(4, OrderContainer::where('order_id', $order->id)->where('order_type', 'export')->count());
        $this->assertDatabaseHas('export_orders', ['id' => $order->exportOrder->id, 'product_name' => 'Edited product']);
    }

    public function test_update_rejects_foreign_order_container_id(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $this->activeVendor('Vendor Aktif');
        $order = $this->createOrder('export', $user, $customer, 'EXP-OWN');
        $otherOrder = $this->createOrder('export', $user, $customer, 'EXP-OTHER');
        $foreign = $this->container($otherOrder->id, 'export', "20'", 'FOREIGN', 'GP', 'Vendor Aktif', 1);

        $payload = array_merge($this->orderRequest($customer), ['groups' => $this->containerGroupsPayload()]);
        $payload['groups'][0]['containers'][0]['id'] = $foreign->id;

        $this->actingAs($user)->patch(route('export-orders.update', $order->exportOrder), $payload)
            ->assertSessionHasErrors();
        $this->assertSame(1, OrderContainer::where('order_id', $otherOrder->id)->count());
        $this->assertSame(0, OrderContainer::where('order_id', $order->id)->count());
    }

    public function test_invalid_group_quantity_and_missing_vendor_fail_before_order_creation(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $this->activeVendor('Vendor Aktif');

        $quantityMismatch = $this->containerGroupsPayload();
        $quantityMismatch[0]['quantity'] = 3;
        $this->actingAs($user)->post(route('import-orders.store'), array_merge(
            $this->orderRequest($customer), ['groups' => $quantityMismatch],
        ))->assertSessionHasErrors();

        $missingVendor = $this->containerGroupsPayload();
        $missingVendor[1]['lcl_vendor'] = '';
        $this->post(route('export-orders.store'), array_merge(
            $this->orderRequest($customer), ['groups' => $missingVendor],
        ))->assertSessionHasErrors();

        $this->assertSame(0, Order::count());
        $this->assertSame(0, ImportOrder::count());
        $this->assertSame(0, ExportOrder::count());
    }

    public function test_cross_order_combine_accepts_only_matching_on_going_container_and_rejects_stale_target(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $otherCustomer = Customer::create(['customer_code' => '10002', 'customer_name' => 'Other Customer']);
        $this->activeVendor('Vendor Aktif');
        $ongoing = $this->createOrder('import', $user, $otherCustomer, 'IMP-SOURCE', 'on going');
        $available = $this->container($ongoing->id, 'import', "20'", 'SOURCE-01', 'GP', 'Vendor Aktif', 1);
        $completed = $this->createOrder('import', $user, $otherCustomer, 'IMP-COMPLETE', 'completed');
        $stale = $this->container($completed->id, 'import', "20'", 'SOURCE-02', 'GP', 'Vendor Aktif', 1);

        $groups = $this->containerGroupsPayload();
        $groups[0]['containers'][0]['combine'] = $available->id;
        $created = $this->actingAs($user)->post(route('export-orders.store'), array_merge(
            $this->orderRequest($customer), ['groups' => $groups],
        ));
        $created->assertRedirect(route('orders.index'));
        $target = OrderContainer::where('order_type', 'export')->where('container_number', 'C-ONE')->firstOrFail();
        $this->assertSame($available->id, $target->combine_with_container_id);

        $groups[0]['containers'][0]['combine'] = $stale->id;
        $this->post(route('export-orders.store'), array_merge(
            $this->orderRequest($customer), ['groups' => $groups],
        ))->assertSessionHasErrors();
        $this->assertSame(1, ExportOrder::count());
        $this->assertSame(1, Order::where('type', 'export')->count());
        $this->assertNotNull($stale->fresh());
    }

    public function test_import_and_export_create_edit_pages_render_container_groups_without_legacy_fields(): void
    {
        $user = $this->staff();
        $customer = $this->customer();
        $this->activeVendor('Vendor Aktif');
        $import = $this->createOrder('import', $user, $customer, 'IMP-VIEW');
        $export = $this->createOrder('export', $user, $customer, 'EXP-VIEW');

        foreach ([
            route('import-orders.create'),
            route('export-orders.create'),
            route('import-orders.edit', $import->importOrder),
            route('export-orders.edit', $export->exportOrder),
        ] as $url) {
            $response = $this->actingAs($user)->get($url)->assertOk()->assertSee('Container Details');
            $this->assertStringNotContainsString('name="party"', $response->getContent());
            $this->assertStringNotContainsString('name="container_number"', $response->getContent());
        }
    }

    private function staff(): User
    {
        $role = Role::findOrCreate('staff', 'web');
        $user = User::factory()->create(['role_id' => $role->id, 'status' => 'active']);
        $user->assignRole($role);

        return $user;
    }

    private function customer(): Customer
    {
        return Customer::create([
            'customer_code' => '10001',
            'customer_name' => 'Customer Same',
        ]);
    }

    private function activeVendor(string $name): Vendor
    {
        return Vendor::create(['name' => $name, 'status' => 'active']);
    }

    private function orderRequest(Customer $customer): array
    {
        return [
            'customer_id' => $customer->id,
            'order_date' => '2026-10-17',
            'bl_number' => 'BL-001',
            'product_name' => 'Product',
            'shipping_line' => 'Line',
            'vessel_name' => 'Vessel',
            'voy_number' => 'VOY-1',
            'vessel_arrival_date' => '2026-10-20',
            'do_number' => 'DO-001',
            'closing_date' => '2026-10-21',
            'status' => 'on going',
        ];
    }

    private function containerGroupsPayload(): array
    {
        return [
            [
                'size' => "20'",
                'quantity' => 2,
                'containers' => [
                    ['number' => 'C-ONE', 'type' => 'GP', 'vendor' => 'Vendor Aktif', 'combo' => 'C-TWO', 'combine' => ''],
                    ['number' => 'C-TWO', 'type' => 'GP', 'vendor' => 'Vendor Aktif', 'combo' => 'C-ONE', 'combine' => ''],
                ],
            ],
            ['size' => 'LCL', 'quantity' => 2, 'lcl_vendor' => 'Vendor Aktif', 'containers' => []],
        ];
    }

    private function createOrder(string $type, User $user, Customer $customer, string $number, string $status = 'on going'): Order
    {
        $order = Order::create([
            'order_code' => $number,
            'type' => $type,
            'order_date' => '2026-10-17',
            'status' => $status,
            'created_by' => $user->id,
        ]);
        $attributes = [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'order_date' => '2026-10-17',
            'status' => $status,
            'created_by' => $user->id,
        ];

        if ($type === 'import') {
            ImportOrder::create($attributes + [
                'import_order_number' => $number,
                'bl_number' => 'BL-'.$number,
                'product_name' => 'Product',
                'shipping_line' => 'Line',
                'vessel_name' => 'Vessel',
                'voy_number' => 'VOY',
                'vessel_arrival_date' => '2026-10-20',
            ]);
        } else {
            ExportOrder::create($attributes + [
                'export_order_number' => $number,
                'do_number' => 'DO-'.$number,
                'product_name' => 'Product',
                'shipping_line' => 'Line',
                'vessel_name' => 'Vessel',
                'voy_number' => 'VOY',
                'closing_date' => '2026-10-21',
            ]);
        }

        return $order->load($type === 'import' ? 'importOrder' : 'exportOrder');
    }

    private function container(int $orderId, string $orderType, string $size, ?string $number, ?string $type, string $vendor, int $group): OrderContainer
    {
        return OrderContainer::create([
            'order_id' => $orderId,
            'order_type' => $orderType,
            'container_group' => $group,
            'container_size' => $size,
            'container_number' => $number,
            'container_type' => $type,
            'vendor' => $vendor,
        ]);
    }
}
