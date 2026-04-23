<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Permis d'Enlever</title>
    <style>
      /* ===== RESET & BASE ===== */
      *,
      *::before,
      *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      /* ===== FORMAT PAGE A4 ===== */
      @page {
        size: A4 portrait;
        margin: 8mm 12mm 8mm 12mm;
      }

      body {
        font-family: "Times New Roman", Times, serif;
        font-size: 9.5pt;
        color: #1a1a1a;
        background: #fff;
        width: 210mm;
        margin: 0 auto;
        padding: 8mm 12mm 8mm 12mm;
        display: flex;
        flex-direction: column;
      }

      /* ===== EN-TÊTE ===== */
      .page-header {
        width: 100%;
        margin-bottom: 3mm;
      }

      .header-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 3mm;
      }

      .header-logo img {
        width: 200mm;
        max-height: 200mm;
        object-fit: contain;
      }

      /* ===== CORPS DU DOCUMENT ===== */
      .doc-body {
        flex: 1;
        line-height: 1.65;
        font-size: 9.5pt;
        display: flex;
        flex-direction: column;
        gap: 1.5mm;
      }

      /* ================================================
         PERMIS D'ENLEVER — classes spécifiques
         ================================================ */

      .pe-doc-title {
        text-align: center;
        font-size: 13pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 4mm;
        text-decoration: underline;
      }

      .pe-two-col {
        display: flex;
        align-items: stretch;
        flex: 1;
        gap: 0;
      }

      .pe-col-left {
        flex: 0 0 62mm;
        min-width: 62mm;
        padding: 3mm 4mm;
        font-size: 9pt;
        line-height: 1.9;
      }

      .pe-col-left .pe-field {
        margin-bottom: 1mm;
      }

      .pe-col-left .pe-label {
        font-weight: 700;
      }

      .pe-col-right {
        flex: 1;
        border-left: none;
        padding: 3mm 5mm;
        font-size: 9pt;
        line-height: 1.8;
        display: flex;
        flex-direction: column;
        gap: 2mm;
      }

      .pe-vu {
        font-size: 11pt;
        font-weight: 700;
      }

      .pe-item {
        margin-bottom: 1.5mm;
      }

      .pe-item .pe-num {
        font-weight: 700;
      }

      .pe-delivrance {
        margin-top: 1mm;
        font-size: 9pt;
        line-height: 1.8;
      }

      .pe-sign-right {
        text-align: right;
        font-size: 9pt;
        margin-top: 2mm;
      }

      .pe-notif {
        font-size: 9pt;
        margin-top: 2mm;
        line-height: 1.8;
      }

      .pe-indent {
        padding-left: 30mm;
        line-height: 1.9;
        font-size: 9pt;
      }

      /* ===== BOUTON IMPRESSION ===== */
      .print-btn {
        display: block;
        margin: 16px auto 0;
        padding: 9px 28px;
        font-size: 13px;
        background: #1a3a1a;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
      }

      .print-btn:hover {
        background: #2d5a2d;
      }

      /* ===== RÈGLES D'IMPRESSION ===== */
      @media print {
        body {
          padding: 0;
          width: 100%;
          height: 297mm;
          overflow: hidden;
        }

        .no-print {
          display: none !important;
        }
      }
    </style>
  </head>
  <body>
    <!-- ===================== EN-TÊTE ===================== -->
    <header class="page-header">
      <div class="header-logo">
        <img src="{{ asset('images/topbar.png') }}" alt="Logo" />
      </div>
    </header>

    <!-- Titre du document -->
    <div class="pe-doc-title">Permis d'Enlever</div>

    <!-- ===================== CORPS DEUX COLONNES ===================== -->
    <div class="pe-two-col">
      <!-- ===== COLONNE GAUCHE ===== -->
      <div class="pe-col-left">
        <div class="pe-field">
          <span class="pe-label">N° :</span>
          <strong>{{ $permiEnlever->num ?? ($permiEnlever->id) }}</strong>
        </div>

        @php
          $cession = $article->cession;
          $dranef = $cession?->dranef;
          $dpanef = $article->cession?->dranef?->dpanefs->first() ?? null;
          $foret = $article->forets->first();
          $canton = $article->canton ?? ($foret ? null : null);
        @endphp

        <div class="pe-field" style="margin-top: 2mm">
          Direction régionale de<br />
          <strong>{{ $dranef?->dranef ?? '………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">DPANEF de :</span><br />
          <strong>{{ $dranef?->dpanefs?->first()?->dpanef ?? '…………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">ZDTF de :</span><br />
          <strong>…………………………………</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          Forêt de<br />
          <strong>{{ $foret?->foret ?? '…………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">Série :</span><br />
          <strong>…………………………………</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">Canton :</span><br />
          <strong>{{ $article->canton ?? '…………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">Parcelle :</span><br />
          <strong>{{ $article->parcelle ?? '…………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <span class="pe-label">Lot :</span><br />
          <strong>{{ $article->lot ?? '…………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          <strong>{{ $cession?->mode_cession ?? '………………………………' }}</strong>
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          Du&nbsp;
          @if($permiEnlever->date)
            <strong>{{ $permiEnlever->date->format('d') }}</strong> /
            <strong>{{ $permiEnlever->date->format('m') }}</strong> /
            <strong>{{ $permiEnlever->date->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20……</strong>
          @endif
        </div>

        <div class="pe-field" style="margin-top: 2mm">
          Article n° <strong>{{ $article->numero ?? '………………………' }}</strong>
        </div>
      </div>

      <!-- ===== COLONNE DROITE ===== -->
      <div class="pe-col-right">
        <div class="pe-vu">VU</div>

        @php
          $contractVente = $permiEnlever->contractVente;
          $exploitant = $contractVente?->exploitant;
          $payment = $contractVente?->payments
              ->firstWhere('date_payment', optional($permiEnlever->date_paiement)->format('Y-m-d'));
          $adjDate = $contractVente?->date_adjudication;
        @endphp

        <div class="pe-item">
          <span class="pe-num">1°)</span> le récépissé n°
          <strong>{{ $payment?->num_quittace ?? ($permiEnlever->num_quittance ?? '…………………………') }}</strong>,
          en date du
          @if($permiEnlever->date_paiement)
            <strong>{{ $permiEnlever->date_paiement->format('d') }}</strong> /
            <strong>{{ $permiEnlever->date_paiement->format('m') }}</strong> /
            <strong>{{ $permiEnlever->date_paiement->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
          @endif
          <br />
          Constatant que M <strong>{{ $exploitant?->nom_complet ?? '………………………………………………………………' }}</strong><br />
          Adjudicataire de l'article <strong>{{ $article->numero ?? '………………' }}</strong>
          de l'adjudication du
          @if($adjDate)
            <strong>{{ $adjDate->format('d') }}</strong> /
            <strong>{{ $adjDate->format('m') }}</strong> /
            <strong>{{ $adjDate->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
          @endif
          <br />
          (forêt de <strong>{{ $foret?->foret ?? '…………………………………………' }}</strong>) a déposé son cautionnement définitif ;
        </div>

        <div class="pe-item">
          <span class="pe-num">2°)</span> les quittances n°
          <strong>{{ $payment?->num_quittace ?? '……………………………………………………………' }}</strong><br />
          <strong>……………………………………………………………………………………………</strong><br />
          du percepteur de <strong>{{ $permiEnlever->percepteur ?? '………………………………………' }}</strong>,
          attestant qu'il satisfait aux paiements (enregistrement, taxes, frais et charges)
          exigés par les articles <strong>…………………………………</strong>
          du cahier des clauses spéciales de <strong>{{ $cession?->mode_cession ?? '………………………' }}</strong>
          du
          @if($adjDate)
            <strong>{{ $adjDate->format('d') }}</strong> /
            <strong>{{ $adjDate->format('m') }}</strong> /
            <strong>{{ $adjDate->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
          @endif
        </div>

        <div class="pe-item">
          <span class="pe-num">3°)</span> l'attestation délivrée par la compagnie d'assurance
          <strong>……………………………………</strong>
          certifiant qu'il a souscrit une police couvrant les risques d'accident du travail de ses ouvriers
          jusqu'au <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
        </div>

        <div class="pe-item">
          <span class="pe-num">4°)</span> la quittance n°
          <strong>{{ $payment?->num_quittace ?? '………………………' }}</strong>
          du
          @if($permiEnlever->date_paiement)
            <strong>{{ $permiEnlever->date_paiement->format('d') }}</strong> /
            <strong>{{ $permiEnlever->date_paiement->format('m') }}</strong> /
            <strong>{{ $permiEnlever->date_paiement->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
          @endif
          du percepteur de <strong>{{ $permiEnlever->percepteur ?? '……………………………………' }}</strong>
          attestant que l'adjudicataire a payé l<strong>……</strong>e <strong>{{ $permiEnlever->num_tranche_paye ?? '……' }}</strong>
          quart ou tranche du prix principal correspondant à un volume de :
          <div class="pe-indent">
            @php $products = $permiEnlever->products ?? collect(); @endphp
            @forelse($products as $product)
              <strong>{{ number_format($product->pivot->quantity ?? 0, 2) }} m³ de {{ $product->name }}</strong><br />
            @empty
              <strong>……………………………………………………</strong><br />
              <strong>……………………………………………………</strong><br />
              <strong>……………………………………………………</strong><br />
              <strong>{{ $permiEnlever->volume ? number_format($permiEnlever->volume, 2) . ' m³' : '……………………………………………………' }}</strong>
            @endforelse
          </div>
        </div>

        <div class="pe-delivrance">
          Nous, <strong>…………………………………………………………</strong>, avons délivré à
          l'adjudicataire susnommé le présent permis d'
          <strong>enlever</strong> pour être, par lui remis au chef de
          secteur forestier à la résidence de
          <strong>………………………………………………</strong>, qu'il devra prévenir du jour où
          il se propose commencer l'<strong>enlèvement</strong>
        </div>

        <div class="pe-sign-right">
          A <strong>………………………………</strong>, le
          @if($permiEnlever->date)
            <strong>{{ $permiEnlever->date->format('d') }}</strong> /
            <strong>{{ $permiEnlever->date->format('m') }}</strong> /
            <strong>{{ $permiEnlever->date->format('Y') }}</strong>
          @else
            <strong>……</strong> / <strong>……</strong> / <strong>20…</strong>
          @endif
          <br />
          Le <strong>………………………………………</strong> <br />
        </div>

        <div class="pe-notif">
          Notifié à (5) <strong>{{ $exploitant?->nom_complet ?? '………………………………' }}</strong><br />
          A <strong>………………………</strong>, le <strong>……</strong> /
          <strong>……</strong> / <strong>20…</strong><br />
          Signature (cachet éventuel)
        </div>
      </div>
    </div>

    <!-- Bouton impression fixe -->
    <div class="no-print" style="position: fixed; bottom: 20px; right: 20px; z-index: 999">
      <button
        class="print-btn"
        onclick="window.print()"
        style="margin: 0; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25)"
      >
        🖨️ Imprimer / Sauvegarder en PDF
      </button>
    </div>
  </body>
</html>
