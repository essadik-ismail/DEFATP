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
                $essenceId = $essenceFirst !== '' ? $this->findEssence($essenceFirst) : null;

                // Nature de coupe from "Mode d'Exploitation" mapped to NatureDeCoupe label if available
                $modeRaw = isset($row["Mode d'Exploitation"]) ? (string) $row["Mode d'Exploitation"] : '';
                $modeFirst = trim((string) explode(';', str_replace([','], [';'], $modeRaw))[0]);
                $natureCoupeId = null;
                if ($modeFirst !== '') {
                    // If numeric, keep as code placeholder; else use it as label
                    $label = ctype_digit($modeFirst) ? 'Mode ' . $modeFirst : $modeFirst;
                    $natureCoupeId = $this->findNatureCoupe($label);
                }

                // Handle adjudicataire - find exploitant
                $adjudicataire = isset($row['Adjudicataire']) ? trim((string) $row['Adjudicataire']) : null;
                $exploitantId = null;
                if ($adjudicataire && $adjudicataire !== '') {
                    $exploitantId = $this->findExploitant($adjudicataire);
                }

                $data = [
                    'annee' => $annee,
                    'numero' => $numero,
                    'date_adjudication' => $date,
                    'invendu' => isset($row['Invendu']) ? (bool) filter_var($row['Invendu'], FILTER_VALIDATE_BOOLEAN) : false,
                    'prix_de_retrait' => isset($row['Prix de retrait']) ? (float) str_replace([','], ['.'], (string) $row['Prix de retrait']) : null,
                    'situation_administrative_id' => $situationAdministrative ? $this->findSituation($situationAdministrative) : null,
                    'foret_id' => $situationForestiere ? $this->findForet($situationForestiere) : null,
                    'localisation_id' => $situationForestiere ? $this->findLocalisation($situationForestiere) : null,
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
     * Find situation administrative - searches across all attributes
     */
    private function findSituation(string $commune): ?int
    {
        // First try exact match on commune
        $situation = SituationAdministrative::where('commune', $commune)->first();
        if ($situation) {
            return $situation->id;
        }
        
        // If not found, search with LIKE on commune and province
        $situation = SituationAdministrative::where(function($query) use ($commune) {
            $query->where('commune', 'LIKE', '%' . $commune . '%')
                  ->orWhere('province', 'LIKE', '%' . $commune . '%');
        })->first();
        
        return $situation ? $situation->id : null;
    }

    /**
     * Find forest - searches across all attributes
     */
    private function findForet(string $foretName): ?int
    {
        // First try exact match on foret field
        $foret = Foret::where('foret', $foretName)->first();
        if ($foret) {
            return $foret->id;
        }
        
        // If not found, search with LIKE on foret field only
        $foret = Foret::where('foret', 'LIKE', '%' . $foretName . '%')->first();
        
        return $foret ? $foret->id : null;
    }

    /**
     * Find localisation - searches across all attributes
     */
    private function findLocalisation(string $code): ?int
    {
        // First try exact match on CODE field
        $localisation = Localisation::where('CODE', $code)->first();
        if ($localisation) {
            return $localisation->id;
        }
        
        // If not found, search with LIKE on available fields
        $localisation = Localisation::where(function($query) use ($code) {
            $query->where('CODE', 'LIKE', '%' . $code . '%')
                  ->orWhere('DRANEF', 'LIKE', '%' . $code . '%')
                  ->orWhere('DPANEF', 'LIKE', '%' . $code . '%')
                  ->orWhere('ENTITE', 'LIKE', '%' . $code . '%');
        })->first();
        
        return $localisation ? $localisation->id : null;
    }

    /**
     * Find essence - searches across all attributes
     */
    private function findEssence(string $essenceName): ?int
    {
        // First try exact match on essence field
        $essence = Essence::where('essence', $essenceName)->first();
        if ($essence) {
            return $essence->id;
        }
        
        // If not found, search with LIKE on essence field only
        $essence = Essence::where('essence', 'LIKE', '%' . $essenceName . '%')->first();
        
        return $essence ? $essence->id : null;
    }

    /**
     * Find nature de coupe - searches across all attributes
     */
    private function findNatureCoupe(string $label): ?int
    {
        // First try exact match on nature_de_coupe field
        $natureCoupe = NatureDeCoupe::where('nature_de_coupe', $label)->first();
        if ($natureCoupe) {
            return $natureCoupe->id;
        }
        
        // If not found, search with LIKE on nature_de_coupe field only
        $natureCoupe = NatureDeCoupe::where('nature_de_coupe', 'LIKE', '%' . $label . '%')->first();
        
        return $natureCoupe ? $natureCoupe->id : null;
    }

    /**
     * Find exploitant - searches across all attributes
     */
    private function findExploitant(string $adjudicataire): ?int
    {
        // First try exact match on numero field
        $exploitant = Exploitant::where('numero', $adjudicataire)->first();
        if ($exploitant) {
            return $exploitant->id;
        }
        
        // If not found, search across available text attributes
        $exploitant = Exploitant::where(function($query) use ($adjudicataire) {
            $query->where('numero', 'LIKE', '%' . $adjudicataire . '%')
                  ->orWhere('nom_complet', 'LIKE', '%' . $adjudicataire . '%')
                  ->orWhere('raison_sociale', 'LIKE', '%' . $adjudicataire . '%')
                  ->orWhere('adresse', 'LIKE', '%' . $adjudicataire . '%')
                  ->orWhere('n_cin', 'LIKE', '%' . $adjudicataire . '%');
        })->first();
        
        return $exploitant ? $exploitant->id : null;
    }
}
