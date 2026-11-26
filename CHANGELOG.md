# Changelog

Notable changes to the `clubstudio/craft` starter. The major version follows Craft's (5.x is for Craft 5), so a minor release can include changes that an existing site has to make by hand. Each release lists them under **Upgrading**.

Projects get their own copy of the starter when they're created, without this file, so nothing here reaches an existing site until it's ported.

## Unreleased

### Added

- A Commands section in `README.md.default`, listing the DDEV commands used most on a project.
- A `ddev setup` command (`.ddev/commands/host/setup`) for setting up a new or cloned project. It restarts DDEV so config in `.ddev/` is loaded, installs Composer and npm dependencies, creates `.env` with an app ID and security key, generates the MCP server's OAuth keys, and imports a database dump if one is given, then runs `ddev craft up`.

### Changed

- The READMEs and `AGENTS.md` use `ddev craft up` after a pull in place of `ddev craft project-config/apply`, so pending migrations run too.
- Creating a project is now `composer create-project`, `ddev setup` and `ddev craft install`. The starter's README covers only that and running Vite, and leaves other commands to the project's README.

### Fixed

- `composer.lock` matched neither `composer.json` nor `composer.json.default`, so Composer warned that the lock file was out of date. The starter's lock is refreshed, and `post-create-project-cmd` refreshes the hash for the project's `composer.json` after the swap, without changing any versions.
- `composer.json.default` allows the `php-http/discovery` plugin, as the starter's `composer.json` already did, so Composer doesn't ask about it in new projects.

### Upgrading

- If Composer warns that the lock file is out of date, run `ddev composer update --lock`. If it asks about `php-http/discovery`, add `"php-http/discovery": true` to `allow-plugins` in `composer.json`.

- Optional: copy `.ddev/commands/host/setup` into the project, keeping it executable, and update the project's `README.md` from `README.md.default` (Getting started and Commands).

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
