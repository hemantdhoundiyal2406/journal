<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acceptance Letter - {{ $article->manuscript_id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; line-height: 1.6; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 30px; }
        .journal-title { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .issn-bar { font-size: 12px; color: #475569; margin-top: 5px; }
        .letter-title { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 25px; color: #1e3a8a; }
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
        <div class="issn-bar">{{ $issn }} | {{ $e_issn }} | Publisher: {{ $publisher }}</div>
    </div>

    <div class="letter-title">OFFICIAL ACCEPTANCE LETTER</div>

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
            <td class="label">Authors:</td>
            <td>{{ $article->formatted_authors }}</td>
        </tr>
        <tr>
            <td class="label">Corresponding Author:</td>
            <td>{{ $correspondingAuthor?->full_name }} ({{ $correspondingAuthor?->email }})</td>
        </tr>
    </table>

    <div class="content">
        <p>Dear {{ $correspondingAuthor?->full_name }},</p>
        <p>We are pleased to inform you that following rigorous double-blind peer review and evaluation by our International Editorial Board, your manuscript titled <strong>"{{ $article->title }}"</strong> has been formally <strong>ACCEPTED</strong> for publication in the <em>{{ $journalName }}</em>.</p>
        <p>Your research paper met the necessary criteria of original contribution, scientific validity, methodological rigor, and academic standards set forth by the journal.</p>
        <p>Your paper will be scheduled for online publication in Volume <strong>{{ $article->volume?->volume_number ?? '12' }}</strong>, Issue <strong>{{ $article->issue?->issue_number ?? '1' }}</strong>.</p>
        <p>We congratulate you and your co-authors on this outstanding achievement and look forward to receiving future quality research submissions from your research group.</p>
    </div>

    <div class="signature-section">
        <br><br>
        <strong>{{ $editorInChief }}</strong><br>
        <span style="font-size: 12px; color: #475569;">Editor-in-Chief</span><br>
        <span style="font-size: 11px; color: #64748b;">{{ $journalName }}</span>
    </div>

    <div class="footer">
        This is an official computer-generated document issued by the Editorial Office of {{ $journalName }}.
    </div>
</body>
</html>
