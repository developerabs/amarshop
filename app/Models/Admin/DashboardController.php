<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class DashboardController extends Model
{
    public function index()
    {
        return view('admin.dashboard');
    }
}
