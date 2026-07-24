<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Copyright Transfer Agreement - {{ $article->manuscript_id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; color: #1e293b; line-height: 1.6; margin: 30px; }
        .header { text-align: center; border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 30px; }
        .journal-title { font-size: 20px; font-weight: bold; color: #0f172a; text-transform: uppercase; }
        .issn-bar { font-size: 12px; color: #475569; margin-top: 5px; }
        .letter-title { text-align: center; font-size: 18px; font-weight: bold; text-decoration: underline; margin-bottom: 25px; color: #0f172a; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        .meta-table td { padding: 6px 0; font-size: 13px; }
        .label { font-weight: bold; width: 140px; color: #334155; }
        .content { font-size: 12px; margin-bottom: 30px; text-align: justify; }
        .sig-box { width: 100%; margin-top: 40px; }
        .sig-col { width: 48%; display: inline-block; vertical-align: top; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="journal-title">{{ $journalName }}</div>
        <div class="issn-bar">{{ $issn }} | {{ $e_issn }}</div>
    </div>

    <div class="letter-title">COPYRIGHT TRANSFER & OPEN ACCESS AGREEMENT</div>

    <table class="meta-table">
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
    </table>

    <div class="content">
        <p>1. <strong>Open Access License (CC BY 4.0):</strong> The author(s) grant {{ $journalName }} the non-exclusive right to publish, index, host, and distribute the work under Creative Commons Attribution License.</p>
        <p>2. <strong>Author Warranties:</strong> The author(s) warrant that the manuscript is original, has not been published elsewhere, is not currently under consideration by any other journal, and does not infringe upon any third-party intellectual property or privacy rights.</p>
        <p>3. <strong>Moral Rights:</strong> Author(s) retain complete ownership of copyright while granting the publisher commercial and non-commercial dissemination rights.</p>
    </div>

    <div class="sig-box">
        <div class="sig-col">
            <strong>Author Signature</strong><br><br><br>
            ______________________________<br>
            Name: {{ $correspondingAuthor?->full_name }}<br>
            Date: {{ $todayDate }}
        </div>
        <div class="sig-col">
            <strong>On Behalf of Publisher</strong><br><br><br>
            ______________________________<br>
            Name: {{ $editorInChief }}<br>
            Date: {{ $todayDate }}
        </div>
    </div>

    <div class="footer">
        {{ $journalName }} - Copyright Department
    </div>
</body>
</html>
