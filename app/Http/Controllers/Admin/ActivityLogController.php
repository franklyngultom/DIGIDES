<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    /**
     * Display the audit trail activity logs.
     */
    public function index(Request $request): View
    {
        $logName = $request->query('log_name');
        $causerId = $request->query('causer_id');
        $event = $request->query('event');
        $search = $request->query('search');

        $query = Activity::with('causer', 'subject')->latest();

        if ($logName) {
            $query->where('log_name', $logName);
        }

        if ($causerId) {
            $query->where('causer_id', $causerId);
        }

        if ($event) {
            $query->where('event', $event);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        $activities = $query->paginate(15)->withQueryString();
        $users = User::all();
        $logNames = Activity::select('log_name')->distinct()->pluck('log_name');

        return view('admin.audit.index', compact('activities', 'users', 'logNames', 'logName', 'causerId', 'event', 'search'));
    }
}
