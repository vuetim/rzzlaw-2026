# Local validation

- PHP syntax: all theme files passed (PHP 8.2).
- JavaScript syntax: both scripts passed Node syntax checks.
- HTTP: home, about, expertise archive, eight individual services, team archive, contact, insights, privacy and terms returned 200.
- Missing route returned 404; search returned 200 and noindex.
- Each content route had one H1, one canonical and parseable JSON-LD.
- All 16 linked theme assets returned 200.
- Core WordPress XML sitemap returned 200.
- Initial seeding repeated without duplicating the eight services.
- Customizer registered 47 theme settings.
- Contact handler: valid, mail failure, invalid nonce, invalid email, privacy missing, honeypot, rate limit and array input all passed. Email delivery was intercepted, not sent.
- Browser/mobile visual and interactive checks remain unverified: Chrome execution permission was declined.
- Actual production email delivery is unverified.
- Temporary team profile and single article returned 200 with one H1 and linked practice areas; temporary records were removed.

Revision: supplied Our Story, compact expertise rows, smaller hero/related cards, dotted backgrounds, editable homepage FAQ and shared consent-gated city map. PHP/JS syntax and five representative HTTP routes passed; new Customizer settings passed. Visual pixel matching remains unverified.

Contact revision: supplied office address, email, phone and Monday–Friday hours configured; Home/Contact output and LegalService opening hours checked. Map query uses the supplied address; exact Google pin not independently verified. No email was sent.

About revision: user-supplied Why Choose Us, Our Practice and Our Philosophy added as separate components with Customizer fields. About/Home HTTP checks and changed PHP syntax passed. Hero copy lowered through spacing changes; browser visual verification remains outstanding.

Compact principles revision: shared Why Choose Us / Our Practice / Our Philosophy band added before contact on Expertise, Team and article templates. Home Why uses dark green with a separating gap before cream Team. Representative HTTP/content checks and changed PHP syntax passed; no duplicate IDs found.

Team refinement: canonical role labels remove the PARTNER/Partner duplicate. Engjëll's biography and excerpt were completed from the official team page. Portrait hover/focus treatment includes reduced-motion support. Desktop hero restored to 500px minimum; mobile remains 420px. HTTP/content and filter checks passed; visual browser verification remains outstanding.

Full profile background dots and patterned homepage separator applied; fingerprint visible size reduced to 36px. Analytics preference added separately with default denial and no tracker bundled. Seven consent scenarios passed in Node VM (default, independent categories, acceptance, revocation, persistence, old policy, expiry). Home/profile/Contact HTTP checks passed.

Version 1.1.0: removed the optional case-study registration, templates, metadata and navigation. One-time administrator migration removes obsolete internal menu links, trashes a matching normal page if present, and refreshes rewrite/cache state. PHP syntax and isolated registration/menu/one-time migration checks passed. Live rollout awaits theme replacement.

Version 1.2.0: generated 12 unique editorial photographs and exported 24 valid WebP files (~2 MB combined). Visually inspected every final image. PHP syntax, service/banner mappings, custom image overrides, responsive markup and Customizer controls passed. Full browser/WordPress rendering remains unverified while the local database is stopped.

Version 1.2.1: 12 unique descriptive photo metadata records, 24 renamed WebP assets, 8 distinct related practice images, card-level overrides, real banner/contact image markup, ImageObject descriptions and Media Library registration. Isolated metadata/registration and responsive markup checks passed. Live installation remains pending.

Version 1.2.2: Our Team and every remaining interior page banner now use the office/photo set. Original city images are retained only for the home hero. Legacy non-hero image choices are reset on the next administrator request. PHP syntax and isolated banner-selection checks passed.

Version 1.2.3: all PHP files passed syntax checks. Isolated shared contact rendering and one-time hours migration passed; all five labels and SVG icons are present. Hero CSS values and native Go to top target verified. Browser/mobile visual rendering remains unverified.

Version 1.2.4 fixes escaped newlines in imported list fields and guarantees available related expertise with explicit-choice/default/fallback ordering. Tests passed for newline formats, custom related selections, comma lists, missing records, all eight services and sparse content. Check-list markers now use the three vertical brand dots.

Version 1.2.5 replaces the related section with direct photo links from code-defined pairs. Rendering the actual template passed on all eight services and an unknown slug, with get_posts and relationship metadata deliberately unavailable. Each output contained exactly two distinct links and bundled photos. A version-based admin cache purge is included.

Version 1.2.6: editor fields are restricted by content type. Home/Pages have no author, practice links or photo overrides. With Yoast active, the empty page Details metabox and theme SEO fields are hidden. Isolated checks with/without Yoast passed, including metadata retention, title handoff and no duplicate SEO head output. PHP syntax passed.
