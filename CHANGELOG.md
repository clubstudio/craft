# Changelog

Notable changes to the `clubstudio/craft` starter. The major version follows Craft's (5.x is for Craft 5), so a minor release can include changes that an existing site has to make by hand. Each release lists them under **Upgrading**.

Projects get their own copy of the starter when they're created, without this file, so nothing here reaches an existing site until it's ported.

## Unreleased

### Added

- A Commands section in `README.md.default`, listing the DDEV commands used most on a project.
- A `ddev setup` command (`.ddev/commands/host/setup`) for setting up a new or cloned project. It restarts DDEV so config in `.ddev/` is loaded, installs Composer and npm dependencies, creates `.env` with an app ID and security key, generates the MCP server's OAuth keys, and imports a database dump if one is given, then runs `ddev craft up`.
- `ddev share` shares the site, with Vite's hot reloading, with devices on your tailnet, through a Tailscale share provider (`.ddev/share-providers/tailscale.sh`, made the default in `.ddev/config.share.yaml`). In dev, `config/app.web.php` builds the site's URLs from the host a request arrives at when it isn't `PRIMARY_SITE_URL`'s, and `vite.config.js` allows `.ts.net` hosts. The provider clears SEOmatic's metadata caches when sharing starts and stops, because SEOmatic caches tags such as `og:url` by page, not by host.

### Changed

- The READMEs and `AGENTS.md` use `ddev craft up` after a pull in place of `ddev craft project-config/apply`, so pending migrations run too.
- Creating a project is now `composer create-project`, `ddev setup` and `ddev craft install`. The starter's README covers only that and running Vite, and leaves other commands to the project's README.

- Aspect ratios are listed once, in `_macros/media.twig`: `media.aspectRatio(preference)` prints a ratio's class, and with `'value'` its number. `classes.aspectRatio()` and the unused `classes.fontSize()` are removed.
- `config/custom.php` lists, at the top, the gutter, content widths and gaps its image `sizes` are written for, so they're updated together, and `site.css` points to it.
- Columns transform keys name the Column Layout (`columns:12`) in place of the number of columns (`columns:2`), and are sized for the widest column. New keys cover the 1:2, 2:1, 1:3 and 3:1 layouts in full width sections, which got sizes for the whole section before.
- Every component starts with a header in one format: what it is, then its inputs with their defaults. The README describes the contract.
- `_components/button` merges its classes and type into the attributes it's given, so `attributes.class` or `attributes.type` no longer prints a second `class` or `type`.
- `_components/date` accepts a string date, such as its default `now`, as well as a DateTime.
- JavaScript modules load only on pages that use them. `site.js` finds elements with `data-module="name"`, imports `src/js/modules/name.js` and calls its default export with each element; Vite builds each module into its own file. The navigation and video modules take their element, in place of searching the page, and their hooks are `data-module="navigation"` and `data-module="video"`.
- The Paragraph field is merged into Rich Text, which had the same settings. The Paragraph block uses Rich Text with the handle `paragraph`, so templates and saved content are unchanged.
- Removed the `beforeScripts` block from `_layouts/base.twig`, the placeholder content in `_builder/section.twig`, and the `<article>` around the page builder in `_pages/builder.twig`. The video component's wrapper is a `<div>` rather than a `<figure>` with no caption.
- The builder passes each block a `contentWidth`, the Content Width of the Section it's in, set by the Section block and passed on by blocks that render blocks of their own. Content & Media reads it to keep the gutter on full bleed sections, in place of looking for `full-bleed` in the image transform key, which now only chooses image sizes. The rendered markup doesn't change.
- `composer.json` and `composer.json.default` set `config.platform.php` to 8.3, so Composer resolves dependencies for PHP 8.3 whichever version runs it, and `composer.lock` stays installable on the server. The lock already needed 8.3 (league/csv, moneyphp/money and openspout require it), so no versions change. `README.md.default` explains keeping it at production's version.
- `_layouts/base.twig` no longer prints a `csrf-token` meta tag. Nothing used it, and a token in every page stops pages being cached whole. `README.md` says how to get a token when one's needed.
- The Large and Small paragraph styles in `config/ckeditor/Paragraph.json` add `lead` and `small` classes in place of `text-xl` and `text-sm`, styled under `.body-copy` in `site.css`. CKEditor saves these class names in the content, so they no longer depend on Tailwind's.
- `.env.example.production` and `.env.example.staging` leave `PRIMARY_SITE_URL` and `CRAFT_DB_USER` blank, in place of the local DDEV URL and `root`.
- The Main Navigation and Legal Links menus are fetched with `level(1)`. Both are one level deep (`maxLevels: 1`), so nothing changes on the page, but a project that raises the limit won't get child items rendered as top-level links before it adds markup for them.

### Fixed

- The focus indicator was invisible on primary buttons: it was drawn in `currentColor`, so a button with white text got a white outline on a white page. It's now a ring of `--color-focus-contrast` inside an outline of `--color-focus` (black and white by default, in `site.css`), so one of the two shows on any background.
- The Hero's image was always hidden from screen readers, because it was wrapped in `<figure aria-hidden="true">`, so its alt text was never read. The wrapper is gone: an image with alt text is announced, and one without renders `alt=""` and is skipped, as in every other block.
- A logo whose image had no alt text had no accessible name: a linked one was an empty link, and an unlinked SVG got `role="img" aria-label=""`. The Logos block now passes each logo's Title to `_components/logos` as `name`, the fallback after the link's ARIA label and the image's alt text, and the Title's instructions say so. An unlinked logo with no name at all is hidden from screen readers.
- The Video and Content & Media blocks passed `block.title` to `_components/video`, but neither has a title field, so every video got a generic `aria-label="Video"`. The blocks no longer pass a title, and the component only labels the video when it's given one. The play button still reads "Play video", or "Play video: {title}" with a title.
- Smaller accessibility and markup fixes:
  - The footer's social links are a list, like the other menus.
  - The social network SVGs in `src/svg/social/` no longer carry `role="img"` and a `<title>`, which contradicted the `aria-hidden` they're drawn with, and the footer no longer adds a `title` attribute to them.
  - The breadcrumb isn't rendered when the trail is only the current page, such as on the homepage.
  - The dev-mode breakpoint helper is hidden from screen readers.
  - Accordions hide the native `<summary>` marker in Safari too.
  - Image (Inline) no longer wraps its image in a `<figure>` with no caption, and `_components/logos` is a list, each logo a list item, in place of a `<div>` of captionless `<figure>`s.
  - The Link field's instructions say that an ARIA label replaces the link's text for screen readers, so it should start with that text (WCAG 2.5.3).
- Error pages could fail to render when the database was the problem, because `_layouts/error` extended `_layouts/base`, which reads Site Settings. The error layout now stands alone: it reads nothing from the database, and uses Vite and SEOmatic only when they're loaded. Error templates set `heading` and `body` in place of overriding an `error` block.
- `site.webmanifest` printed names and descriptions into JSON unescaped, so a quote in the site's name broke it. It's now built as a hash and JSON-encoded. It also lists `favicon.svg` as the icon for any purpose, beside the maskable PNGs, and falls back to the site name when SEOmatic's names are empty.
- `composer.lock` matched neither `composer.json` nor `composer.json.default`, so Composer warned that the lock file was out of date. The starter's lock is refreshed, and `post-create-project-cmd` refreshes the hash for the project's `composer.json` after the swap, without changing any versions.
- `composer.json.default` allows the `php-http/discovery` plugin, as the starter's `composer.json` already did, so Composer doesn't ask about it in new projects.

### Upgrading

- Replace the `:focus-visible` rule in `src/css/site.css` with the starter's, and add `--color-focus` and `--color-focus-contrast` to `@theme`. If the site's buttons or backgrounds use brand colours, pick two that contrast with each other by 3:1 or more.
- In `templates/_builder/blocks/hero.twig`, remove the `<figure aria-hidden="true">` around the image include. Check that hero images people should hear about have alt text in Assets.
- Copy `templates/_components/logos.twig` and `templates/_builder/blocks/logos.twig` from the starter, or pass `name: logo.title` from the block and fall back to it in the component. Optionally update the Logo entry type's Title instructions to match.
- Remove `title: block.title` from `templates/_builder/blocks/video.twig` and `content-and-media.twig`. In `templates/_components/video.twig`, replace the `<video>` element's `aria-label="{{ data.title ?? 'Video'|t }}"` with `'aria-label': data.title ?: null` in its `attr()` call.
- Optional: copy the smaller accessibility fixes from the starter: `src/svg/social/*.svg`, the social list in `templates/_partials/footer.twig`, and `templates/_components/breadcrumb.twig`, `accordion.twig`, `logos.twig`, `_builder/blocks/image-inline.twig` and `_dev/breakpoint-helper.twig`. A site that styles `_components/logos` by its `<figure>` or `<div>` elements should target the `<ul>` and `<li>` instead.
- Copy `templates/_macros/media.twig` and replace `classes.aspectRatio(x)` with `media.aspectRatio(x)` (importing `_macros/media`), and `media.aspectRatio(x, image)` with `media.aspectRatio(x, 'value', image)`.
- Rename `columns:2` and `columns:3` keys in `config/custom.php` to the layouts they're for (`columns:11`, `columns:111`), and copy `templates/_builder/blocks/columns.twig`. Optionally copy the new `columns:12`, `21`, `13` and `31` keys and the comment at the top of the file, checking its values against the site's CSS.
- Copy `src/js/site.js` and `src/js/modules/`, and change `data-nav-toggle` to `data-module="navigation"` in the header and `data-video` to `data-module="video"` in the video component. Change any project modules to export a function that takes its element, and give their elements `data-module`.
- Optional: to merge the Paragraph field into Rich Text, run `php craft fields/merge paragraph richText` (interactively, choosing Rich Text to keep), then check the Paragraph block's field has the handle `paragraph`. Commit the content migration it writes.
- Optional: to pass `contentWidth` through the builder, copy `templates/_builder/builder.twig` and the Section, Columns, Content & Media, Content Group, CTA, FAQs and Text Column block templates, merging any project changes. Add `contentWidth: contentWidth` beside `transformKey` in any other block that includes `_builder/builder`.
- Copy `templates/_layouts/error.twig` and `templates/_errors/` from the starter. If the site's error templates override `{% block error %}`, change them to set `heading` and `body` instead.
- Copy `templates/site.webmanifest.twig` from the starter, keeping the site's own colours.
- Add `"platform": { "php": "8.3" }` (or the version production runs) to `config` in `composer.json`, then run `ddev composer update --lock`.
- Remove `<meta name="csrf-token">` from `templates/_layouts/base.twig`, unless the site's own JavaScript reads it. If it does, fetch the token from `/actions/users/session-info` instead.
- To use semantic rich text classes, copy `config/ckeditor/Paragraph.json` and the `.lead` and `.small` rules in `site.css`, then update saved content: in the database, replace `class="text-xl"` with `class="lead"` and `class="text-sm"` with `class="small"` in rich text fields (back up first, or use `ddev snapshot`). Until then, existing content keeps `text-xl` and `text-sm`, which Tailwind only generates while they appear in the site's source, so add `@source inline("text-xl text-sm");` to `site.css`.
- If Composer warns that the lock file is out of date, run `ddev composer update --lock`. If it asks about `php-http/discovery`, add `"php-http/discovery": true` to `allow-plugins` in `composer.json`.

- Optional: copy `.ddev/commands/host/setup` and `.ddev/share-providers/tailscale.sh` into the project, keeping them executable, with `.ddev/config.share.yaml`, the `beforeRequest` changes in `config/app.web.php` and `allowedHosts` in `vite.config.js`, and update the project's `README.md` from `README.md.default` (Getting started and Commands).

## 5.3.0 - 2026-11-25

A review of the starter's content model, templates and accessibility, so it's a simpler base for editors and a more consistent one for developers.

### Added

- A style guide at `/styleguide`, routed only in dev mode. It shows colour, typography and every component with sample values, and draws the content widths, Content & Media and Columns layouts with live measurements. A sample video and poster are in `web/samples/`.
- Components for every block that shows content: `eyebrow`, `rich-text`, `list`, `statistic`, `accordion`, `testimonial`, `logos`, `icon`, `divider` and `site-logo`.
- Layout components in `_components/layout/`: `container` (Section), `split` (Content & Media and Hero) and `columns` (Columns).
- A mobile menu that works without JavaScript, using a `no-js:` variant.
- A Footer Navigation menu, with Navigation's Group / Column nodes as columns.
- A Background option on Section blocks (None and Shade), set by `--color-shade` in `site.css`.
- A play button on videos, and posters: a Poster field on the Video block, and Content & Media's Image field for videos.
- A generic error page, `_errors/error.twig`, for errors without their own template.
- Links to files in the Files volume. Download and ID are passed through by the Links and Logos blocks.
- Pinterest, Threads and Vimeo social profiles, brand icons and colours in the Social Network options, and a colour token for every network.
- Field instructions where a field's purpose isn't obvious.
- An organised Entries sidebar: Home and Pages, then Library (FAQs, Testimonials) and Settings (Site Settings).
- The `svg` macro for inlining SVG assets.
- Craft's MCP server in local development.
- Robots and canonical URL in the SEO field.
- `AGENTS.md` for working on the starter, removed from new projects, and `README.md.default`, which becomes a project's README.

### Changed

- Upgraded to Formie 4 and Navigation 4. The form theme uses Formie 4's config, breadcrumbs use `urlBreadcrumbs()` and menus use `getTag()` and `getLinkAttributes()`.
- Pages and Home use one Builder entry type, in place of Basic, Index and Profile.
- The page builder field is `contentBlocks` (Hero and Section), and a Section's blocks are in `sectionContent`.
- Renamed blocks: Text Elements is now Content Group, Text Content is Content, Text is Rich Text, and Text & Media is Content & Media.
- Maximum Width is replaced by Content Width (Full bleed, Full width, Medium, Narrow), with one gutter (`px-gutter`) and one block gap (`gap-block`).
- The Anchor field is on the Section block.
- Section settings are in one Appearance tab, and Padding Top and Padding Bottom share one scale.
- Globals is now Site Settings (`siteSettings`), and its Meta tab is Tracking & Scripts, visible to admins only.
- Frequently Asked Questions is now FAQs (`faqs`). Testimonial titles are made from the person and company, and new testimonials are enabled by default.
- Section and Text Column titles are labelled Label. Blocks don't show slugs, layout options are in Appearance tabs, and Media Type defaults to Image.
- The Link field no longer offers Category, and allows anchors on entry links.
- Templates in `_builder/` read CMS content and pass values to components in `_components/`. Every content block renders a component.
- Components take a `class` input as a string or an array.
- Heading styles are the `heading-1` to `heading-6` utilities, shared by the heading component and rich text. The heading component has an `appearance` input.
- Image transform keys fall back to the nearest parent key, and `sizes` follow the gutter.
- The Hero uses the split layout, and Content & Media's columns are 4rem apart.
- Menus share `_partials/nav-link.twig`.
- Accessibility to WCAG 2.2 AA: a visible focus outline, an indicator on accordions, decorative marks hidden from screen readers, and the full language code in `lang`.
- Rich text lists, links and bold text are styled, and rows of buttons and links follow the surrounding text alignment.

### Removed

- Blocks: Content Cards, Spacer, Showcase, Steps and Anchor.
- Entry types: Basic, Index and Profile, replaced by Builder.
- Fields: Maximum Width, Spacer Size, Article Content, Steps, Entries, Entry Type, User and Content Cards.
- `_builder/container.twig`, the `sectionTextContainer` macro, `.no-scrollbar`, the `x-ua-compatible` meta tag and the noscript notice.

### Fixed

- "Unsupported operand types" and "Invalid class value" errors from macro output, and errors when an aspect ratio is empty.
- Image sizes for full-width layouts, which assumed the old maximum width.
- More than one FAQs block on a page now adds to one FAQPage schema.
- The Divider's layout referred to a field that didn't exist.

### Upgrading

Sites made from 5.2.x keep working as they are. To port a change:

- **Site Settings:** rename the Globals section and entry type in the control panel, keeping their UIDs, then change `globals.` to `siteSettings.` in templates.
- **FAQs:** if templates query the section by handle, change `frequentlyAskedQuestions` to `faqs`.
- **Formie 4 and Navigation 4:** update the plugins, then copy `_components/form.twig`, `_components/breadcrumb.twig` and `_partials/nav-link.twig`. Formie 4 rejects Twig strings in theme config.
- **Content Width:** add the field and move each Section's width over before deleting Maximum Width.
- **Components:** copy each component with the block templates that use it. Blocks now include components rather than holding markup.
- **Removed blocks:** delete or replace their content before deleting the entry types.
