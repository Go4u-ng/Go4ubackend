<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Customer;

class DashController extends Controller
{
    //get dashboard data
    public function dashboard(){
        $order = Order::get();
        $sales = Order::sum('total_price');
        $customers = Customer::get();
        $customers_count = Customer::count();
        $all_oders = Order::count();
        $active_customers = Customer::count();
        $pending_orders = Order::where('status', 'pending')->count();
        $completed_orders = Order::where('status', 'completed')->count();


         $data = [
            'sales' => $sales,
            'customers_count' => $customers_count,
            'all_oders' => $all_oders,
            'active_customers' => $active_customers,
            'pending_orders' => $pending_orders,
            'completed_orders' => $completed_orders,
            'recent_orders' => $order,
         ];

         return response()->json([
            'status' => 'success',
            'data' => $data
         ]);

    }

    //get orders data
    public function orders(){
        $order = Order::get();
        $order_count = Order::count();
        $pending_orders = Order::where('status', 'pending')->count();
        $completed_orders = Order::where('status', 'completed')->count();
        $cancelled_orders = Order::where('status', 'cancelled')->count();
        $return_orders = Order::where('status', 'return')->count();
        $customers = Customer::count();

        $data = [
            'all_orders' => $order_count,
            'pending_orders' => $pending_orders,
            'completed_orders' => $completed_orders,
            'cancelled_orders' => $cancelled_orders,
            'return_orders' => $return_orders,
            'customers' => $customers,
            'orders' => $order,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
            ]);

    }
}
