<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Foret;
use App\Models\Essence;
use App\Models\Localisation;
use App\Models\SituationAdministrative;
use App\Models\Exploitant;
use App\Models\NatureDeCoupe;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting ArticleSeeder...');
        
        // Get articles data
        $articlesData = $this->getArticlesData();
        $this->command->info('Found ' . count($articlesData) . ' articles to process.');

        $createdCount = 0;
        $skippedCount = 0;

        foreach ($articlesData as $articleData) {
            try {
                // Check if article already exists
                $existingArticle = Article::where('numero', $articleData['numero'])
                    ->where('annee', $articleData['annee'])
                    ->first();
                
                if ($existingArticle) {
                    $skippedCount++;
                    $this->command->warn("Article already exists: {$articleData['numero']} - {$articleData['annee']}");
                    continue;
                }

                Article::create($articleData);
                $createdCount++;
                $this->command->info("Created article: {$articleData['numero']} - {$articleData['annee']}");
            } catch (\Exception $e) {
                $this->command->error("Failed to create article {$articleData['numero']}: " . $e->getMessage());
            }
        }

        $this->command->info("ArticleSeeder completed. Created: {$createdCount}, Skipped: {$skippedCount}");
    }

    /**
     * Get the articles data from the external JSON file
     */
    private function getArticlesData(): array
    {
        $path = base_path('Article.json');
        if (!file_exists($path)) {
            $this->command->error('Article.json file not found at: ' . $path);
            return [];
        }

        $json = file_get_contents($path);
        $rows = json_decode($json, true) ?? [];
        
        if (empty($rows)) {
            $this->command->error('Article.json file is empty or invalid JSON');
            return [];
        }

        $this->command->info('Loaded ' . count($rows) . ' articles from Article.json');
        $articles = [];

        foreach ($rows as $row) {
            try {
                $date = isset($row['Date']) && $row['Date']
                    ? Carbon::createFromFormat('dmY', preg_replace('/[^0-9]/', '', (string) $row['Date']))
                    : null;

                $annee = $date ? (int) $date->format('Y') : null;
                $numero = isset($row['Numéro']) ? (int) $row['Numéro'] : null;

                $situationAdministrative = isset($row['Situation administrative']) ? trim((string) $row['Situation administrative']) : null;
                $situationForestiere = isset($row['Situation forestière']) ? trim((string) $row['Situation forestière']) : null;
                $parcelle = null;
                if (!empty($row['Parcelle'])) {
                    // Keep raw parcelle value if provided (may be composite like "45, 46")
                    $parcelle = (string) $row['Parcelle'];
                } elseif (!empty($row['Forêt'])) {
                    // Some rows use "Forêt" number where parcelle was meant
                    $parcelle = (string) $row['Forêt'];
                }

                // Coordinates → extract lat/log if present
                $lat = null;
                $log = null;
                if (!empty($row['Coordonnées'])) {
                    $coords = (string) $row['Coordonnées'];
                    if (preg_match('/x\s*=\s*([-0-9.,]+)/i', $coords, $mx)) {
                        $log = (float) str_replace([','], ['.'], $mx[1]);
                    }
                    if (preg_match('/y\s*=\s*([-0-9.,]+)/i', $coords, $my)) {
                        $lat = (float) str_replace([','], ['.'], $my[1]);
                    }
                }

                // Essence: pick first code/name if multiple
                $essenceRaw = isset($row['Essence']) ? (string) $row['Essence'] : '';
                $essenceFirst = trim((string) explode(';', str_replace(',', ';', $essenceRaw))[0]);
                $essenceId = $essenceFirst !== '' ? $this->findOrCreateEssence($essenceFirst) : null;

                // Nature de coupe from "Mode d'Exploitation" mapped to NatureDeCoupe label if available
                $modeRaw = isset($row["Mode d'Exploitation"]) ? (string) $row["Mode d'Exploitation"] : '';
                $modeFirst = trim((string) explode(';', str_replace([','], [';'], $modeRaw))[0]);
                $natureCoupeId = null;
                if ($modeFirst !== '') {
                    // If numeric, keep as code placeholder; else use it as label
                    $label = ctype_digit($modeFirst) ? 'Mode ' . $modeFirst : $modeFirst;
                    $natureCoupeId = $this->findOrCreateNatureCoupe($label);
                }

                // Handle adjudicataire - find or create exploitant
                $adjudicataire = isset($row['Adjudicataire']) ? trim((string) $row['Adjudicataire']) : null;
                $exploitantId = null;
                if ($adjudicataire && $adjudicataire !== '') {
                    $exploitantId = $this->findOrCreateExploitant($adjudicataire);
                }

                $data = [
                    'annee' => $annee,
                    'numero' => $numero,
                    'date_adjudication' => $date,
                    'invendu' => isset($row['Invendu']) ? (bool) filter_var($row['Invendu'], FILTER_VALIDATE_BOOLEAN) : false,
                    'prix_de_retrait' => isset($row['Prix de retrait']) ? (float) str_replace([','], ['.'], (string) $row['Prix de retrait']) : null,
                    'situation_administrative_id' => $situationAdministrative ? $this->findOrCreateSituation($situationAdministrative) : null,
                    'foret_id' => $situationForestiere ? $this->findOrCreateForet($situationForestiere) : null,
                    'localisation_id' => $situationForestiere ? $this->findOrCreateLocalisation($situationForestiere) : null,
                    'exploitant_id' => $exploitantId,
                    'lot' => isset($row['Lot']) && $row['Lot'] !== '' ? (string) $row['Lot'] : null,
                    'parcelle' => $parcelle,
                    'essence_id' => $essenceId,
                    'nature_de_coupe_id' => $natureCoupeId,
                    'superficie' => isset($row['Superficie']) ? (float) str_replace([','], ['.'], (string) $row['Superficie']) : null,
                    'bo_m3' => isset($row['BO (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BO (m3)']) : null,
                    'bi_m3' => isset($row['BI (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BI (m3)']) : null,
                    'bf_st' => isset($row['BF (St)']) ? (float) str_replace([','], ['.'], (string) $row['BF (St)']) : null,
                    'tanin_t' => isset($row['Tanin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Tanin (T)']) : null,
                    'fleur_acacia_t' => isset($row["Fleur d'acacia (T)"]) ? (float) str_replace([','], ['.'], (string) $row["Fleur d'acacia (T)"]) : null,
                    'caroube_t' => isset($row['Caroube (T)']) ? (float) str_replace([','], ['.'], (string) $row['Caroube (T)']) : null,
                    'romarin_t' => isset($row['Romarin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Romarin (T)']) : null,
                    'ps_t' => isset($row['PS (T)']) ? (float) str_replace([','], ['.'], (string) $row['PS (T)']) : null,
                    // model fillable uses accented key for liège
                    'liége_st' => isset($row['Liège (St)']) ? (float) str_replace([','], ['.'], (string) $row['Liège (St)']) : null,
                    'charbon_bois_ox' => isset($row['Charbon de bois (Qx)']) ? (float) str_replace([','], ['.'], (string) $row['Charbon de bois (Qx)']) : null,
                    'prix_vente' => isset($row['Prix de vente']) ? (float) str_replace([','], ['.'], (string) $row['Prix de vente']) : null,
                    'dc' => isset($row['DC']) ? (bool) filter_var($row['DC'], FILTER_VALIDATE_BOOLEAN) : false,
                    'rc' => isset($row['RC']) ? (bool) filter_var($row['RC'], FILTER_VALIDATE_BOOLEAN) : false,
                    'numero_adjudication' => $adjudicataire,
                    'type' => 'adjudication',
                    'is_deleted' => false,
                ];

                // Optional lat/log if parsed
                if ($lat !== null) { $data['lat'] = $lat; }
                if ($log !== null) { $data['log'] = $log; }

                // Clean out nulls to avoid mass-assignment of null where not desired
                $articles[] = array_filter($data, static function ($v) { return $v !== null; });
            } catch (\Throwable $e) {
                $this->command->warn("Skipping malformed row: " . $e->getMessage());
                continue;
            }
        }

        return $articles;
    }

    /**
     * Find or create situation administrative
     */
    private function findOrCreateSituation(string $commune): int
    {
        $situation = SituationAdministrative::where('commune', $commune)->first();
        if (!$situation) {
            $situation = SituationAdministrative::create([
                'commune' => $commune,
                'province' => 'Unknown',
                'region' => 'Unknown',
            ]);
        }
        return $situation->id;
    }

    /**
     * Find or create forest
     */
    private function findOrCreateForet(string $foretName): int
    {
        $foret = Foret::where('foret', $foretName)->first();
        if (!$foret) {
            $foret = Foret::create([
                'foret' => $foretName,
                'lat' => '0',
                'log' => '0',
            ]);
        }
        return $foret->id;
    }

    /**
     * Find or create localisation
     */
    private function findOrCreateLocalisation(string $code): int
    {
        $localisation = Localisation::where('CODE', $code)->first();
        if (!$localisation) {
            $localisation = Localisation::create([
                'CODE' => $code,
                'DRANEF' => 'Unknown',
                'DPANEF' => 'Unknown',
                'ENTITE' => 'Unknown',
            ]);
        }
        return $localisation->id;
    }

    /**
     * Find or create essence
     */
    private function findOrCreateEssence(string $essenceName): int
    {
        $essence = Essence::where('essence', $essenceName)->first();
        if (!$essence) {
            $essence = Essence::create([
                'essence' => $essenceName,
                'is_deleted' => false,
            ]);
        }
        return $essence->id;
    }

    /**
     * Find or create nature de coupe
     */
    private function findOrCreateNatureCoupe(string $label): int
    {
        $natureCoupe = NatureDeCoupe::where('nature_de_coupe', $label)->first();
        if (!$natureCoupe) {
            $natureCoupe = NatureDeCoupe::create([
                'nature_de_coupe' => $label,
                'is_deleted' => false,
            ]);
        }
        return $natureCoupe->id;
    }

    /**
     * Find or create exploitant
     */
    private function findOrCreateExploitant(string $adjudicataire): int
    {
        $exploitant = Exploitant::where('numero', $adjudicataire)->first();
        if (!$exploitant) {
            $exploitant = Exploitant::create([
                'categorie' => 'personne_physique',
                'numero' => $adjudicataire,
                'nom_complet' => $adjudicataire,
                'n_cin' => 'AUTO-' . $adjudicataire,
                'activite' => 'BI',
                'qualification_rc' => 'Auto-created from seeder',
                'date_obtention' => now()->format('Y-m-d'),
                'duree_validite' => '5',
                'exclusion' => false,
            ]);
        }
        return $exploitant->id;
    }
}
