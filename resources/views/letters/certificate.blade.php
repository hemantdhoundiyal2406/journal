<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Publication - {{ $article->manuscript_id }}</title>
    <style>
        @page { size: landscape; margin: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; padding: 40px; }
        .cert-border { border: 12px double #1e3a8a; padding: 30px; background: #ffffff; position: relative; height: 90%; box-shadow: inset 0 0 10px rgba(0,0,0,0.05); }
        .cert-header { text-align: center; }
        .cert-logo { font-size: 24px; font-weight: bold; color: #1e3a8a; letter-spacing: 1px; text-transform: uppercase; }
        .cert-subtitle { font-size: 13px; color: #475569; margin-top: 5px; }
        .cert-title { text-align: center; font-size: 32px; font-weight: bold; color: #0f172a; margin-top: 30px; letter-spacing: 2px; text-transform: uppercase; }
        .cert-presented { text-align: center; font-size: 14px; color: #64748b; margin-top: 15px; font-style: italic; }
        .author-name { text-align: center; font-size: 26px; font-weight: bold; color: #1e3a8a; margin-top: 10px; border-bottom: 2px solid #cbd5e1; display: inline-block; padding-bottom: 5px; }
        .cert-body { text-align: center; font-size: 14px; color: #334155; margin-top: 20px; line-height: 1.6; padding: 0 40px; }
        .article-title-box { font-size: 16px; font-weight: bold; color: #0f172a; margin: 10px 0; }
        .footer-table { width: 100%; margin-top: 40px; border-collapse: collapse; }
        .footer-table td { width: 33.33%; text-align: center; vertical-align: bottom; font-size: 12px; }
        .qr-box { font-size: 10px; color: #64748b; border: 1px solid #e2e8f0; padding: 8px; border-radius: 6px; background: #f8fafc; display: inline-block; }
    </style>
</head>
<body>
    <div class="cert-border">
        <div class="cert-header">
            <div class="cert-logo">{{ $journalName }}</div>
            <div class="cert-subtitle">{{ $issn }} | {{ $e_issn }}</div>
        </div>

        <div class="cert-title">Certificate of Publication</div>

        <div class="cert-presented">This certificate is proudly awarded to</div>

        <div style="text-align: center;">
            <div class="author-name">{{ $author?->full_name ?? 'Research Author' }}</div>
        </div>

        <div class="cert-body">
            in recognition of the publication of the research manuscript titled
            <div class="article-title-box">"{{ $article->title }}"</div>
            published in <strong>{{ $journalName }}</strong>, {{ $issueTitle }}.<br>
            <strong>Manuscript ID:</strong> {{ $article->manuscript_id }} | <strong>DOI:</strong> {{ $article->doi ?? 'Pending' }}
        </div>

        <table class="footer-table">
            <tr>
                <td>
                    <div class="qr-box">
                        <strong>VERIFICATION CODE</strong><br>
                        <span style="font-family: monospace; font-size: 11px; color: #1e3a8a;">{{ $certificateToken }}</span><br>
                        <span style="font-size: 8px; color: #94a3b8;">Scan/Verify at: {{ $verificationUrl }}</span>
                    </div>
                </td>
                <td>
                    <span style="color: #64748b;">Date of Issue</span><br>
                    <strong>{{ $date }}</strong>
                </td>
                <td>
                    <br><br>
                    <strong style="border-top: 1px solid #0f172a; padding-top: 4px; display: inline-block;">{{ $editorInChief }}</strong><br>
                    <span style="color: #64748b;">Editor-in-Chief</span>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
