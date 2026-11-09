# RZZ LAW

Local WordPress theme with the supplied Söhne Breit fonts for headings and body copy, PHP 8.0+, no build step, frontend framework or CDN.

## Editing
Appearance → Customize → RZZ LAW: brand colour, contact data, hero images/copy/buttons, all home headings and visibility, about and privacy copy. Navigation uses Primary / Footer menus, with automatic fallback. Separate CSS in assets/css, separate homepage parts in template-parts/home. CSS loads by template; native JS loads deferred.

Expertise: body, excerpt, covered matters, approach, related slugs, SEO title/description. Eight initial pages created on activation without overwriting existing content.
Team: create actual members with real featured portrait, biography, excerpt, position, email, LinkedIn, education, career and practice area slugs. No invented staff are seeded. Directory filters appear at four members and multiple positions. Profiles link to practice areas.
Client Voices: publish approved client statements only. Title = client name, editor = quotation, details = organisation. No invented quotations. Insights use standard posts with author/date and related practice areas.

## Setup and release
Activation creates Home, About, Contact, Insights, Privacy Policy, Terms; Careers and FAQ are optional drafts. Existing pages are retained. Set confirmed contact details and recipient in Customizer. Add exact office address, map link and optionally a Google Maps embed URL. Maps remain blocked until external-media consent. Fingerprint reopens preferences. Choices expire in 180 days. No analytics/advertising scripts bundled.

Before public launch confirm the Privacy Policy/Terms drafts against actual firm practices. Confirm service copy/history. Add real team portraits and biographies and public contact details. Configure and test SMTP on the destination host. Local mail acceptance does not prove external deliverability. WordPress supplies XML sitemaps/robots.txt; check Reading search visibility at launch. SEO plugins take precedence over theme metadata/schema. Team/Expertise post types are theme-owned; migrate registration when switching themes.

Contact: admin-post endpoint with nonce, server validation, honeypot, hashed per-IP 60-second rate limit. Reply-To uses visitor email. Enquiries are not stored in WordPress. Delivery/retention require host configuration.

## Story, FAQ and map
The supplied Our Story is editable under Customizer → RZZ LAW → About page, with HTML paragraphs supported. FAQ questions and answers are under Home · FAQ. The shared map appears on Home and Contact; it defaults to a city overview of Prishtina, not a claimed office location. Replace the map title, embed URL and directions link when the exact office location is confirmed. Google Maps remains blocked until external-media consent. Expertise card descriptions have their own Details field.

About has three separate components: Why Choose Us, Our Practice and Our Philosophy. Each component has its own template part and CSS file; all section headings and nine items are editable under the corresponding About sections in Customizer.

The compact principles-band component reuses all three About sections beneath expertise archives/details, team archives/profiles and articles. Content stays synchronized with the existing About Customizer settings. On Home, Why Choose Us uses dark green on mobile too, followed by a visible gap and a cream Team section.

## Published source migration
Five team profiles and their real portraits were imported from https://rzzlaw.com/team/ into the local WordPress database. Existing manually edited biography copy was preserved. The article at https://rzzlaw.com/pavaresia-financiare-e-komunave/ was imported with its original date and Albanian text, with obsolete Divi shortcodes removed. The source API did not identify the article author, so editorial attribution is RZZ LAW. Older biographies mentioning current postgraduate study were phrased as information from the published biography; no degree completion was invented.

Careers is an open-enquiry page, without invented vacancies. Theme ZIP contains templates; WordPress records and portraits are database/uploads content. The accompanying content XML can be imported using WordPress Tools → Import, with attachment download enabled while this local site is reachable.

## Analytics readiness
Privacy preferences v2 separate Essential, Analytics and External media. Analytics defaults to false. No analytics SDK, tracking request or provider ID is included. Integrations can inspect window.rzzConsent.get().analytics and subscribe to rzz:consent; they must load only after permission, handle revocation, and expire provider cookies as needed. Update the policy/provider details and bump the consent version when deploying an analytics provider so visitors make a new informed choice. Older v1 preferences prompt again.

Version 1.1.0 removes the former case-study feature. On the next administrator request it removes matching old internal menu items and refreshes rewrite rules once. If a normal page with that old slug exists, it is moved to Trash. Other pages, posts and team profiles are retained.

Version 1.2.0 adds 12 coordinated generated editorial photographs, responsive Expertise banners, photographic related cards and editable image defaults. See EDITORIAL-IMAGES.md. Original PNGs are provided separately; the theme includes optimized WebP assets.

Version 1.2.1 uses descriptive rzz-law filenames, per-photo titles/alt/descriptions and ImageObject metadata. On the first administrator request, the 12 photos are registered in Media Library with these details. Related cards select the linked item’s own photo and support independent photo URL and metadata overrides in RZZ LAW Details.

## Repository installation
Clone this repository into wp-content/themes/rzzlaw (keep the folder name rzzlaw), or upload the release/theme ZIP in WordPress. This repository contains theme source and assets only; site content/database and uploads are separate.

Version 1.2.3 standardises contact labels, updates opening hours to Monday�Friday 08:00�16:00, adds a native Go to top link, and uses 13px unrestricted hero descriptions with 12px hero buttons.
