<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Article;
use App\Models\ArticleAuthor;
use App\Models\ArticleFile;
use App\Models\ArticleTimeline;
use App\Models\Author;
use App\Models\CmsPage;
use App\Models\EditorialMember;
use App\Models\EmailTemplate;
use App\Models\Issue;
use App\Models\JournalSetting;
use App\Models\Reviewer;
use App\Models\Volume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JournalDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $settings = [
            'journal_name' => 'International Journal of Advanced Science & Engineering Research',
            'journal_short_name' => 'IJASER',
            'tagline' => 'A Peer-Reviewed International Open Access Multidisciplinary Journal',
            'print_issn' => 'ISSN: 2831-9042',
            'online_issn' => 'E-ISSN: 2831-9050',
            'publisher_name' => 'Global Academic Science Publishing Ltd.',
            'editor_in_chief' => 'Prof. Dr. Alexander Vance, Ph.D.',
            'contact_email' => 'editor@ijaser-journal.org',
            'contact_phone' => '+1 (800) 555-0199',
            'contact_address' => '750 Academic Parkway, Suite 400, Cambridge, MA 02138, USA',
            'manuscript_id_prefix' => 'IJASER',
            'meta_title' => 'International Journal of Advanced Science & Engineering Research (IJASER)',
            'meta_description' => 'IJASER is a high-impact, international peer-reviewed journal publishing original research articles, reviews, and case studies across multidisciplinary fields.',
            'meta_keywords' => 'research journal, academic publishing, open access, science, engineering, technology, peer reviewed',
            'facebook_url' => 'https://facebook.com',
            'twitter_url' => 'https://twitter.com',
            'linkedin_url' => 'https://linkedin.com',
            'hero_title' => 'Empowering Global Academic Excellence & Innovation',
            'hero_subtitle' => 'Publishing groundbreaking open-access scientific research with rapid double-blind peer review and international indexing.',
        ];

        foreach ($settings as $key => $val) {
            JournalSetting::setKey($key, $val);
        }

        // Admin User
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@ijaser-journal.org'],
            [
                'name' => 'Editorial Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('admin@12345'),
            ]
        );

        // 2. Volumes & Issues
        $vol1 = Volume::create([
            'volume_number' => '12',
            'year' => 2026,
            'title' => 'Volume 12 (2026)',
            'description' => 'Annual Volume for the year 2026 featuring contemporary multidisciplinary innovations.',
            'is_active' => true,
        ]);

        $issue1 = Issue::create([
            'volume_id' => $vol1->id,
            'issue_number' => '1',
            'title' => 'Issue 1 (January - March 2026)',
            'publication_month' => 'March',
            'publication_year' => 2026,
            'is_published' => true,
            'published_at' => now()->subDays(30),
        ]);

        $issue2 = Issue::create([
            'volume_id' => $vol1->id,
            'issue_number' => '2',
            'title' => 'Issue 2 (April - June 2026)',
            'publication_month' => 'June',
            'publication_year' => 2026,
            'is_published' => true,
            'published_at' => now()->subDays(5),
        ]);

        // 3. Articles (Published & In-progress)
        $articlesData = [
            [
                'manuscript_id' => 'IJASER-2026-0001',
                'title' => 'Quantum Neural Networks in Autonomous Satellite Navigation: A Comparative Efficiency Analysis',
                'running_title' => 'Quantum NN Satellite Navigation',
                'category' => 'Engineering & Technology',
                'article_type' => 'Research Paper',
                'abstract' => 'This paper explores the application of hybrid quantum neural networks (QNN) for real-time trajectory optimization in low Earth orbit autonomous microsatellites. We demonstrate a 42% reduction in computational latency compared to classical deep learning models.',
                'keywords' => 'Quantum Computing, Satellite Navigation, Deep Learning, Aerospace Engineering, Neural Networks',
                'status' => 'Published',
                'volume_id' => $vol1->id,
                'issue_id' => $issue1->id,
                'doi' => '10.5281/zenodo.1026001',
                'start_page' => '1',
                'end_page' => '14',
                'published_at' => now()->subDays(30),
                'view_count' => 1240,
                'download_count' => 385,
                'authors' => [
                    ['first_name' => 'Elena', 'last_name' => 'Rostova', 'email' => 'elena.rostova@mit.edu', 'institution' => 'Massachusetts Institute of Technology', 'country' => 'United States', 'orcid' => '0000-0002-1825-0097', 'is_corresponding' => true],
                    ['first_name' => 'Marcus', 'last_name' => 'Vance', 'email' => 'm.vance@stanford.edu', 'institution' => 'Stanford University', 'country' => 'United States', 'orcid' => '0000-0001-9281-3341', 'is_corresponding' => false],
                ]
            ],
            [
                'manuscript_id' => 'IJASER-2026-0002',
                'title' => 'Biodegradable Nanostructured Polymers for Target-Specific Cancer Chemotherapy Delivery Systems',
                'running_title' => 'Biodegradable Polymers in Targeted Chemotherapy',
                'category' => 'Medical & Life Sciences',
                'article_type' => 'Research Paper',
                'abstract' => 'Selective drug delivery mechanisms remain a major hurdle in oncology. Here, we present novel chitosan-based nanocarriers engineered for pH-triggered sustained release of doxorubicin with minimal systemic cytotoxicity.',
                'keywords' => 'Nanomedicine, Drug Delivery, Oncology, Biodegradable Polymers, Chemotherapy',
                'status' => 'Published',
                'volume_id' => $vol1->id,
                'issue_id' => $issue2->id,
                'doi' => '10.5281/zenodo.1026002',
                'start_page' => '15',
                'end_page' => '28',
                'published_at' => now()->subDays(5),
                'view_count' => 890,
                'download_count' => 210,
                'authors' => [
                    ['first_name' => 'Rajesh', 'last_name' => 'Kumar', 'email' => 'rajesh.kumar@iitd.ac.in', 'institution' => 'Indian Institute of Technology Delhi', 'country' => 'India', 'orcid' => '0000-0003-4412-8871', 'is_corresponding' => true],
                    ['first_name' => 'Sophia', 'last_name' => 'Chen', 'email' => 'sophia.chen@tsinghua.edu.cn', 'institution' => 'Tsinghua University', 'country' => 'China', 'orcid' => '0000-0002-9901-4412', 'is_corresponding' => false],
                ]
            ],
            [
                'manuscript_id' => 'IJASER-2026-0003',
                'title' => 'Decentralized Zero-Knowledge Proof Architectures for Secure Medical Record Sharing Across Health Systems',
                'running_title' => 'ZK-Proof Medical Records Sharing',
                'category' => 'Computer Science & Security',
                'article_type' => 'Research Paper',
                'abstract' => 'Patient data confidentiality poses persistent challenges in cross-institutional telehealth. We formulate a privacy-preserving smart contract framework utilizing zk-SNARK proofs to enable verifiable access control without exposing patient health info.',
                'keywords' => 'Blockchain, Zero-Knowledge Proofs, Health Informatics, Cybersecurity, Cryptography',
                'status' => 'Under Review',
                'volume_id' => null,
                'issue_id' => null,
                'doi' => null,
                'start_page' => null,
                'end_page' => null,
                'published_at' => null,
                'view_count' => 45,
                'download_count' => 12,
                'authors' => [
                    ['first_name' => 'Hans', 'last_name' => 'Müller', 'email' => 'hans.mueller@tum.de', 'institution' => 'Technical University of Munich', 'country' => 'Germany', 'orcid' => '0000-0001-5542-1200', 'is_corresponding' => true]
                ]
            ],
            [
                'manuscript_id' => 'IJASER-2026-0004',
                'title' => 'Assessment of Microplastic Contamination Vectors in Urban Coastal Estuaries',
                'running_title' => 'Microplastic Vectors in Coastal Estuaries',
                'category' => 'Environmental Sciences',
                'article_type' => 'Review Paper',
                'abstract' => 'Comprehensive review of sampling methodology, infrared spectrographic identification, and bioaccumulation pathways of secondary polyethylene microplastics in benthic organisms.',
                'keywords' => 'Microplastics, Marine Biology, Estuarine Ecology, Environmental Pollution',
                'status' => 'Submitted',
                'volume_id' => null,
                'issue_id' => null,
                'doi' => null,
                'start_page' => null,
                'end_page' => null,
                'published_at' => null,
                'view_count' => 10,
                'download_count' => 2,
                'authors' => [
                    ['first_name' => 'Amara', 'last_name' => 'Okafor', 'email' => 'a.okafor@unilag.edu.ng', 'institution' => 'University of Lagos', 'country' => 'Nigeria', 'orcid' => '0000-0004-1123-0099', 'is_corresponding' => true]
                ]
            ]
        ];

        foreach ($articlesData as $aData) {
            $authors = $aData['authors'];
            unset($aData['authors']);

            $article = Article::create(array_merge($aData, [
                'certificate_token' => 'CERT-' . strtoupper(Str::random(12)),
            ]));

            foreach ($authors as $idx => $auth) {
                ArticleAuthor::create(array_merge($auth, [
                    'article_id' => $article->id,
                    'order' => $idx + 1,
                ]));

                Author::updateOrCreate(
                    ['email' => $auth['email']],
                    [
                        'full_name' => "{$auth['first_name']} {$auth['last_name']}",
                        'institution' => $auth['institution'],
                        'country' => $auth['country'],
                        'orcid' => $auth['orcid'],
                        'total_articles_count' => 1,
                        'published_articles_count' => $aData['status'] === 'Published' ? 1 : 0,
                    ]
                );
            }

            // Timeline
            ArticleTimeline::create([
                'article_id' => $article->id,
                'status_from' => null,
                'status_to' => 'Submitted',
                'comment' => 'Manuscript submitted via public portal.',
                'created_by' => 'System',
            ]);

            if ($aData['status'] === 'Published') {
                ArticleTimeline::create([
                    'article_id' => $article->id,
                    'status_from' => 'Accepted',
                    'status_to' => 'Published',
                    'comment' => 'Article published in Issue ' . ($article->issue?->issue_number ?? '1'),
                    'created_by' => 'Editorial Admin',
                ]);
            }
        }

        // 4. Editorial Board Members
        $editorialList = [
            [
                'name' => 'Prof. Dr. Alexander Vance',
                'designation' => 'Editor-in-Chief',
                'university' => 'Harvard University',
                'country' => 'United States',
                'biography' => 'Distinguished Chair of Applied Physics with over 250 peer-reviewed articles and 30 years of editorial leadership.',
                'orcid' => '0000-0001-9988-7766',
                'google_scholar' => 'https://scholar.google.com',
                'sort_order' => 1,
            ],
            [
                'name' => 'Dr. Eleanor Sterling',
                'designation' => 'Associate Editor',
                'university' => 'University of Oxford',
                'country' => 'United Kingdom',
                'biography' => 'Head of Bioengineering Sciences specializing in regenerative medicine and nanoscale tissue scaffolds.',
                'orcid' => '0000-0002-3344-5566',
                'google_scholar' => 'https://scholar.google.com',
                'sort_order' => 2,
            ],
            [
                'name' => 'Prof. Hiroshi Tanaka',
                'designation' => 'Section Editor (Computer Science)',
                'university' => 'Tokyo Institute of Technology',
                'country' => 'Japan',
                'biography' => 'Leading expert in distributed systems, high-performance computing, and post-quantum cryptographic primitives.',
                'orcid' => '0000-0003-8877-6655',
                'google_scholar' => 'https://scholar.google.com',
                'sort_order' => 3,
            ]
        ];

        foreach ($editorialList as $member) {
            EditorialMember::create($member);
        }

        // 5. Reviewer Database
        Reviewer::create([
            'name' => 'Dr. Robert Thorne',
            'email' => 'r.thorne@cambridge.ac.uk',
            'expertise' => 'Artificial Intelligence, Computer Vision, Robotics',
            'university' => 'University of Cambridge',
            'country' => 'United Kingdom',
            'notes' => 'Available for fast 14-day peer reviews.',
        ]);

        // 6. CMS Pages
        $cmsPages = [
            [
                'slug' => 'about',
                'title' => 'About the Journal',
                'content' => '<h3>Welcome to IJASER</h3><p>The International Journal of Advanced Science & Engineering Research (IJASER) is a high-grade, double-blind peer-reviewed open access publication dedicated to advancing scientific discovery, technological innovation, and interdisciplinary collaboration globally.</p><p>We strictly adhere to COPE guidelines to ensure academic rigor, speed, and transparency in publishing.</p>',
            ],
            [
                'slug' => 'aim-and-scope',
                'title' => 'Aim & Scope',
                'content' => '<h3>Journal Scope</h3><p>IJASER welcomes high-quality original research papers, reviews, and short communications in the following domains:</p><ul><li>Artificial Intelligence & Computer Engineering</li><li>Bio-Medical & Life Sciences</li><li>Environmental & Sustainable Energy Technologies</li><li>Nanotechnology & Advanced Materials</li><li>Applied Physics, Chemistry & Mathematics</li></ul>',
            ],
            [
                'slug' => 'author-guidelines',
                'title' => 'Author Guidelines',
                'content' => '<h3>Submission Guidelines</h3><p>Manuscripts must be submitted in Microsoft Word (.doc, .docx) or PDF format using standard single-column or double-column IEEE/APA style layout. Papers must include a structured abstract (150-250 words), 4-6 keywords, clear figure captions, and reference citations.</p>',
            ],
            [
                'slug' => 'publication-ethics',
                'title' => 'Publication Ethics',
                'content' => '<h3>Ethical Standards</h3><p>IJASER follows standard guidelines established by the Committee on Publication Ethics (COPE). Plagiarism in any form (including self-plagiarism) is strictly prohibited. All submitted manuscripts are screened using Crossref Similarity Check powered by iThenticate.</p>',
            ],
            [
                'slug' => 'peer-review-policy',
                'title' => 'Peer Review Policy',
                'content' => '<h3>Double-Blind Peer Review</h3><p>All submitted manuscripts undergo preliminary editorial screening followed by rigorous double-blind peer review by at least two independent expert reviewers in the relevant domain. Typical review turnaround is 14 to 21 days.</p>',
            ]
        ];

        foreach ($cmsPages as $cp) {
            CmsPage::create(array_merge($cp, [
                'meta_title' => $cp['title'] . ' | IJASER Journal',
                'meta_description' => 'Official ' . $cp['title'] . ' page for IJASER.',
                'meta_keywords' => 'journal, academic, ethics, peer review, scope',
                'is_active' => true,
            ]));
        }

        // 7. Email Templates
        $templates = [
            [
                'key' => 'submission_confirmation',
                'name' => 'Submission Confirmation',
                'subject' => 'Manuscript Submission Received: [{manuscript_id}]',
                'body_html' => '<p>Dear {author_name},</p><p>Thank you for submitting your manuscript titled <strong>"{article_title}"</strong> to {journal_name}.</p><p>Your assigned Manuscript ID is: <strong>{manuscript_id}</strong>.</p><p>You can reference this ID in all future communications with the editorial team.</p><p>Sincerely,<br>Editorial Office<br>{journal_name}</p>',
                'placeholders' => '{author_name}, {article_title}, {manuscript_id}, {journal_name}',
            ],
            [
                'key' => 'acceptance_letter',
                'name' => 'Acceptance Letter Notification',
                'subject' => 'Official Acceptance Letter: [{manuscript_id}]',
                'body_html' => '<p>Dear {author_name},</p><p>We are pleased to inform you that your manuscript <strong>"{article_title}"</strong> (ID: {manuscript_id}) has been formally ACCEPTED for publication in {journal_name}.</p><p>Sincerely,<br>Editor-in-Chief<br>{journal_name}</p>',
                'placeholders' => '{author_name}, {article_title}, {manuscript_id}, {journal_name}',
            ],
            [
                'key' => 'revision_request',
                'name' => 'Revision Request',
                'subject' => 'Revision Required: [{manuscript_id}]',
                'body_html' => '<p>Dear {author_name},</p><p>Your manuscript <strong>"{article_title}"</strong> (ID: {manuscript_id}) has completed peer review. The editorial board requests minor/major revisions as outlined by the reviewers.</p><p>Sincerely,<br>Editorial Team<br>{journal_name}</p>',
                'placeholders' => '{author_name}, {article_title}, {manuscript_id}, {journal_name}',
            ],
            [
                'key' => 'rejection_letter',
                'name' => 'Rejection Decision',
                'subject' => 'Editorial Decision: [{manuscript_id}]',
                'body_html' => '<p>Dear {author_name},</p><p>Thank you for giving us the opportunity to consider your manuscript <strong>"{article_title}"</strong>. Regrettably, after careful peer review, we are unable to accept it for publication.</p><p>Sincerely,<br>Editorial Board<br>{journal_name}</p>',
                'placeholders' => '{author_name}, {article_title}, {manuscript_id}, {journal_name}',
            ]
        ];

        foreach ($templates as $tmpl) {
            EmailTemplate::create($tmpl);
        }

        // 8. Announcements
        Announcement::create([
            'type' => 'top_bar',
            'title' => 'Call for Papers 2026',
            'content' => 'Submissions Open for Volume 12, Issue 3 (2026). Fast-Track Peer Review Available!',
            'link' => url('/submit-manuscript'),
            'is_active' => true,
        ]);

        Announcement::create([
            'type' => 'call_for_papers',
            'title' => 'Call for Papers: Volume 12, Issue 3',
            'content' => 'We invite original research contributions for our upcoming issue focusing on AI, Nanotechnology, and Renewable Energy Systems. Submission deadline: August 30, 2026.',
            'link' => url('/submit-manuscript'),
            'is_active' => true,
        ]);

        Announcement::create([
            'type' => 'latest_news',
            'title' => 'IJASER Achieves High Citation Indexing Status',
            'content' => 'We are proud to announce that IJASER has passed annual Crossref & Google Scholar indexing metrics with record reader downloads.',
            'link' => null,
            'is_active' => true,
        ]);
    }
}
