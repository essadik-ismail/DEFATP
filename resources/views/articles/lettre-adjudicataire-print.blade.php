<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lettre Adjudicataire</title>
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

      /* ── Meta line ── */
      .letter-meta {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        font-size: 9pt;
        margin-bottom: 5mm;
      }

      .letter-meta .ref { line-height: 1.7; }
      .letter-meta .place-date { text-align: right; line-height: 1.7; }

      /* ── Recipient ── */
      .letter-recipient {
        margin-bottom: 4mm;
        font-size: 9.5pt;
        line-height: 1.8;
      }

      /* ── Subject ── */
      .letter-subject {
        font-size: 9.5pt;
        font-weight: 700;
        margin-bottom: 4mm;
        line-height: 1.6;
      }

      /* ── Body ── */
      .letter-body {
        font-size: 9.5pt;
        line-height: 1.75;
        display: flex;
        flex-direction: column;
        gap: 3mm;
      }

      .letter-body p { text-align: justify; }

      /* ── Financial table ── */
      .charges-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9pt;
        margin: 1mm 0;
      }

      .charges-table td {
        padding: 1mm 2mm;
        vertical-align: top;
      }

      .charges-table td:first-child {
        white-space: nowrap;
        font-weight: 700;
        width: 30mm;
      }

      .charges-table td:last-child {
        text-align: right;
        white-space: nowrap;
        width: 30mm;
        font-weight: 700;
      }

      /* ── Conditions list ── */
      .conditions-list {
        list-style: none;
        padding-left: 5mm;
        margin: 0;
      }

      .conditions-list li {
        padding-left: 4mm;
        text-indent: -4mm;
        margin-bottom: 1mm;
      }

      .conditions-list li::before { content: "- "; }

      /* ── Signature ── */
      .letter-signature {
        margin-top: 6mm;
        text-align: right;
        font-size: 9.5pt;
        line-height: 2;
      }

      /* ── Notification ── */
      .letter-notif {
        margin-top: 8mm;
        font-size: 9pt;
        border-top: 1px solid #ccc;
        padding-top: 3mm;
        line-height: 1.8;
      }

      /* ── Print button ── */
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

      .print-btn:hover { background: #2d5a2d; }

      @media print {
        body {
          padding: 0;
          width: 100%;
          height: 297mm;
          overflow: hidden;
        }

        .no-print { display: none !important; }
      }
    </style>
  </head>
  <body>

    <!-- EN-TÊTE -->
    <header class="page-header">
      <div class="header-logo">
        <img src="{{ asset('images/topbar.png') }}" alt="Logo" />
      </div>
    </header>

    @php
      $d   = $resolvedPlaceholders;
      $get = fn(string $key, string $fallback = '………………………………') => (($d[$key] ?? '') !== '') ? $d[$key] : $fallback;
    @endphp

    <!-- Référence + Lieu/Date -->
    <div class="letter-meta">
      <div class="ref">
        N° ………… / DRANEF-… / SGF
      </div>
      <div class="place-date">
        ………………, le ………………………
      </div>
    </div>

    <!-- Destinataire -->
    <div class="letter-recipient">
      A<br />
      Mr / Sté <strong>{{ $get('Exploitant') }}</strong>
    </div>

    <!-- Objet -->
    <div class="letter-subject">
      Objet : Adjudication / AO des {{ $get('DateAdj') }} – Article n° {{ $get('numArticle') }}
    </div>

    <!-- Corps -->
    <div class="letter-body">

      <p style="padding-left: 20mm;">Monsieur,</p>

      <p>
        Vous avez été déclaré adjudicataire de l'article dont les références sont visées en marge
        pour un prix principal égal à <strong>{{ $get('PrixPrincipal') }}</strong> dh.
        En conséquence, j'ai l'honneur de vous faire connaître qu'en application des clauses spéciales
        de l'adjudication des {{ $get('DateAO') }} vous êtes tenu d'effectuer les formalités de versement
        ci-après dans les conditions suivantes :
      </p>

      <p>
        <strong>1-</strong> À la caisse du receveur de l'enregistrement du lieu de la vente des droits
        d'enregistrement conformément aux textes spéciaux régissant la matière.
      </p>

      <p>
        <strong>2-</strong> À la caisse du percepteur <strong>{{ $get('percepteur') }}</strong>
        aux échéances ci-après :
      </p>

      <table class="charges-table">
        <tr>
          <td>Avant le {{ $get('EcheanceRefChemin', '……………………') }}</td>
          <td>La taxe de réfection des chemins forestiers</td>
          <td>{{ $get('taxeRefChemin', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('EcheanceFNF', '……………………') }}</td>
          <td>La taxe FNF (20%)</td>
          <td>{{ $get('TaxeFNF', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('Echeancefraisadj', '……………………') }}</td>
          <td>La taxe de 1,6%</td>
          <td>{{ $get('fraisadj', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('EcheanceServicerendu', '……………………') }}</td>
          <td>La rémunération des services rendus par l'ANEF</td>
          <td>{{ $get('Servicerendu', '……………') }} dh</td>
        </tr>
      </table>

      <p>Vous aurez en outre à verser à la même caisse aux échéances ci-après :</p>

      <table class="charges-table">
        <tr>
          <td>Avant le {{ $get('EcheanceTR1', '……………………') }}</td>
          <td>1<sup>er</sup> quart du prix principal</td>
          <td>{{ $get('montantTR1', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('EcheanceTR2', '……………………') }}</td>
          <td>2<sup>ème</sup> quart du prix principal</td>
          <td>{{ $get('montantTR2', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('EcheanceTR3', '……………………') }}</td>
          <td>3<sup>ème</sup> quart du prix principal</td>
          <td>{{ $get('montantTR3', '……………') }} dh</td>
        </tr>
        <tr>
          <td>Avant le {{ $get('EcheanceTR4', '……………………') }}</td>
          <td>4<sup>ème</sup> quart du prix principal</td>
          <td>{{ $get('montantTR4', '……………') }} dh</td>
        </tr>
      </table>

      <p>
        <strong>3-</strong> À la caisse du Trésorier Provincial d'<strong>{{ $get('province') }}</strong>,
        la taxe de 10% sur la vente des produits forestiers, soit
        <strong>{{ $get('taxeprovinciale') }}</strong> Dh.
        Le paiement de cette taxe devra être acquitté avant le {{ $get('Echeancetaxeprovinciale') }}.
      </p>

      <p>
        <strong>4-</strong> À la caisse du percepteur ou du receveur du Trésorier provincial à
        <strong>{{ $get('province') }}</strong> pour le compte de la caisse de dépôt et de gestion,
        la valeur du cautionnement définitif soit <strong>{{ $get('CautionDefinitif') }}</strong> Dh.
        Ce versement peut toutefois être remplacé par la constitution d'une caution personnelle et solidaire
        auprès de l'un des établissements de crédits agréé par le Secrétaire Général du Gouvernement
        et l'engagement à verser jusqu'à concurrence de la même somme le montant des dettes que vous
        pourriez contracter envers l'État.
        La constitution du cautionnement devra intervenir avant le <strong>{{ $get('EcheancierCaution') }}</strong>,
        faute de quoi vous seriez déclaré déchu de vos droits d'adjudicataire avec toutes les conséquences
        qui en découleraient.
      </p>

      <p>
        <strong>5-</strong> La fourniture de <strong>{{ $get('boischauffage') }}</strong> st de bois de
        chauffage à titre de mise en charge en nature au personnel forestier, la livraison doit intervenir
        avant le <strong>{{ $get('echeancierboischauffage') }}</strong>.
      </p>

      <p>
        Le permis d'exploiter vous sera délivré par le DPANEF/ZDTF <strong>{{ $get('entite') }}</strong>
        sur présentation :
      </p>

      <ul class="conditions-list">
        <li>Des récépissés de versement des droits d'enregistrement et de diverses taxes ci-dessus ;</li>
        <li>Du récépissé de versement du cautionnement ou de la déclaration de caution ;</li>
        <li>
          D'une attestation émanant d'une compagnie d'assurance agréée, certifiant que vous avez assuré
          vos ouvriers contre les accidents de travail pouvant survenir et assurant également votre
          responsabilité civile contre les risques et dommages aux tiers.
        </li>
      </ul>

      <p>L'exécution du contrat devra commencer le ……………</p>

      <p>Veuillez agréer, Monsieur, l'expression de mes salutations distinguées.</p>

    </div>

    <!-- Signature -->
    <div class="letter-signature">
      Le ………………………………………………<br /><br /><br />
    </div>

    <!-- Notification -->
    <div class="letter-notif">
      Notifié à Mr ……………………………………………………………………………<br />
      A …………………………………………… le ………………………………………
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
