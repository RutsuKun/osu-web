<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Admin\Controller;
use App\Models\Store\OrderItem;
use App\Models\Store\Product;
use Request;

class OrderItemsController extends Controller
{
    protected $section = 'storeAdmin';

    public function update($orderId, $orderItemId)
    {
        $item = OrderItem::findOrFail($orderItemId);

        if ($item->order_id !== (int) $orderId) {
            return error_popup('invalid order id for this item.');
        }

        if ($item->order->status !== 'paid') {
            return error_popup("order status {$item->order->status} is invalid.");
        }

        $productId = get_int(Request::input('item.product_id'));
        $product = Product::findOrFail($productId);

        $item->order->switchItems($item, $product);

        return ['message' => "order item {$orderItemId} updated"];
    }
}
