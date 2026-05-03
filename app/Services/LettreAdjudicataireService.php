<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ChargeApayer;
use App\Models\PermiEnlever;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use RuntimeException;

class LettreAdjudicataireService
{
    private const PDF_HEADER_IMAGE_PATH = 'app/pdf-header.png';

    public function __construct(
        private readonly DocxTemplateService $docxTemplateService,
        private readonly PdfRenderingService $pdfRenderingService
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

    public function canGeneratePdf(): bool
    {
        return $this->pdfRenderingService->canGeneratePdf();
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

        $availableReplacements = [
            'Exploitant' => $exploitant?->nom_complet ?: ($exploitant?->raison_sociale ?? ''),
            'numArticle' => (string) ($article->numero ?? $article->id ?? ''),
            'PrixPrincipal' => $this->formatAmount($prixPrincipal),
            'DateAdj' => $this->formatDate($contractVente->date_adjudication ?? $cession?->date_adjudication),
            'DateAO' => $this->formatDate($cession?->date_attribution ?? $cession?->date_adjudication ?? $contractVente->date_adjudication),
            'TaxeFNF' => $this->formatAmount($this->resolveChargeAmount($fnfCharge, $prixPrincipal !== null ? $prixPrincipal * 0.20 : null)),
            'EcheanceFNF' => $this->formatDate($fnfCharge?->date_echeance ?? $contractVente->date_limite_taxes),
            'fraisadj' => $this->formatAmount($this->resolveChargeAmount($fraisAdjudicationCharge, $prixPrincipal !== null ? $prixPrincipal * 0.016 : null)),
            'Echeancefraisadj' => $this->formatDate($fraisAdjudicationCharge?->date_echeance ?? $contractVente->date_limite_taxes),
            'taxeprovinciale' => $this->formatAmount($this->resolveChargeAmount($taxeProvincialeCharge, $prixPrincipal !== null ? $prixPrincipal * 0.10 : null)),
            'Echeancetaxeprovinciale' => $this->formatDate($taxeProvincialeCharge?->date_echeance ?? $contractVente->date_limite_taxes),
            'taxeRefChemin' => $this->formatAmount($this->resolveChargeAmount($taxeRefectionCharge, $this->normalizeAmount($article->taxe_refection_chemins))),
            'EcheanceRefChemin' => $this->formatDate($taxeRefectionCharge?->date_echeance ?? $article->date_echeance_taxe_refection_chemins),
            'Servicerendu' => $this->formatAmount($this->resolveChargeAmount($serviceRenduCharge, $this->normalizeAmount($article->service_rendu_anef))),
            'EcheanceServicerendu' => $this->formatDate($serviceRenduCharge?->date_echeance ?? $article->date_echeance_service_rendu_anef),
            'CautionDefinitif' => $this->formatAmount($this->resolveChargeAmount($cautionCharge, $prixPrincipal !== null ? $prixPrincipal * 0.10 : null)),
            'EcheancierCaution' => $this->formatDate($cautionCharge?->date_echeance ?? $contractVente->date_limite_taxes),
            'boischauffage' => $this->formatAmount($boisChauffage),
            'echeancierboischauffage' => $this->formatDate($article->date_echeance_mise_en_charge ?? $article->date_livaison_mise_en_charge_bf),
            'entite' => $dranef?->ENTITE ?? $dranef?->DPANEF ?? $dranef?->dranef ?? '',
            'province' => $province,
            'percepteur' => $contractVente->percepteur
                ?? $contractVente->permisExploiter?->percepteur
                ?? $permisEnlevers->pluck('percepteur')->filter()->first()
                ?? $dranef?->dranef
                ?? $province,
            'montantTR1' => $this->formatAmount($this->getTrancheAmount($tranches, 1)),
            'montantTR2' => $this->formatAmount($this->getTrancheAmount($tranches, 2)),
            'montantTR3' => $this->formatAmount($this->getTrancheAmount($tranches, 3)),
            'montantTR4' => $this->formatAmount($this->getTrancheAmount($tranches, 4)),
            'EcheanceTR1' => $this->formatDate($this->getTrancheDate($tranches, 1) ?? $contractVente->date_limite_tranche),
            'EcheanceTR2' => $this->formatDate($this->getTrancheDate($tranches, 2) ?? $contractVente->date_limite_tranche),
            'EcheanceTR3' => $this->formatDate($this->getTrancheDate($tranches, 3) ?? $contractVente->date_limite_tranche),
            'EcheanceTR4' => $this->formatDate($this->getTrancheDate($tranches, 4) ?? $contractVente->date_limite_tranche),
        ];

        if (!$this->hasTemplate()) {
            return $availableReplacements;
        }

        try {
            $resolvedReplacements = [];

            foreach ($this->docxTemplateService->extractPlaceholders($this->getTemplatePath()) as $placeholder) {
                $resolvedReplacements[$placeholder] = $availableReplacements[$placeholder] ?? '';
            }

            return $resolvedReplacements !== [] ? $resolvedReplacements : $availableReplacements;
        } catch (\Throwable) {
            return $availableReplacements;
        }
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
        $outputFilename = (string) Str::uuid() . '-' . $downloadName;
        $generatedPath = $this->docxTemplateService->populateTemplate(
            $this->getTemplatePath(),
            $replacements,
            $outputFilename
        );

        return [
            'path' => $generatedPath,
            'download_name' => $downloadName,
            'replacements' => $replacements,
        ];
    }

    /**
     * @return array{path:string,download_name:string,replacements:array<string,string>}
     */
    public function generatePdf(Article $article): array
    {
        if (!$this->canGeneratePdf()) {
            throw new RuntimeException('La generation PDF n\'est pas disponible sur ce serveur.');
        }

        $replacements = $this->getResolvedData($article);
        $filledContent = $this->normalizePdfContentSpacing(
            $this->fillPdfTemplate($this->getPdfTemplate(), $replacements)
        );
        $html = view('articles.pdf.lettre-adjudicataire', [
            'article' => $article,
            'content' => $filledContent,
            'headerImageDataUri' => $this->getPdfHeaderImageDataUri(),
        ])->render();

        $pdfPath = $this->pdfRenderingService->renderHtmlToPdf(
            $html,
            (string) Str::uuid() . '-' . $this->buildPdfDownloadFilename($article)
        );

        return [
            'path' => $pdfPath,
            'download_name' => $this->buildPdfDownloadFilename($article),
            'replacements' => $replacements,
        ];
    }

    public function buildDownloadFilename(Article $article): string
    {
        $articleReference = $article->numero ?: $article->id ?: 'article';
        $sanitizedReference = preg_replace('/[^A-Za-z0-9_-]+/', '-', (string) $articleReference) ?: 'article';

        return 'lettre-adjudicataire-' . trim($sanitizedReference, '-') . '.docx';
    }

    public function buildPdfDownloadFilename(Article $article): string
    {
        return preg_replace('/\.docx$/i', '.pdf', $this->buildDownloadFilename($article)) ?: 'lettre-adjudicataire.pdf';
    }

    private function getPdfTemplate(): string
    {
        return <<<'TEXT'
N°………………./DRANEF-…/SGF			                ….., le ………………………

A
Mr/Sté {{Exploitant}}
Objet : Adjudication / AO des {{DateAdj}} - Article n° {{numArticle}}

            Monsieur, 
Vous avez été déclaré adjudicataire de l’article dont les références sont visées en marge pour un prix principal égal à {{PrixPrincipal}} dh.. En conséquence, j’ai l’honneur de vous faire connaître qu’en application des clauses spéciales de l’adjudication des {{DateAO}} vous êtes tenu d’effectuer les formalités de versement ci-après dans les conditions suivantes :
1- A la caisse du receveur de l’enregistrement du lieu de la vente des droits d’enregistrement conformément aux textes spéciaux régissant la matière.
2- A la caisse du percepteur {{percepteur}} aux échéances ci-après :  
Avant le {{EcheanceRefChemin}}	La taxe de réfection des chemins forestiers	{{taxeRefChemin}} dh   
Avant le {{EcheanceFNF}}	La taxe FNF (20%)	{{TaxeFNF}} dh
Avant le {{Echeancefraisadj}}	La taxe de 1,6%	{{fraisadj}} dh
Avant le {{EcheanceServicerendu}}	La rémunération des services rendus par l’ANEF	{{Servicerendu}}  dh
Vous aurez en outre à verser à la même caisse aux échéances ci-après
Avant le {{EcheanceTR1}}
	1er  quart du prix principal	{{montantTR1}} dh
Avant le {{EcheanceTR2}}	2ème quart du prix principal	{{montantTR2}} dh
Avant le {{EcheanceTR3}}
	3ème quart du prix principal	{{montantTR3}} dh
Avant le {{EcheanceTR4}}	4ème quart du prix principal	{{montantTR4}}  dh
3-A la caisse du Trésorier Provincial d’{{province}}, la taxe de 10% sur la vente des produits forestiers, soit {{taxeprovinciale}} Dh. Le paiement de cette taxe devra être acquitté avant le {{Echeancetaxeprovinciale}}
4- A la caisse du percepteur ou du receveur du Trésorier provincial à {{province}} pour le compte de la caisse de dépôt et de gestion, la valeur du cautionnement définitif soit {{CautionDefinitif}} Dh. Ce Versement peut toutefois être remplacé par la constitution d’une caution personnelle et solidaire auprès de l’un des établissements de crédits agrée par le Secrétaire Général du Gouvernement et l’engagement à verser jusqu’à concurrence de la même somme le montant des dettes que vous pourriez contracter envers l’Etat.
La constitution du cautionnement devra intervenir avant le {{EcheancierCaution}}, faute de quoi vous seriez déclaré déchu de vos droits d’adjudicataire avec toutes les conséquences qui en découleraient.  
5- La fourniture de {{boischauffage}} st de bois de chauffage à titre de mise en charge en nature au personnel forestier, la livraison doit intervenir avant le {{echeancierboischauffage}}
Le permis d’exploiter vous sera délivré par le DPANEF/ZDTF {{entite}} sur présentation :

	Des récépissés de versement des droits d’enregistrement et de diverses taxes ci-dessus ;
	Du récépissé de versement du cautionnement ou de la déclaration de caution ;
	D’une attestation émanant d’une compagnie d’assurance agréée, certifiant que vous avez assuré vos ouvriers contre les accidents de travail pouvant survenir et assurant également votre responsabilité civile contre les risques et dommages aux tiers.

L’exécution du contrat devra commencer le …….

Veuillez agréer, Monsieur, l’expression de mes salutations distinguées.









Notifié à Mr.......................................................

A.....................................le............................
TEXT;
    }

    /**
     * @param array<string, string> $replacements
     */
    private function fillPdfTemplate(string $template, array $replacements): string
    {
        return preg_replace_callback('/\{\{\s*([^{}]+?)\s*\}\}/u', function (array $matches) use ($replacements): string {
            $key = trim((string) ($matches[1] ?? ''));

            return $replacements[$key] ?? '';
        }, $template) ?? $template;
    }

    private function getPdfHeaderImageDataUri(): ?string
    {
        $path = storage_path(self::PDF_HEADER_IMAGE_PATH);

        if (!is_file($path)) {
            return null;
        }

        $contents = @file_get_contents($path);
        if ($contents === false) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mimeType = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/png',
        };

        return 'data:' . $mimeType . ';base64,' . base64_encode($contents);
    }

    private function normalizePdfContentSpacing(string $content): string
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($content));

        return preg_replace("/\n[ \t]*\n(?:[ \t]*\n)+/u", "\n\n", $normalized) ?? $normalized;
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
