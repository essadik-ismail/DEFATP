<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AppNotification;

try {
    // Vérifier la structure de la table
    $columns = DB::select('DESCRIBE notifications');
    echo "=== Structure de la table notifications ===\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} | {$column->Type} | {$column->Null} | {$column->Key} | {$column->Default}\n";
    }
    
    // Tester la création d'une notification
    $user = User::first();
    if (!$user) {
        throw new Exception("Aucun utilisateur trouvé dans la base de données");
    }
    
    echo "\n=== Test de création de notification ===\n";
    echo "Utilisateur: {$user->id} - {$user->name}\n";
    
    $notification = new AppNotification([
        'type' => 'test',
        'title' => 'Test de notification',
        'message' => 'Ceci est un test de notification',
        'user_id' => $user->id,
        'notifiable_type' => get_class($user),
        'notifiable_id' => $user->id,
        'action_url' => '/notifications',
        'icon' => 'fas fa-bell',
        'color' => 'primary',
        'priority' => 'medium'
    ]);
    
    $notification->save();
    
    echo "Notification créée avec succès! ID: {$notification->id}\n";
    
} catch (Exception $e) {
    echo "\n=== ERREUR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace: \n" . $e->getTraceAsString() . "\n";
}
