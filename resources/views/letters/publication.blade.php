<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Publication Confirmation - {{ $article->manuscript_id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; line-height: 1.6; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 30px; }
        .journal-title { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .issn-bar { font-size: 12px; color: #475569; margin-top: 5px; }
        .letter-title { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 25px; color: #047857; }
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

    <div class="letter-title">OFFICIAL PUBLICATION CERTIFICATE / CONFIRMATION</div>

    <table class="meta-table">
        <tr>
            <td class="label">Date of Issue:</td>
            <td>{{ $todayDate }}</td>
        </tr>
        <tr>
            <td class="label">Manuscript ID:</td>
            <td><strong>{{ $article->manuscript_id }}</strong></td>
        </tr>
        <tr>
            <td class="label">DOI Link:</td>
            <td><strong>{{ $article->doi ? 'https://doi.org/' . $article->doi : 'https://ijaser-journal.org/article/' . $article->id }}</strong></td>
        </tr>
        <tr>
            <td class="label">Article Title:</td>
            <td><strong>{{ $article->title }}</strong></td>
        </tr>
        <tr>
            <td class="label">Volume & Issue:</td>
            <td>Volume {{ $article->volume?->volume_number ?? '12' }}, Issue {{ $article->issue?->issue_number ?? '1' }} (pp. {{ $article->start_page ?? '1' }}-{{ $article->end_page ?? '12' }})</td>
        </tr>
        <tr>
            <td class="label">Authors:</td>
            <td>{{ $article->formatted_authors }}</td>
        </tr>
    </table>

    <div class="content">
        <p>This is to certify that the research article titled <strong>"{{ $article->title }}"</strong> has been officially published online in <em>{{ $journalName }}</em> and indexed in the journal digital archives.</p>
        <p>The published article is released under Open Access Creative Commons Attribution 4.0 International License (CC BY 4.0), permitting unrestricted read, download, citation, and dissemination.</p>
    </div>

    <div class="signature-section">
        <br><br>
        <strong>{{ $editorInChief }}</strong><br>
        <span style="font-size: 12px; color: #475569;">Editor-in-Chief</span>
    </div>

    <div class="footer">
        Official Digital Publication Record - {{ $journalName }}
    </div>
</body>
</html>
