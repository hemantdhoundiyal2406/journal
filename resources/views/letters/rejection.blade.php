<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Decision Letter - {{ $article->manuscript_id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; line-height: 1.6; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 30px; }
        .journal-title { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .issn-bar { font-size: 12px; color: #475569; margin-top: 5px; }
        .letter-title { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 25px; color: #991b1b; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .meta-table td { padding: 6px 0; font-size: 13px; }
        .label { font-weight: bold; width: 140px; color: #334155; }
        .content { font-size: 13px; margin-bottom: 30px; text-align: justify; }
        .signature-section { margin-top: 50px; float: right; width: 250px; text-align: center; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="journal-title">{{ $journalName }}</div>
        <div class="issn-bar">{{ $issn }} | {{ $e_issn }}</div>
    </div>

    <div class="letter-title">EDITORIAL DECISION LETTER</div>

    <table class="meta-table">
        <tr>
            <td class="label">Date:</td>
            <td>{{ $todayDate }}</td>
        </tr>
        <tr>
            <td class="label">Manuscript ID:</td>
            <td><strong>{{ $article->manuscript_id }}</strong></td>
        </tr>
        <tr>
            <td class="label">Article Title:</td>
            <td><strong>{{ $article->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Corresponding Author:</td>
            <td>{{ $correspondingAuthor?->full_name }}</td>
        </tr>
    </table>

    <div class="content">
        <p>Dear {{ $correspondingAuthor?->full_name }},</p>
        <p>Thank you for submitting your manuscript titled <strong>"{{ $article->title }}"</strong> to <em>{{ $journalName }}</em>.</p>
        <p>Following comprehensive double-blind peer review by our expert reviewers, we regret to inform you that we are unable to accept your manuscript for publication in its current form.</p>
        <p>We receive a high volume of quality submissions and must decline many papers due to space constraints, methodological fit, or scope priorities. We wish you success in placing your research in another suitable academic venue.</p>
    </div>

    <div class="signature-section">
        <br><br>
        <strong>{{ $editorInChief }}</strong><br>
        <span style="font-size: 12px; color: #475569;">Editor-in-Chief</span>
    </div>

    <div class="footer">
        Editorial Office - {{ $journalName }}
    </div>
</body>
</html>
