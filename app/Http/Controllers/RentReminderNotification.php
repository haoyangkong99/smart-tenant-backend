<?php

namespace App\Http\Controllers;
use App\Models\Tenant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentReminderNotification extends Controller
{

    public function sendRentReminder(Tenant $tenant)
{
    $tenant->notify(new RentReminderNotification($tenant->leases->last()->rent_amount));
}
}
