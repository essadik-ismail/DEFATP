<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Lettre Adjudicataire PDF</title>
    <style>
        @page {
            size: A4;
            margin: 8mm 10mm 10mm 10mm;
        }

        body {
            margin: 0;
            color: #111827;
            font-family: "Times New Roman", Times, serif;
            font-size: 11.2pt;
            line-height: 1.18;
        }

        .document {
            width: 100%;
        }

        .header {
            margin-bottom: 4mm;
        }

        .header img {
            display: block;
            width: 100%;
            max-height: 22mm;
            object-fit: contain;
        }

        .letter {
            white-space: pre-wrap;
            word-break: normal;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="document">
        @if(!empty($headerImageDataUri))
            <div class="header">
                <img src="{{ $headerImageDataUri }}" alt="ANEF">
            </div>
        @endif

        <div class="letter">{!! nl2br(e(str_replace("\t", "    ", $content))) !!}</div>
    </div>
</body>
</html>
