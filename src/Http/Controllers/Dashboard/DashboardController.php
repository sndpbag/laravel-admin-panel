<?php

namespace Sndpbag\AdminPanel\Http\Controllers\Dashboard;

use Sndpbag\AdminPanel\Http\Controllers\Controller;
 
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_users' => 2548,
            'total_views' => 45678,
            'total_revenue' => 324500,
            'total_orders' => 1234,
            'users_growth' => 12,
            'views_growth' => 8,
            'revenue_growth' => 23,
            'orders_growth' => 15,
        ];

        $recentActivities = [
            [
                'icon' => '👤',
                'title' => 'New User Registration',
                'description' => 'sndp bag created an account',
                'time' => '2 mins ago',
                'bg_color' => 'gradient-bg',
            ],
            [
                'icon' => '📦',
                'title' => 'New Order',
                'description' => 'Order #12345 is processing',
                'time' => '15 mins ago',
                'bg_color' => 'var(--accent)',
            ],
            [
                'icon' => '💳',
                'title' => 'Payment Completed',
                'description' => 'Rs.5,500 payment received',
                'time' => '1 hour ago',
                'bg_color' => 'var(--secondary)',
            ],
        ];

        return view('admin-panel::dashboard.index', compact('stats', 'recentActivities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(cr $cr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cr $cr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cr $cr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cr $cr)
    {
        //
    }
}
