# Technical SEO review — 9 November 2026

Theme release: 1.2.8. Scope: theme source plus a read-only crawl of https://eza.al/rzzdemo/. This is not a deployment to that server or an indexing/ranking guarantee.

## Verified

- 25 successful HTML routes: Home, About, Expertise, Team, Insights, Contact, Careers, Privacy, Terms, eight expertise pages, five team profiles, the published article, a category archive and search. Every response has one main landmark, one H1, no duplicate IDs and no img missing its alt attribute. Decorative images may correctly use empty alt.
- 78 distinct internal navigation/image/CSS/JavaScript URLs returned successful HTTP responses. External destinations and every srcset candidate were not exhaustively tested.
- An invented URL returns HTTP 404. /insights/page/2/ correctly returns 404 with only one article currently published. Pagination behavior with multiple pages is covered by an isolated regression fixture, not by a populated live archive.
- WordPress XML sitemap returns HTTP 200 and includes pages, articles, expertise and team. robots.txt belongs at the domain root: /rzzdemo/robots.txt is not the site's robots endpoint.
- All theme PHP passes syntax checks. `php -n tests/seo-smoke.php` passes 38 checks for canonical URLs, breadcrumb context/markup, descriptions, JSON-LD, dashboard-controlled indexing and Yoast output ownership. These are isolated fixtures, not a full WordPress integration test.
- Server-rendered navigation/content, labeled navigation and forms, native article/time/address elements, skip navigation, SVG icons with accessible parent controls, responsive image sizes, image dimensions, local fonts with font-display:swap and deferred theme scripts are present.

## Corrected in this release

- Pagination canonical uses its actual page number. Singular canonical remains owned by WordPress core; the theme does not add a duplicate.
- Breadcrumbs now use an ordered list and accurate context for Insights, taxonomy archives and article parents. Visible and structured breadcrumbs share the same items.
- Team and Expertise directories have a complete heading hierarchy. Profile directory is labeled navigation. Articles reference their page heading.
- Empty Contact/taxonomy descriptions receive useful fallback text.
- Native schema now includes connected WebSite/WebPage nodes and Article image/publisher details. No invented ratings, awards, FAQs or completed cases are declared.
- Search engine visibility is controlled through WordPress Settings > Reading and the SEO plugin. The theme has no domain/environment-based noindex rule and sends no custom X-Robots-Tag header. Search results and 404 pages remain noindexed.
- Yoast activation suppresses all native SEO title overrides, description/canonical/social metadata and JSON-LD. It retains theme HTML breadcrumbs and the WordPress privacy setting. Previous native SEO custom-field values are not automatically imported into Yoast.

## Production handoff

1. Migrate the database, uploads and theme; update WordPress/site URLs and replace demo URLs using a serialization-aware tool. Flush permalinks and server/CDN caches. The theme ZIP alone does not include database content or portraits in uploads.
2. Set WordPress environment to production and allow indexing in Settings > Reading. Confirm actual live HTTP headers, robots metadata and root robots.txt do not block intended pages. Keep the demo noindexed.
3. Activate/configure Yoast: site name RZZ LAW, organization identity/logo/social details, individual titles/descriptions, social images, canonicals and indexability. Include Expertise and Team in its sitemap; decide whether thin category/author/date archives should be indexed. Preview each content type. Native schema deliberately hands over to Yoast; verify its graph, and separately configure any specialist/local-business schema if required.
4. Review real content, professional biographies, contact details, legal policies and photo alt text. Media Library controls shared image alt/title; actual visible text and accessible HTML are theme/content responsibilities, not something Yoast rewrites. Legal editorial content should identify its actual author and publication/update date. Configure language per content if multilingual publishing is introduced.
5. Map old production URLs to equivalent new destinations with permanent redirects, especially any /services/ paths becoming /expertise/. Preserve URLs where possible; do not redirect every missing page to Home.
6. Submit the live Yoast sitemap to Google Search Console, inspect representative URLs and validate their structured data. Monitor real 404s and indexing after migration.
7. Measure mobile/desktop performance and Core Web Vitals on the final host and real URLs. Test actual form delivery/SMTP and consent-controlled third-party integrations there. This audit did not run a browser performance measurement or send a real enquiry.

Yoast completes SEO configuration; it does not migrate URLs, create redirects automatically in every edition, validate hosting behavior, guarantee delivery, improve content automatically or guarantee indexing/rankings.

References:
- https://developers.google.com/search/docs/fundamentals/seo-starter-guide
- https://developers.google.com/search/docs/crawling-indexing/block-indexing
- https://developers.google.com/search/docs/appearance/structured-data/sd-policies
