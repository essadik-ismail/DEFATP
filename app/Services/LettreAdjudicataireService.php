<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ChargeApayer;
use App\Models\ContractVente;
use App\Models\PermiEnlever;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class LettreAdjudicataireService
{
    public function __construct(
        private readonly DocxTemplateService $docxTemplateService
    ) {
    }

    public function getTemplatePath(): string
    {
        return storage_path('app/templates/lettre-adjudicataire-template.docx');
    }

    public function hasTemplate(): bool
    {
        return is_file($this->getTemplatePath());
    }

    /**
     * @return array<string, string>
     */
    public function getResolvedData(Article $article): array
    {
        $article->loadMissing([
            'cession.dranef',
            'provinces',
            'contractVentes.exploitant',
            'contractVentes.chargeApayer',
            'contractVentes.permisExploiter',
        ]);

        $contractVente = $article->contractVentes->first();

        if (!$contractVente) {
            throw new RuntimeException('Un contrat de vente est requis pour generer la lettre adjudicataire.');
        }

        $charges = $contractVente->chargeApayer ?? collect();
        $tranches = $charges
            ->filter(fn (ChargeApayer $charge) => str_starts_with($charge->nom ?? '', 'Tranche'))
            ->sortBy(fn (ChargeApayer $charge) => $this->extractTrancheNumber($charge->nom))
            ->values();

        $permisEnlevers = PermiEnlever::query()
            ->where('contract_vente_id', $contractVente->id)
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->get();

        $cession = $article->cession;
        $dranef = $cession?->dranef;
        $exploitant = $contractVente->exploitant;

        $cautionCharge = $this->findCharge($charges, ['cautionnement', 'caution']);
        $fnfCharge = $this->findCharge($charges, ['taxe fnf', 'fnf']);
        $fraisAdjudicationCharge = $this->findCharge($charges, ['frais d\'adjudication', 'taxe des frais d\'adjudication', 'frais adjudication']);
        $taxeProvincialeCharge = $this->findCharge($charges, ['taxe provinciale']);
        $taxeRefectionCharge = $this->findCharge($charges, ['refection des chemins', 'refection des chemins forestiers', 'chemins forestiers']);
        $serviceRenduCharge = $this->findCharge($charges, ['service rendu', 'services rendus']);

        $prixPrincipal = $this->normalizeAmount($contractVente->prix_vente);
        $boisChauffage = $this->normalizeAmount(
            $article->bois_chauffage_volume
            ?? $article->fourniture_mise_charge
            ?? $article->mise_en_charge_volume
        );

        $province = $article->provinces
            ->pluck('nom')
            ->filter()
            ->implode(', ');

        return [
            'Exploitant' => $exploitant?->nom_complet ?: ($exploitant?->raison_sociale ?? ''),
            'numArticle' => (string) ($article->numero ?? $article->id ?? ''),
            'PrixPrincipal' => $this->formatAmount($prixPrincipal),
            'DateAdj' => $this->formatDate($contractVente->date_adjudication ?? $cession?->date_adjudication),
            'DateAO' => $this->formatDate($cession?->date_attribution ?? $cession?->date_adjudication ?? $contractVente->date_adjudication),
            'TaxeFNF' => $this->formatAmount($this->resolveChargeAmount($fnfCharge, $prixPrincipal !== null ? $prixPrincipal * 0.20 : null)),
            'EcheanceFNF' => $this->formatDate($fnfCharge?->date_echeance),
            'fraisadj' => $this->formatAmount($this->resolveChargeAmount($fraisAdjudicationCharge, $prixPrincipal !== null ? $prixPrincipal * 0.016 : null)),
            'Echeancefraisadj' => $this->formatDate($fraisAdjudicationCharge?->date_echeance),
            'taxeprovinciale' => $this->formatAmount($this->resolveChargeAmount($taxeProvincialeCharge, $prixPrincipal !== null ? $prixPrincipal * 0.10 : null)),
            'Echeancetaxeprovinciale' => $this->formatDate($taxeProvincialeCharge?->date_echeance),
            'taxeRefChemin' => $this->formatAmount($this->resolveChargeAmount($taxeRefectionCharge, $this->normalizeAmount($article->taxe_refection_chemins))),
            'EcheanceRefChemin' => $this->formatDate($taxeRefectionCharge?->date_echeance ?? $article->date_echeance_taxe_refection_chemins),
            'Servicerendu' => $this->formatAmount($this->resolveChargeAmount($serviceRenduCharge, $this->normalizeAmount($article->service_rendu_anef))),
            'EcheanceServicerendu' => $this->formatDate($serviceRenduCharge?->date_echeance ?? $article->date_echeance_service_rendu_anef),
            'CautionDefinitif' => $this->formatAmount($this->resolveChargeAmount($cautionCharge, $prixPrincipal !== null ? $prixPrincipal * 0.10 : null)),
            'EcheancierCaution' => $this->formatDate($cautionCharge?->date_echeance),
            'boischauffage' => $this->formatAmount($boisChauffage),
            'echeancierboischauffage' => $this->formatDate($article->date_echeance_mise_en_charge ?? $article->date_livaison_mise_en_charge_bf),
            'entite' => $dranef?->ENTITE ?? $dranef?->DPANEF ?? $dranef?->dranef ?? '',
            'province' => $province,
            'percepteur' => $contractVente->permisExploiter?->percepteur
                ?? $permisEnlevers->pluck('percepteur')->filter()->first()
                ?? $dranef?->dranef
                ?? $province,
            'montantTR1' => $this->formatAmount($this->getTrancheAmount($tranches, 1)),
            'montantTR2' => $this->formatAmount($this->getTrancheAmount($tranches, 2)),
            'montantTR3' => $this->formatAmount($this->getTrancheAmount($tranches, 3)),
            'montantTR4' => $this->formatAmount($this->getTrancheAmount($tranches, 4)),
            'EcheanceTR1' => $this->formatDate($this->getTrancheDate($tranches, 1)),
            'EcheanceTR2' => $this->formatDate($this->getTrancheDate($tranches, 2)),
            'EcheanceTR3' => $this->formatDate($this->getTrancheDate($tranches, 3)),
            'EcheanceTR4' => $this->formatDate($this->getTrancheDate($tranches, 4)),
        ];
    }

    /**
     * @return array{path:string,download_name:string,replacements:array<string,string>}
     */
    public function generate(Article $article): array
    {
        if (!$this->hasTemplate()) {
            throw new RuntimeException('Le modele de lettre adjudicataire est introuvable.');
        }

        $replacements = $this->getResolvedData($article);
        $downloadName = $this->buildDownloadFilename($article);
        $generatedPath = $this->docxTemplateService->populateTemplate(
            $this->getTemplatePath(),
            $replacements,
            $downloadName
        );

        return [
            'path' => $generatedPath,
            'download_name' => $downloadName,
            'replacements' => $replacements,
        ];
    }

    public function buildDownloadFilename(Article $article): string
    {
        $articleReference = $article->numero ?: $article->id ?: 'article';
        $sanitizedReference = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) $articleReference) ?: 'article';

        return 'lettre-adjudicataire-' . trim($sanitizedReference, '-') . '.docx';
    }

    /**
     * @param Collection<int, ChargeApayer> $charges
     */
    private function findCharge(Collection $charges, array $needles): ?ChargeApayer
    {
        return $charges->first(function (ChargeApayer $charge) use ($needles) {
            $normalizedName = Str::lower(Str::ascii($charge->nom ?? ''));

            foreach ($needles as $needle) {
                if (str_contains($normalizedName, Str::lower(Str::ascii($needle)))) {
                    return true;
                }
            }

            return false;
        });
    }

    private function resolveChargeAmount(?ChargeApayer $charge, ?float $fallback = null): ?float
    {
        return $this->normalizeAmount($charge?->montant) ?? $fallback;
    }

    /**
     * @param Collection<int, ChargeApayer> $tranches
     */
    private function getTrancheAmount(Collection $tranches, int $number): ?float
    {
        $tranche = $tranches->first(fn (ChargeApayer $charge) => $this->extractTrancheNumber($charge->nom) === $number);

        return $this->normalizeAmount($tranche?->montant);
    }

    /**
     * @param Collection<int, ChargeApayer> $tranches
     */
    private function getTrancheDate(Collection $tranches, int $number): mixed
    {
        $tranche = $tranches->first(fn (ChargeApayer $charge) => $this->extractTrancheNumber($charge->nom) === $number);

        return $tranche?->date_echeance;
    }

    private function extractTrancheNumber(?string $name): int
    {
        if (!$name) {
            return 0;
        }

        if (preg_match('/Tranche\s+(\d+)/i', $name, $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    private function formatDate(mixed $value): string
    {
        if (!$value) {
            return '';
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('d/m/Y');
        }

        try {
            return \Carbon\Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable) {
            return '';
        }
    }

    private function formatAmount(?float $value): string
    {
        if ($value === null) {
            return '';
        }

        return number_format($value, 2, ',', ' ');
    }

    private function normalizeAmount(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }
}
