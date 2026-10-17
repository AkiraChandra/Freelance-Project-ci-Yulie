<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderContainer;
use App\Models\ImportOrder;
use App\Models\ExportOrder;
use Illuminate\Http\Request;

class ContainerApiController extends Controller
{
    /**
     * Get available containers from other orders for Combine feature
     * Rules:
     * - Different order
     * - Same order type (import/export)
     * - Same customer
     * - Order status is "on going" (not completed/cancelled)
     * - Same container size, type, vendor, and container number
     */
    public function availableForCombine(Request $request)
    {
        $currentOrderId = $request->orderId;
        $orderType = $request->orderType ?? 'import';

        // Get the current order to find matching criteria
        $currentOrder = null;
        $currentCustomerId = null;
        $currentContainers = [];

        if ($currentOrderId && $currentOrderId !== 'null') {
            $currentOrder = Order::find($currentOrderId);
            if ($currentOrder) {
                $currentCustomerId = $currentOrder->customer_id;

                $currentContainers = OrderContainer::where('order_id', $currentOrderId)
                    ->where('order_type', $orderType)
                    ->whereNotNull('container_number')
                    ->where('container_number', '!=', '')
                    ->get();
            }
        }

        // Get all on-going orders of the same type, same customer, excluding current order
        $query = Order::where('status', 'on going')
            ->where('type', $orderType)
            ->where('id', '!=', $currentOrderId)
            ->where('customer_id', $currentCustomerId); // Same customer constraint

        $orders = $query->with(['containers' => function ($q) {
            $q->whereNotNull('container_number')
              ->where('container_number', '!=', '');
        }])->get();

        $result = [];

        foreach ($orders as $order) {
            // Get order number
            $orderNumber = '';
            if ($orderType === 'import') {
                $importOrder = ImportOrder::where('order_id', $order->id)->first();
                $orderNumber = $importOrder ? $importOrder->import_order_number : $order->order_code;
            } else {
                $exportOrder = ExportOrder::where('order_id', $order->id)->first();
                $orderNumber = $exportOrder ? $exportOrder->export_order_number : $order->order_code;
            }

            $matchingContainers = [];

            foreach ($order->containers as $container) {
                // Skip containers without number
                if (empty($container->container_number)) {
                    continue;
                }

                // Check if this container can be combined with any of the current order's containers
                $canCombine = false;

                if ($currentContainers->isEmpty()) {
                    // No current containers - show all from other orders (with same customer)
                    $canCombine = true;
                } else {
                    // Check if container matches criteria from current order's containers
                    foreach ($currentContainers as $current) {
                        if (
                            $container->container_size === $current->container_size &&
                            $container->container_type === $current->container_type &&
                            $container->vendor === $current->vendor &&
                            $container->container_number === $current->container_number
                        ) {
                            // Check if this container is not already combined
                            if (empty($container->combine_with_container_id)) {
                                $canCombine = true;
                                break;
                            }
                        }
                    }
                }

                if ($canCombine) {
                    $matchingContainers[] = [
                        'id' => $container->id,
                        'container_number' => $container->container_number,
                        'container_type' => $container->container_type,
                        'vendor' => $container->vendor,
                        'container_size' => $container->container_size,
                    ];
                }
            }

            if (!empty($matchingContainers)) {
                $result[] = [
                    'order_code' => $orderNumber,
                    'order_id' => $order->id,
                    'containers' => $matchingContainers,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'orders' => $result,
        ]);
    }

    /**
     * Check if a container can be combo'd with another
     */
    public function checkCombo(Request $request)
    {
        $sourceNumber = $request->source_number;
        $targetNumber = $request->target_number;
        $orderId = $request->order_id;
        $orderType = $request->order_type;

        $sourceContainer = OrderContainer::where('order_id', $orderId)
            ->where('order_type', $orderType)
            ->where('container_number', $sourceNumber)
            ->first();

        $targetContainer = OrderContainer::where('order_id', $orderId)
            ->where('order_type', $orderType)
            ->where('container_number', $targetNumber)
            ->first();

        if (!$sourceContainer || !$targetContainer) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan',
            ]);
        }

        // Validation: same order (already implied), same size, same type, same vendor
        if (
            $sourceContainer->container_size !== $targetContainer->container_size ||
            $sourceContainer->container_type !== $targetContainer->container_type ||
            $sourceContainer->vendor !== $targetContainer->vendor
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak memenuhi criteria combo (size/type/vendor harus sama)',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Combo valid',
        ]);
    }

    /**
     * Check if a container can be combined with another from different order
     * Rules:
     * - Different order
     * - Same customer
     * - Same order type
     * - Order status is "on going"
     * - Same container size, type, vendor, and container number
     */
    public function checkCombine(Request $request)
    {
        $sourceOrderId = $request->source_order_id;
        $sourceNumber = $request->source_number;
        $targetOrderId = $request->target_order_id;
        $targetNumber = $request->target_number;
        $orderType = $request->order_type;

        $sourceOrder = Order::find($sourceOrderId);
        $targetOrder = Order::find($targetOrderId);

        if (!$sourceOrder || !$targetOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak ditemukan',
            ]);
        }

        // Validation: Different order
        if ($sourceOrderId === $targetOrderId) {
            return response()->json([
                'success' => false,
                'message' => 'Combine harus dengan order yang berbeda',
            ]);
        }

        // Validation: Same customer
        if ($sourceOrder->customer_id !== $targetOrder->customer_id) {
            return response()->json([
                'success' => false,
                'message' => 'Combine harus dengan customer yang sama',
            ]);
        }

        // Validation: Order status is "on going"
        if ($sourceOrder->status !== 'on going' || $targetOrder->status !== 'on going') {
            return response()->json([
                'success' => false,
                'message' => 'Combine hanya bisa dengan order yang berstatus On Going',
            ]);
        }

        $sourceContainer = OrderContainer::where('order_id', $sourceOrderId)
            ->where('order_type', $orderType)
            ->where('container_number', $sourceNumber)
            ->first();

        $targetContainer = OrderContainer::where('order_id', $targetOrderId)
            ->where('order_type', $orderType)
            ->where('container_number', $targetNumber)
            ->first();

        if (!$sourceContainer || !$targetContainer) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak ditemukan',
            ]);
        }

        // Validation: same size, type, vendor, AND container number
        if (
            $sourceContainer->container_size !== $targetContainer->container_size ||
            $sourceContainer->container_type !== $targetContainer->container_type ||
            $sourceContainer->vendor !== $targetContainer->vendor ||
            $sourceContainer->container_number !== $targetContainer->container_number
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Container tidak memenuhi criteria combine (size/type/vendor/no container harus sama)',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Combine valid',
            'target_container_id' => $targetContainer->id,
        ]);
    }
}
