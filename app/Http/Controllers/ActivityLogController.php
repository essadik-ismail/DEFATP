<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the activity logs.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user');

        // Filter by user if specified
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action if specified
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by model type if specified
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activityLogs = $query->latest()->paginate(20)->withQueryString();

        // Get available filters
        $users = User::orderBy('name')->get();
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->filter()->sort();

        return view('activity-logs.index', compact('activityLogs', 'users', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog): View
    {
        $activityLog->load('user');
        
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Display activity logs for a specific user.
     */
    public function userActivity(User $user, Request $request): View
    {
        $query = $user->activityLogs();

        // Filter by action if specified
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->latest()->paginate(20)->withQueryString();
        $actions = $user->activityLogs()->distinct()->pluck('action')->sort();

        return view('activity-logs.user-activity', compact('user', 'activityLogs', 'actions'));
    }

    /**
     * Get activity logs for AJAX requests.
     */
    public function getActivityLogs(Request $request): JsonResponse
    {
        $query = ActivityLog::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->latest()->paginate(15);

        return response()->json([
            'html' => view('activity-logs.partials.activity-logs-table', compact('activityLogs'))->render(),
            'pagination' => view('activity-logs.partials.pagination', compact('activityLogs'))->render(),
        ]);
    }

    /**
     * Export activity logs to CSV.
     */
    public function export(Request $request)
    {
        $query = ActivityLog::with('user');

        // Apply filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->latest()->get();

        $filename = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activityLogs) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Action',
                'Description',
                'Type de modèle',
                'ID du modèle',
                'Adresse IP',
                'URL',
                'Méthode',
                'Date de création'
            ]);

            // Add data
            foreach ($activityLogs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user->name ?? 'N/A',
                    $log->action,
                    $log->description,
                    $log->model_type ?? 'N/A',
                    $log->model_id ?? 'N/A',
                    $log->ip_address ?? 'N/A',
                    $log->url ?? 'N/A',
                    $log->method ?? 'N/A',
                    $log->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get activity statistics for dashboard.
     */
    public function getStatistics(): JsonResponse
    {
        $stats = [
            'total_activities' => ActivityLog::count(),
            'today_activities' => ActivityLog::whereDate('created_at', today())->count(),
            'this_week_activities' => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month_activities' => ActivityLog::whereMonth('created_at', now()->month)->count(),
            'top_actions' => ActivityLog::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
            'top_users' => ActivityLog::selectRaw('user_id, COUNT(*) as count')
                ->with('user:id,name')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(5)
                ->get(),
        ];

        return response()->json($stats);
    }
}
