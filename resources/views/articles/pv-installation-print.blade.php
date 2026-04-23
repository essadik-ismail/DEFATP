<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PV d'Installation - Article #{{ $article->numero ?? $article->id }}</title>
  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

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

    .header-body {
      display: flex;
      align-items: stretch;
    }

    .header-left {
      flex: 0 0 62mm;
      min-width: 62mm;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: 2mm;
    }

    .header-left .field-line {
      font-size: 8.5pt;
      color: #1a1a1a;
    }

    .header-left .field-line .label {
      font-weight: 700;
    }

    .header-divider {
      width: 1px;
      background: #ffffff;
      margin: 0 4mm;
      flex-shrink: 0;
    }

    .header-center {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      text-align: center;
      gap: 2mm;
      padding-top: 2mm;
    }

    .pv-title {
      font-size: 9.5pt;
      font-weight: 700;
      line-height: 1.4;
      color: #1a1a1a;
      letter-spacing: 0.2px;
    }

    .pv-sub {
      font-size: 9pt;
      font-weight: 700;
      line-height: 1.8;
      color: #1a1a1a;
      text-align: center;
    }

    /* ===== SÉPARATEUR ===== */
    .header-separator {
      border: none;
      border-top: 1.5px solid #aaa;
      margin: 3mm 0 3mm 0;
    }

    /* ===== CORPS ===== */
    .doc-body {
      flex: 1;
      line-height: 1.65;
      font-size: 9.5pt;
      display: flex;
      flex-direction: column;
      gap: 1.5mm;
    }

    .pv-meta {
      display: flex;
      gap: 15mm;
      margin-bottom: 1mm;
    }

    .doc-field .f-label {
      font-weight: 700;
    }

    .participants-block .p-label {
      font-weight: 700;
    }

    .section-title {
      font-weight: 700;
      font-size: 9.5pt;
      margin-top: 2mm;
      text-decoration: underline;
    }

    .main-list {
      list-style: none;
      padding-left: 4mm;
      margin: 0;
    }

    .main-list > li {
      margin-bottom: 0.8mm;
      padding-left: 4mm;
      text-indent: -4mm;
    }

    .main-list > li::before {
      content: "- ";
      font-weight: 700;
    }

    .sub-list {
      list-style: none;
      padding-left: 8mm;
      margin: 0.3mm 0;
    }

    .sub-list li {
      margin-bottom: 0.5mm;
      padding-left: 4mm;
      text-indent: -4mm;
    }

    .sub-list li::before {
      content: "o  ";
    }

    .indent-list {
      list-style: none;
      padding-left: 8mm;
      margin: 0.3mm 0;
    }

    .indent-list li {
      margin-bottom: 0.5mm;
      padding-left: 4mm;
      text-indent: -4mm;
    }

    .indent-list li::before {
      content: "- ";
    }

    .section-ab {
      margin-bottom: 1mm;
    }

    .section-ab .ab-title {
      font-weight: 700;
      margin-bottom: 1mm;
    }

    .puce-list {
      list-style: none;
      padding-left: 5mm;
      margin: 0;
    }

    .puce-list li {
      margin-bottom: 0.8mm;
      padding-left: 5mm;
      text-indent: -5mm;
    }

    .puce-list li::before {
      content: "\00D8  ";
    }

    /* ===== SIGNATURE ===== */
    .signature {
      margin-top: 4mm;
      text-align: right;
      font-weight: 700;
      font-size: 9.5pt;
      margin-bottom: 15mm;
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

  @php
    $contractVente = $pvInstallation->contractVente;
    $article->loadMissing(['cession.dranef.dpanefs', 'forets', 'modeExploitations', 'natureDeCoupes', 'essences', 'products']);
    $cession  = $article->cession;
    $dranef   = $cession?->dranef;
    $dpanef   = $dranef?->dpanefs?->first();
    $foret    = $article->forets?->first();
    $adjDate  = $contractVente?->date_adjudication;
    $exploitant = $contractVente?->exploitant;

    $modeExpl = $article->modeExploitations->pluck('mode_exploiattion')->filter()->implode(', ');
    $natureExpl = $article->natureDeCoupes->pluck('nature_de_coupe')->filter()->implode(', ');

    // Volumes BI / BF
    $bi = $article->bois_chauffage_volume ? number_format($article->bois_chauffage_volume, 2) . ' m³' : null;
    $bf = $article->mise_en_charge_volume ? number_format($article->mise_en_charge_volume, 2) . ' m³' : null;

    $dot = '…………………………………………………';
  @endphp

  <!-- EN-TÊTE -->
  <header class="page-header">
    <div class="header-logo">
      <img src="{{ asset('images/topbar.png') }}" alt="Logo" />
    </div>

    <div class="header-body">

      <div class="header-left">
        <div class="field-line">
          <span class="label">DRANEF de :</span>
          <strong>{{ $dranef?->dranef ?? $dot }}</strong>
        </div>
        <div class="field-line">
          <span class="label">DPANEF de :</span>
          <strong>{{ $dpanef?->dpanef ?? $dot }}</strong>
        </div>
        <div class="field-line">
          <span class="label">ZDTF de :</span>
          <strong>{{ $dot }}</strong>
        </div>
        <div class="field-line">
          <span class="label">DFP de :</span>
          <strong>{{ $dot }}</strong>
        </div>
      </div>

      <div class="header-divider"></div>

      <div class="header-center">
        <div class="pv-title">
          PV d'Installation et d'ouverture du chantier des<br />
          Travaux d'Exploitation forestière
        </div>
        <div class="pv-sub">
          Adjudication du
          <strong>
            @if($adjDate)
              {{ $adjDate->format('d/m/Y') }}
            @else
              ………………………………
            @endif
          </strong><br />
          Article n° <strong>{{ $article->numero ?? '………………………………' }}</strong>
        </div>
      </div>

    </div>
  </header>

  <hr class="header-separator" />

  <!-- CORPS -->
  <main class="doc-body">

    <div class="pv-meta">
      <span><strong>PV n° :</strong> <strong>{{ $pvInstallation->pvn ?? '………………………' }}</strong></span>
      <span><strong>Date :</strong> <strong>{{ $pvInstallation->date ? $pvInstallation->date->format('d/m/Y') : '………………………' }}</strong></span>
    </div>

    <div class="doc-field">
      <span class="f-label">Objet :</span>
      Lancement des travaux d'exploitation forestière adjudication du :
      <strong>{{ $adjDate ? $adjDate->format('d/m/Y') : '………………………………' }}</strong>
    </div>

    <div class="doc-field">
      <span class="f-label">Adjudicataire :</span>
      <strong>{{ $pvInstallation->exploitant ?? $exploitant?->nom_complet ?? '………………………………………………………………………………' }}</strong>
    </div>

    <div class="doc-field">
      <span class="f-label">Lieu :</span>
      Forêt domaniale <strong>{{ $foret?->foret ?? '…………………………………………' }}</strong>
      Canton de <strong>{{ $article->canton ?? '…………………………………' }}</strong>
    </div>

    <div class="participants-block">
      <span class="p-label">Participants :</span>
      @if($pvInstallation->participants)
        @foreach(explode("\n", $pvInstallation->participants) as $line)
          <strong>{{ trim($line) }}</strong><br />
        @endforeach
      @else
        <strong>…………………………………………………………………………………………………………</strong><br />
        <strong>…………………………………………………………………………………………………………</strong><br />
        <strong>…………………………………………………………………………………………………………</strong>
      @endif
    </div>

    <div class="doc-field">
      <span class="f-label">En présence de Mr</span>
      <strong>{{ $pvInstallation->exploitant ?? $exploitant?->nom_complet ?? '………………………………………………………' }}</strong>
      l'exploitant acquéreur
    </div>

    <div class="section-title">Notes et Observations :</div>
    <p>Nous avons rappelé à l'adjudicataire les règles à respecter et qui sont comme suit :</p>

    <ul class="main-list">
      <li>Les limites du lot ont été désignées à l'adjudicataire et ses ouvriers et elles ont été reconnues par ces ouvriers.</li>
      <li>Mode d'exploitation : <strong>{{ $modeExpl ?: '…………………………………………………………………………' }}</strong></li>
      <li>Nature de l'exploitation : <strong>{{ $natureExpl ?: '……………………………………………………………' }}</strong></li>
      <li>Volume cédé :
        <ul class="sub-list">
          <li>BI <strong>{{ $bi ?? '……………………………………………………………………………………' }}</strong></li>
          <li>BF <strong>{{ $bf ?? '……………………………………………………………………………………' }}</strong></li>
        </ul>
      </li>
      <li>Réserve : <strong>{{ $pvInstallation->reserve ?? '……………………………………………………………………………………' }}</strong></li>
      <li>Colportage des produits : en temps du jour.</li>
      <li>Les travaux devront être effectués de proche en proche et en temps du jour.</li>
      <li>Rappel à l'adjudicataire des délais des paiements des quarts du prix principal.</li>
      <li>M.O : <strong>{{ $pvInstallation->emo ?? '……………………………………………………………………………………………' }}</strong></li>
      <li>Charbonnières :
        <ul class="indent-list">
          @php
            $charb = $pvInstallation->{'charbonniére'} ?? null;
            $charbParts = $charb ? explode('/', $charb, 2) : [];
          @endphp
          <li>En cours de cuisson : <strong>{{ $charbParts[0] ?? '……………………………………………………………' }}</strong></li>
          <li>Préparées et édifiées : <strong>{{ $charbParts[1] ?? '………………………………………………………' }}</strong></li>
        </ul>
      </li>
      <li>Mise en charge : <strong>{{ $pvInstallation->mise_en_charge ?? '……………………………………………………………………………' }}</strong></li>
      <li>Ravalement des souches : <strong>{{ $pvInstallation->ravalement_souches ?? '………………………………………………………………' }}</strong></li>
      <li>Remembrement : <strong>{{ $pvInstallation->remarient ?? '…………………………………………………………………………' }}</strong></li>
      <li>Mise en défens : respectés ou non <strong>{{ $pvInstallation->mise_en_defens ?? '…………………………………………………' }}</strong></li>
    </ul>

    <div class="section-title">Dispositions prises séances tenantes :</div>

    <div class="section-ab">
      <div class="ab-title">A - Invitation du caporal à :</div>
      <ul class="puce-list">
        <li>Travailler de proche en proche</li>
        <li>Respecter les limites du lot</li>
        @if($pvInstallation->invitation_caporal)
          <li>{{ $pvInstallation->invitation_caporal }}</li>
        @else
          <li>Seul les bois <strong>………………………</strong> sont à exploiter</li>
          <li>Les bois <strong>………………………</strong> sont à préserver</li>
        @endif
        <li>Effectuer le nettoyement du parterre de la coupe et dégagement des remuants hors forêts.</li>
        <li>Se conformer aux prescriptions du CPS de l'adjudication.</li>
      </ul>
    </div>

    <div class="section-ab">
      <div class="ab-title">B - Invitation du personnel forestier à :</div>
      <ul class="main-list">
        <li>
          Se conformer aux dispositions du CPS afférent à l'adjudication du
          <strong>{{ $adjDate ? $adjDate->format('d/m/Y') : '…………………………' }}</strong>
          notamment l'article <strong>{{ $article->numero ?? '………………………' }}</strong>
          et de suivre l'exécution des travaux d'exploitation et de vidanges conformément au clauses du
          <strong>{{ $cession?->mode_cession ?? '…………………………' }}</strong>
          instructions en vigueurs.
        </li>
      </ul>
    </div>

    <div class="signature">Le Chef du DFP</div>

  </main>

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
