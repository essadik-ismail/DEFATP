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
    private $situationData = [];
    private $foretData = [];
    private $localisationData = [];
    private $essenceData = [];
    private $natureCoupeData = [];
    private $exploitantData = [];
    private $autoNumber = 1;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting ArticleSeeder...');
        
        // Load JSON data
        $this->loadJsonData();
        
        // Get articles data (Article.json + any JSON in articles/ directory)
        $articlesData = $this->getArticlesData();
        $folderArticles = $this->getArticlesFromFolder(base_path('articles'));
        $articlesData = array_merge($articlesData, $folderArticles);
        $this->command->info('Found ' . count($articlesData) . ' articles to process.');

        $createdCount = 0;
        $updatedCount = 0;

        foreach ($articlesData as $articleData) {
            try {
                // Ensure numero exists; synthesize if missing
                if (!isset($articleData['numero']) || $articleData['numero'] === null || $articleData['numero'] === '') {
                    $articleData['numero'] = $this->generateNumero($articleData);
                }

                // Check if article already exists (update instead of skip)
                $existingArticle = Article::where('numero', $articleData['numero'] ?? null)
                    ->where('annee', $articleData['annee'] ?? null)
                    ->first();
                // Extract pivot ids (if present) and remove from mass-assignable attributes
                $pivotEssenceIds = $articleData['_pivot_essence_ids'] ?? [];
                $pivotNatureIds = $articleData['_pivot_nature_ids'] ?? [];
                $pivotForetIds = $articleData['_pivot_foret_ids'] ?? [];
                $pivotSituationIds = $articleData['_pivot_situation_ids'] ?? [];
                $pivotLocalisationIds = $articleData['_pivot_localisation_ids'] ?? [];

                unset(
                    $articleData['_pivot_essence_ids'],
                    $articleData['_pivot_nature_ids'],
                    $articleData['_pivot_foret_ids'],
                    $articleData['_pivot_situation_ids'],
                    $articleData['_pivot_localisation_ids']
                );

                if ($existingArticle) {
                    $existingArticle->update($articleData);
                    $article = $existingArticle;
                    $updatedCount++;
                    $this->command->info('Updated article: ' . ($articleData['numero'] ?? 'N/A') . ' - ' . ($articleData['annee'] ?? 'N/A'));
                } else {
                $article = Article::create($articleData);
                    $createdCount++;
                    $this->command->info('Created article: ' . ($articleData['numero'] ?? 'N/A') . ' - ' . ($articleData['annee'] ?? 'N/A'));
                }

                // Sync many-to-many relations
                if (!empty($pivotEssenceIds)) {
                    $article->essences()->syncWithoutDetaching($pivotEssenceIds);
                }
                if (!empty($pivotNatureIds)) {
                    $article->naturesDeCoupe()->syncWithoutDetaching($pivotNatureIds);
                }
                if (!empty($pivotForetIds)) {
                    $article->forets()->syncWithoutDetaching($pivotForetIds);
                }
                if (!empty($pivotSituationIds)) {
                    $article->situationsAdministratives()->syncWithoutDetaching($pivotSituationIds);
                }
                if (!empty($pivotLocalisationIds)) {
                    $article->localisations()->syncWithoutDetaching($pivotLocalisationIds);
                }
                
            } catch (\Exception $e) {
                $num = $articleData['numero'] ?? 'N/A';
                $yr = $articleData['annee'] ?? 'N/A';
                $this->command->error('Failed to create/update article ' . $num . ' - ' . $yr . ': ' . $e->getMessage());
            }
        }

        $this->command->info("ArticleSeeder completed. Created: {$createdCount}, Updated: {$updatedCount}");
    }

    /**
     * Load all JSON data files
     */
    private function loadJsonData(): void
    {
        $this->situationData = $this->loadJsonFile('Situation administrative.json');
        $this->foretData = $this->loadJsonFile('Foret.json');
        $this->localisationData = $this->loadJsonFile('Localisation.json');
        $this->essenceData = $this->loadJsonFile('Essence.json');
        $this->natureCoupeData = $this->loadJsonFile('Nature de Coupe.json');
        $this->exploitantData = $this->loadJsonFile('Adjudication.json');
    }

    /**
     * Load JSON file and return data
     */
    private function loadJsonFile(string $filename): array
    {
        $path = base_path($filename);
        if (!file_exists($path)) {
            $this->command->warn("JSON file not found: {$filename}");
            return [];
        }

        $json = file_get_contents($path);
        $data = json_decode($json, true) ?? [];
        
        if (empty($data)) {
            $this->command->warn("JSON file is empty or invalid: {$filename}");
            return [];
        }

        $this->command->info("Loaded " . count($data) . " records from {$filename}");
        return $data;
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
                    'numero' => $numero ?? $this->generateNumero(['annee' => $annee]),
                    'date_adjudication' => $date,
                    'invendu' => isset($row['Invendu']) ? (bool) filter_var($row['Invendu'], FILTER_VALIDATE_BOOLEAN) : false,
                    'prix_de_retrait' => isset($row['Prix de retrait']) ? (float) str_replace([','], ['.'], (string) $row['Prix de retrait']) : null,
                    'exploitant_id' => $exploitantId,
                    'lot' => isset($row['Lot']) && $row['Lot'] !== '' ? (string) $row['Lot'] : null,
                    'parcelle' => $parcelle,
                    'superficie' => isset($row['Superficie']) ? (float) str_replace([','], ['.'], (string) $row['Superficie']) : null,
                    'bo_m3' => isset($row['BO (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BO (m3)']) : null,
                    'bi_m3' => isset($row['BI (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BI (m3)']) : null,
                    'bf_st' => isset($row['BF (St)']) ? (float) str_replace([','], ['.'], (string) $row['BF (St)']) : null,
                    'tanin_t' => isset($row['Tanin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Tanin (T)']) : null,
                    'fleur_acacia_t' => isset($row["Fleur d'acacia (T)"]) ? (float) str_replace([','], ['.'], (string) $row["Fleur d'acacia (T)"]) : null,
                    'caroube_t' => isset($row['Caroube (T)']) ? (float) str_replace([','], ['.'], (string) $row['Caroube (T)']) : null,
                    'romarin_t' => isset($row['Romarin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Romarin (T)']) : null,
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

                // Add pivot IDs (arrays) built from the looked-up single ids
                $situationId = $situationAdministrative ? $this->findSituation($situationAdministrative) : null;
                $foretId = $situationForestiere ? $this->findForet($situationForestiere) : null;
                $localisationId = $situationForestiere ? $this->findLocalisation($situationForestiere) : null;

                $data['_pivot_situation_ids'] = $situationId ? [$situationId] : [];
                $data['_pivot_foret_ids'] = $foretId ? [$foretId] : [];
                $data['_pivot_localisation_ids'] = $localisationId ? [$localisationId] : [];
                $data['_pivot_essence_ids'] = $essenceId ? [$essenceId] : [];
                $data['_pivot_nature_ids'] = $natureCoupeId ? [$natureCoupeId] : [];

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
     * Read all JSON files in a folder and aggregate parsed articles
     */
    private function getArticlesFromFolder(string $folderPath): array
    {
        $all = [];
        if (!is_dir($folderPath)) {
            $this->command->warn('Articles folder not found: ' . $folderPath);
            return $all;
        }
        $files = glob($folderPath . DIRECTORY_SEPARATOR . '*.json');
        if (!$files) {
            return $all;
        }
        foreach ($files as $file) {
            try {
                $json = file_get_contents($file);
                $payload = json_decode($json, true) ?? [];
                if (empty($payload)) {
                    $this->command->warn('Empty or invalid JSON: ' . $file);
                    continue;
                }
                // Detect schema: either direct array of rows, or wrapped under Feuil1
                if (isset($payload['Feuil1']) && is_array($payload['Feuil1'])) {
                    $rows = $payload['Feuil1'];
                } elseif (array_is_list($payload)) {
                    $rows = $payload;
                } else {
                    $this->command->warn('Unrecognized JSON schema in ' . basename($file) . ' — skipping');
                    continue;
                }
                $this->command->info('Parsing ' . count($rows) . ' rows from ' . basename($file));
                // Try Feuil1 schema first
                if (isset($payload['Feuil1'])) {
                    foreach ($rows as $row) {
                        $norm = $this->normalizeFeuil1Row($row);
                        if (!empty($norm)) { $all[] = $norm; }
                    }
                    continue;
                }
                // Fallback: Inline the parsing loop (Article.json-like)
                foreach ($rows as $row) {
                    try {
                        // Basic reuse of the transform from getArticlesData
                        $date = isset($row['Date']) && $row['Date']
                            ? \Carbon\Carbon::createFromFormat('dmY', preg_replace('/[^0-9]/', '', (string) $row['Date']))
                            : null;
                        $annee = $date ? (int) $date->format('Y') : null;
                        $numero = isset($row['Numéro']) ? (int) $row['Numéro'] : null;
                        $situationAdministrative = isset($row['Situation administrative']) ? trim((string) $row['Situation administrative']) : null;
                        $situationForestiere = isset($row['Situation forestière']) ? trim((string) $row['Situation forestière']) : null;
                        $parcelle = null;
                        if (!empty($row['Parcelle'])) {
                            $parcelle = (string) $row['Parcelle'];
                        } elseif (!empty($row['Forêt'])) {
                            $parcelle = (string) $row['Forêt'];
                        }
                        $lat = null; $log = null;
                        if (!empty($row['Coordonnées'])) {
                            $coords = (string) $row['Coordonnées'];
                            if (preg_match('/x\s*=\s*([-0-9.,]+)/i', $coords, $mx)) { $log = (float) str_replace([','], ['.'], $mx[1]); }
                            if (preg_match('/y\s*=\s*([-0-9.,]+)/i', $coords, $my)) { $lat = (float) str_replace([','], ['.'], $my[1]); }
                        }
                        $essenceRaw = isset($row['Essence']) ? (string) $row['Essence'] : '';
                        $essenceFirst = trim((string) explode(';', str_replace(',', ';', $essenceRaw))[0]);
                        $essenceId = $essenceFirst !== '' ? $this->findEssence($essenceFirst) : null;
                        $modeRaw = isset($row["Mode d'Exploitation"]) ? (string) $row["Mode d'Exploitation"] : '';
                        $modeFirst = trim((string) explode(';', str_replace([','], [';'], $modeRaw))[0]);
                        $natureCoupeId = null;
                        if ($modeFirst !== '') { $label = ctype_digit($modeFirst) ? 'Mode ' . $modeFirst : $modeFirst; $natureCoupeId = $this->findNatureCoupe($label); }
                        $adjudicataire = isset($row['Adjudicataire']) ? trim((string) $row['Adjudicataire']) : null;
                        $exploitantId = $adjudicataire ? $this->findExploitant($adjudicataire) : null;

                        $data = [
                            'annee' => $annee,
                            'numero' => $numero ?? $this->generateNumero(['annee' => $annee]),
                            'date_adjudication' => $date,
                            'invendu' => isset($row['Invendu']) ? (bool) filter_var($row['Invendu'], FILTER_VALIDATE_BOOLEAN) : false,
                            'prix_de_retrait' => isset($row['Prix de retrait']) ? (float) str_replace([','], ['.'], (string) $row['Prix de retrait']) : null,
                            'exploitant_id' => $exploitantId,
                            'lot' => isset($row['Lot']) && $row['Lot'] !== '' ? (string) $row['Lot'] : null,
                            'parcelle' => $parcelle,
                            'superficie' => isset($row['Superficie']) ? (float) str_replace([','], ['.'], (string) $row['Superficie']) : null,
                            'bo_m3' => isset($row['BO (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BO (m3)']) : null,
                            'bi_m3' => isset($row['BI (m3)']) ? (float) str_replace([','], ['.'], (string) $row['BI (m3)']) : null,
                            'bf_st' => isset($row['BF (St)']) ? (float) str_replace([','], ['.'], (string) $row['BF (St)']) : null,
                            'tanin_t' => isset($row['Tanin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Tanin (T)']) : null,
                            'fleur_acacia_t' => isset($row["Fleur d'acacia (T)"]) ? (float) str_replace([','], ['.'], (string) $row["Fleur d'acacia (T)"]) : null,
                            'caroube_t' => isset($row['Caroube (T)']) ? (float) str_replace([','], ['.'], (string) $row['Caroube (T)']) : null,
                            'romarin_t' => isset($row['Romarin (T)']) ? (float) str_replace([','], ['.'], (string) $row['Romarin (T)']) : null,
                            'liége_st' => isset($row['Liège (St)']) ? (float) str_replace([','], ['.'], (string) $row['Liège (St)']) : null,
                            'charbon_bois_ox' => isset($row['Charbon de bois (Qx)']) ? (float) str_replace([','], ['.'], (string) $row['Charbon de bois (Qx)']) : null,
                            'prix_vente' => isset($row['Prix de vente']) ? (float) str_replace([','], ['.'], (string) $row['Prix de vente']) : null,
                            'dc' => isset($row['DC']) ? (bool) filter_var($row['DC'], FILTER_VALIDATE_BOOLEAN) : false,
                            'rc' => isset($row['RC']) ? (bool) filter_var($row['RC'], FILTER_VALIDATE_BOOLEAN) : false,
                            'numero_adjudication' => $adjudicataire,
                            'type' => 'adjudication',
                            'is_deleted' => false,
                        ];
                        if ($lat !== null) { $data['lat'] = $lat; }
                        if ($log !== null) { $data['log'] = $log; }
                        // build pivot arrays
                        $situationId = $situationAdministrative ? $this->findSituation($situationAdministrative) : null;
                        $foretId = $situationForestiere ? $this->findForet($situationForestiere) : null;
                        $localisationId = $situationForestiere ? $this->findLocalisation($situationForestiere) : null;
                        $data['_pivot_situation_ids'] = $situationId ? [$situationId] : [];
                        $data['_pivot_foret_ids'] = $foretId ? [$foretId] : [];
                        $data['_pivot_localisation_ids'] = $localisationId ? [$localisationId] : [];
                        $data['_pivot_essence_ids'] = $essenceId ? [$essenceId] : [];
                        $data['_pivot_nature_ids'] = $natureCoupeId ? [$natureCoupeId] : [];

                        $all[] = array_filter($data, static function ($v) { return $v !== null; });
                    } catch (\Throwable $e) {
                        $this->command->warn('Skipping malformed row in ' . basename($file) . ': ' . $e->getMessage());
                        continue;
                    }
                }
            } catch (\Throwable $e) {
                $this->command->warn('Failed to parse ' . basename($file) . ': ' . $e->getMessage());
                continue;
            }
        }
        return $all;
    }

    /**
     * Find situation administrative - searches across all attributes
     */
    private function findSituation(string $commune): ?int
    {
        // First try exact match on commune
        foreach ($this->situationData as $situation) {
            if (isset($situation['Commune']) && $situation['Commune'] === $commune) {
                return $situation['id'] ?? null;
            }
        }
        
        // If not found, search with LIKE on commune and province
        foreach ($this->situationData as $situation) {
            if (isset($situation['Commune']) && str_contains($situation['Commune'], $commune)) {
                return $situation['id'] ?? null;
            }
            if (isset($situation['Province']) && str_contains($situation['Province'], $commune)) {
                return $situation['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Find forest - searches across all attributes
     */
    private function findForet(string $foretName): ?int
    {
        // First try exact match on foret field
        foreach ($this->foretData as $foret) {
            if (isset($foret['foret']) && $foret['foret'] === $foretName) {
                return $foret['id'] ?? null;
            }
        }
        
        // If not found, search with LIKE on foret field only
        foreach ($this->foretData as $foret) {
            if (isset($foret['foret']) && str_contains($foret['foret'], $foretName)) {
                return $foret['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Find localisation - searches across all attributes
     */
    private function findLocalisation(string $code): ?int
    {
        // First try exact match on CODE field
        foreach ($this->localisationData as $localisation) {
            if (isset($localisation['CODE']) && $localisation['CODE'] === $code) {
                return $localisation['id'] ?? null;
            }
        }
        
        // If not found, search with LIKE on available fields
        foreach ($this->localisationData as $localisation) {
            if (isset($localisation['CODE']) && str_contains($localisation['CODE'], $code)) {
                return $localisation['id'] ?? null;
            }
            if (isset($localisation['DRANEF']) && str_contains($localisation['DRANEF'], $code)) {
                return $localisation['id'] ?? null;
            }
            if (isset($localisation['DPANEF']) && str_contains($localisation['DPANEF'], $code)) {
                return $localisation['id'] ?? null;
            }
            if (isset($localisation['ENTITE']) && str_contains($localisation['ENTITE'], $code)) {
                return $localisation['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Find essence - searches across all attributes
     */
    private function findEssence(string $essenceName): ?int
    {
        // First try exact match on essence field
        foreach ($this->essenceData as $essence) {
            if (isset($essence['Essence']) && $essence['Essence'] === $essenceName) {
                return $essence['id'] ?? null;
            }
        }
        
        // If not found, search with LIKE on essence field only
        foreach ($this->essenceData as $essence) {
            if (isset($essence['Essence']) && str_contains($essence['Essence'], $essenceName)) {
                return $essence['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Find nature de coupe - searches across all attributes
     */
    private function findNatureCoupe(string $label): ?int
    {
        // First try exact match on nature_de_coupe field
        foreach ($this->natureCoupeData as $natureCoupe) {
            if (isset($natureCoupe['Nature de coupe']) && $natureCoupe['Nature de coupe'] === $label) {
                return $natureCoupe['id'] ?? null;
            }
        }
        
        // If not found, search with LIKE on nature_de_coupe field only
        foreach ($this->natureCoupeData as $natureCoupe) {
            if (isset($natureCoupe['Nature de coupe']) && str_contains($natureCoupe['Nature de coupe'], $label)) {
                return $natureCoupe['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Find exploitant - searches across all attributes
     */
    private function findExploitant(string $adjudicataire): ?int
    {
        // First try exact match on numero field
        foreach ($this->exploitantData as $exploitant) {
            if (isset($exploitant['numero']) && $exploitant['numero'] === $adjudicataire) {
                return $exploitant['id'] ?? null;
            }
        }
        
        // If not found, search across available text attributes
        foreach ($this->exploitantData as $exploitant) {
            if (isset($exploitant['numero']) && str_contains($exploitant['numero'], $adjudicataire)) {
                return $exploitant['id'] ?? null;
            }
            if (isset($exploitant['nom_complet']) && str_contains($exploitant['nom_complet'], $adjudicataire)) {
                return $exploitant['id'] ?? null;
            }
            if (isset($exploitant['raison_sociale']) && str_contains($exploitant['raison_sociale'], $adjudicataire)) {
                return $exploitant['id'] ?? null;
            }
            if (isset($exploitant['adresse']) && str_contains($exploitant['adresse'], $adjudicataire)) {
                return $exploitant['id'] ?? null;
            }
            if (isset($exploitant['n_cin']) && str_contains($exploitant['n_cin'], $adjudicataire)) {
                return $exploitant['id'] ?? null;
            }
        }
        
        return null;
    }

    /**
     * Generate a synthetic numero when source data lacks a distinct number.
     */
    private function generateNumero(array $articleData): string
    {
        $year = $articleData['annee'] ?? date('Y');
        $seq = str_pad((string) $this->autoNumber++, 5, '0', STR_PAD_LEFT);
        return (string) $year . '-' . $seq;
    }
}
