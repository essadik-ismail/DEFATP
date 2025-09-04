<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Log a user activity.
     */
    public static function log(
        string $action,
        string $description,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $properties = null,
        ?Request $request = null
    ): ?ActivityLog {
        $request = $request ?? request();
        $user = Auth::user();

        if (!$user) {
            return null;
        }

        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
        ]);
    }

    /**
     * Log user login.
     */
    public static function logLogin(User $user, Request $request = null): ?ActivityLog
    {
        return self::log(
            'login',
            "L'utilisateur {$user->name} s'est connecté",
            null,
            null,
            ['user_email' => $user->email],
            $request
        );
    }

    /**
     * Log user logout.
     */
    public static function logLogout(User $user, Request $request = null): ?ActivityLog
    {
        return self::log(
            'logout',
            "L'utilisateur {$user->name} s'est déconnecté",
            null,
            null,
            ['user_email' => $user->email],
            $request
        );
    }

    /**
     * Log model creation.
     */
    public static function logCreate(
        string $modelType,
        int $modelId,
        string $modelName,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'create',
            "Création de {$modelName}",
            $modelType,
            $modelId,
            ['model_name' => $modelName],
            $request
        );
    }

    /**
     * Log model update.
     */
    public static function logUpdate(
        string $modelType,
        int $modelId,
        string $modelName,
        array $changes = [],
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'update',
            "Modification de {$modelName}",
            $modelType,
            $modelId,
            [
                'model_name' => $modelName,
                'changes' => $changes
            ],
            $request
        );
    }

    /**
     * Log model deletion.
     */
    public static function logDelete(
        string $modelType,
        int $modelId,
        string $modelName,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'delete',
            "Suppression de {$modelName}",
            $modelType,
            $modelId,
            ['model_name' => $modelName],
            $request
        );
    }

    /**
     * Log model view.
     */
    public static function logView(
        string $modelType,
        int $modelId,
        string $modelName,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'view',
            "Consultation de {$modelName}",
            $modelType,
            $modelId,
            ['model_name' => $modelName],
            $request
        );
    }

    /**
     * Log export action.
     */
    public static function logExport(
        string $modelType,
        string $format,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'export',
            "Export des {$modelType} au format {$format}",
            $modelType,
            null,
            ['format' => $format],
            $request
        );
    }

    /**
     * Log import action.
     */
    public static function logImport(
        string $modelType,
        string $filename,
        int $count,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'import',
            "Import de {$count} {$modelType} depuis {$filename}",
            $modelType,
            null,
            [
                'filename' => $filename,
                'count' => $count
            ],
            $request
        );
    }

    /**
     * Log status change.
     */
    public static function logStatusChange(
        string $modelType,
        int $modelId,
        string $modelName,
        string $oldStatus,
        string $newStatus,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            'status_change',
            "Changement de statut de {$modelName} de {$oldStatus} à {$newStatus}",
            $modelType,
            $modelId,
            [
                'model_name' => $modelName,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ],
            $request
        );
    }

    /**
     * Log user management actions.
     */
    public static function logUserAction(
        string $action,
        User $targetUser,
        string $description,
        Request $request = null
    ): ?ActivityLog {
        return self::log(
            $action,
            $description,
            User::class,
            $targetUser->id,
            [
                'target_user_id' => $targetUser->id,
                'target_user_name' => $targetUser->name,
                'target_user_email' => $targetUser->email
            ],
            $request
        );
    }
}
