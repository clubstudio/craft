<img src="./src/svg/logo.svg" width="64">

# Craft CMS Boilerplate

A scaffolding package to help you hit the ground running with your next [Craft CMS](https://craftcms.com) project.

## What's included?

* [Craft CMS](https://craftcms.com)
* [Tailwind CSS](https://tailwindcss.com)
* [Vite](https://vite.dev/)
* A flexible, accessible content builder with responsive image transforms
* A sensible directory structure
* Commonly used plugins — CKEditor, Formie, Navigation, Retour and SEOmatic

## Getting Started

Install or update [DDEV](https://ddev.com/) (which requires [Docker](https://ddev.readthedocs.io/en/stable/users/install/docker-installation/)), then follow these steps:

1. Create a project directory and move into it:
   ```
   mkdir my-craft-project && cd my-craft-project
   ```
2. Create DDEV configuration files:
   ```
   ddev config --project-type=craftcms --docroot=web
   ```
3. Scaffold the project from this starter project:
   ```
   ddev composer create-project "clubstudio/craft"
   ```
   This replaces this README and `composer.json` with your project's own, from `README.md.default` and `composer.json.default`, and removes the starter's `AGENTS.md` and `CHANGELOG.md`.
4. Finish setting up the project:
   ```
   ddev setup
   ```
   This restarts DDEV so it loads the config the starter ships in `.ddev/` (the Vite dev server port and the MCP server's keys), and installs npm dependencies.
5. Run Craft's installer, answering each prompt as it's asked:
   ```
   ddev craft install
   ```
6. Run `ddev launch` to view your site in the browser
7. Log in to the Craft control panel at `/admin` (or run `ddev launch /admin`) with the admin account you created during install

Next, feel free to read the official [Craft installation documentation](https://craftcms.com/docs/5.x/install.html).

## Developing

Start the Vite dev server, with hot reloading, and leave it running while you work:

```
ddev npm run dev
```

Pages render without styles unless it's running. To build optimised, cache-busted assets into `web/dist/`:

```
ddev npm run build
```

When you've finished working, run `ddev stop` to shut down the project containers and free up resources.

Your project's README lists the other DDEV commands you'll use, including `ddev setup` for teammates cloning the project.

## Project Config

Craft's [project config](https://craftcms.com/docs/5.x/system/project-config.html) is the source of truth for your site's schema — fields, entry types, sections and settings. It's stored as version-controlled YAML in `config/project/`.

- After pulling changes from a teammate, run any pending migrations and sync your database with:
  ```
  ddev craft up
  ```
- In `dev`, `CRAFT_ALLOW_ADMIN_CHANGES=true` lets you edit the schema from the control panel. Craft writes those edits back to `config/project/`, so commit the updated YAML alongside your code.

## MCP

Craft's built-in [MCP server](https://github.com/craftcms/cms/blob/5.x/docs/mcp.md) lets AI assistants like Claude Code read and manage your content and schema. It's set up for local development out of the box:

- **Enabled in project config.** The authenticated server is turned on under **Settings → MCP**, at `/admin/mcp`. The public server stays off.
- **Dev only.** `config/app.web.php` switches the server off unless `CRAFT_ENVIRONMENT=dev`, so on staging and production its endpoints return a 404.
- **OAuth keys.** The `.ddev/config.mcp.yaml` hook generates signing keys in `storage/` the first time DDEV starts with Composer dependencies installed, and `ddev setup` generates them if they're missing. The keys are git-ignored. To generate them by hand, run `ddev craft oauth/generate-keys`.
- **Claude Code.** `composer create-project` writes a `.mcp.json` pointing at your DDEV site. Commit it so your team shares it. In Claude Code, run `/mcp`, choose `craft`, sign in to Craft and approve the request. Access is read-only by default, and admins can grant full access.

Other MCP clients can connect to `https://<project>.ddev.site/admin/mcp/auth`. To skip OAuth for a local client, use stdio: `ddev craft mcp/serve --user=<email>` runs as that user.

## Template System

This boilerplate uses a flexible content builder pattern with reusable components and macros.

### Directory Structure

```
templates/
├── _layouts/          base (the HTML document), master (header, <main>, footer) and error
├── _partials/         Header, footer, skip link and the link for a menu item
├── _pages/            entry.twig, the template every section uses, includes the one for the entry's type
├── _builder/          Reads CMS content and passes it to components
│   ├── blocks/        One template per content block
│   ├── builder.twig   Renders a list of blocks
│   └── section.twig   The <section> wrapper with padding and background, embedded by the Section block
├── _components/       Reusable UI; components take the values they show
│   └── layout/        Container, split and columns, which arrange what's embedded in them
├── _macros/           Class maps, aspect ratios, transform keys, SVG assets
├── _styleguide/       Each component with sample values, at /styleguide in dev mode
├── _dev/              Development helpers (the breakpoint helper)
├── _errors/           Error pages: 404, 503 and error for everything else
└── site.webmanifest.twig
```

### Template Hierarchy

Templates follow an inheritance pattern:

1. **base.twig** - HTML document structure, head tags, Vite script integration
2. **master.twig** - Extends base, adds header/footer and `<main>` landmark
3. **Page templates** - Specific page layouts that extend master

Sections with URLs use `_pages/entry.twig` as their template. It includes `_pages/{entry-type-handle}.twig` (kebab-cased), so each entry type has one page template: Home and Pages use the Builder entry type, rendered by `_pages/builder.twig`.

Error pages in `_errors/` extend `_layouts/error`, which stands alone rather than extending base: it reads nothing from the database, so error pages still render when the database is the problem.

### Content Builder Pattern

The builder system allows content editors to compose pages using reusable blocks:

```twig
{% include '_builder/builder' with {
    context: 'page',
    blocks: entry.contentBlocks.all(),
} only %}
```

**How it works:**
- Iterates through content blocks
- Includes appropriate templates with context-aware fallbacks
- Supports nested blocks for complex layouts

**Template Resolution Order:**
1. `_builder/blocks/{context}/{block-type}.twig` (context-specific override, for each context in turn)
2. `_builder/blocks/{block-type}.twig` (default)

**Example contexts:** `page`, `hero`, `footer`, `columns/2`, `content-and-media`

### Image Transform System

Images use contextual transforms defined in `config/custom.php` for optimal responsive delivery:

**Transform Key Pattern:**
Keys are built hierarchically as blocks nest:
- `section:full-bleed` - Full bleed section (edge to edge)
- `section:full-width` - Full width section (inside the site's gutters)
- `section:medium`, `section:narrow` - Capped content widths
- `section:full-width:columns:12` - Columns in a full width section, keyed by Column Layout (`11`, `12`, `21`, `13`, `31` or `111`); images are sized for the widest column
- `section:full-width:content-and-media` - Content & Media block
- `section:full-width:faqs` - FAQs block

A key that isn't in `config/custom.php` falls back to its nearest parent that is (`section:narrow:my-block` uses `section:narrow`), then to `default`.

Each `sizes` is written for the gutter, content widths and gaps the CSS uses, which are listed at the top of `config/custom.php`. If you change any of them in the CSS, update the sizes that use them.

**Building Transform Keys:**
```twig
{% import '_macros/transforms' as transforms %}
{% set transformKey = transforms.buildKey([transformKey, 'my-block']) %}
```

**Adding New Transforms:**
1. Add configuration to `config/custom.php`
2. Define `width`, `srcset` array, and `sizes` string
3. Match the hierarchical key pattern

**Debug Mode:**
In development mode, HTML comments show the transform key used for each image, and the key it fell back to.

### Content Width and Gutters

A Section's **Content Width** sets how wide its content is: Full bleed (edge to edge), Full width (inside the gutters, the default), Medium or Narrow. The gutter is one CSS variable, `--gutter` in `src/css/site.css`, used through the `px-gutter` class by sections, the header, footer and hero. The Medium and Narrow widths are `--container-medium` and `--container-narrow` in the same file, and the space between blocks in a Section is `--block-gap`, used through `gap-block`.

### Components

Components in `_components/` take the values they show, never CMS content, so they work anywhere: in blocks, page templates and the style guide. Each one follows the same contract:

- It starts with a header comment: its name and what it is, then its inputs, each with its default in brackets.
- It sets those defaults with `{% set data = { … }|merge(_context) %}` and reads its inputs from `data`.
- `class` adds classes to the element its header names, as a string or an array, joined with `classes.combine()`.
- Where its header lists `attributes`, that adds HTML attributes to the element it names, merged into one `attr()` call, so a class or type given there joins the component's own rather than repeating the attribute.
- It's included with `only`, so it sees nothing but its inputs.

### Template Conventions

- Block templates receive `block`, `context`, `transformKey` and `contentWidth` variables. A block that renders blocks of its own passes `transformKey` and `contentWidth` on to them.
- `transformKey` only chooses image sizes. A block that lays itself out by its Section's Content Width reads `contentWidth` (`full-bleed`, `full-width`, `medium` or `narrow`), never the key.
- Use `block.fieldName.eagerly().one()` or `.all()` for related content
- Use `{{ 'Text'|t }}` for all user-facing strings (translation support)
- Include SVGs with `{{ svg('@svg/path.svg')|attr({...}) }}`
- Add JavaScript as a module in `src/js/modules/` whose default export takes an element, and give the elements it works on `data-module="name"`. `site.js` loads a module only on pages whose markup names it.
- Keep CSRF tokens out of the page's shared markup, so pages can be cached whole (Blitz, a CDN). Formie forms add their own. In a hand-written form, use `{{ csrfInput({ async: true }) }}`, which fetches the token when the page loads; `->asyncCsrfInputs()` in `config/general.php` does that for every form, which then needs JavaScript to submit. In JavaScript, get it from `/actions/users/session-info` (with `Accept: application/json`), whose response includes `csrfTokenValue`.

### Accessibility Standards

Templates aim for WCAG 2.2 AA:
- Semantic HTML with landmarks (`<header>`, `<main>`, `<footer>`, and a labelled `<nav>` for each menu)
- A skip-to-content link, and a focus indicator that shows on any background (`--color-focus` and `--color-focus-contrast` in `site.css`)
- `aria-current` on the current page's links, and screen reader text on links that open in a new tab
- Decorative images and icons hidden from screen readers: `alt=""` on images, `aria-hidden="true"` on SVGs
- A mobile menu that works without JavaScript, and animations that respect reduced motion
- Form labels and error messages, from Formie

Editors choose heading levels in the Heading block, so a page's heading order depends on its content: check that each page has one `<h1>` and no skipped levels.

### Macros

Utility macros are available in `_macros/`:

**Classes (`_macros/classes.twig`):**
- `combine(base, extra)` - Joins a component's classes with a caller's `class` input
- `paddingTop(preference)` / `paddingBottom(preference)` - Responsive section padding
- `contentMaxWidth(preference)` - The cap for Medium and Narrow content widths
- `horizontalAlignment(preference)` - Horizontal alignment classes
- `verticalAlignment(preference)` - Flexbox vertical alignment
- `backgroundColor(preference)` - Background color utilities
- `textAlignment(preference)` - Text alignment classes
- `columnLayout(preference)` - Grid column layouts

**Media (`_macros/media.twig`):**
- `aspectRatio(preference)` - The class for an aspect ratio, or with `'value'` the ratio as a number. The one list of ratios; its keys match the Aspect Ratio field's options

**Transforms (`_macros/transforms.twig`):**
- `buildKey(segments)` - Builds transform keys from array of segments

**SVG (`_macros/svg.twig`):**
- `asset(asset, attrs, namespace)` - Inlines an uploaded SVG asset, cached so a remote volume isn't read on every request

### Creating New Blocks

1. Create a new entry type in the control panel, and add it to the Matrix field it belongs in (Content Blocks, Section Content, Content or Columns)
2. Add a template in `templates/_builder/blocks/{block-type-handle}.twig` (kebab-cased: `contentAndMedia` is `content-and-media.twig`)
3. Keep it to reading the block's fields and passing them to a component in `_components/`, adding one if none fits
4. (Optional) Add a context-specific override in `_builder/blocks/{context}/{block-type-handle}.twig`
5. If the block contains images, add its transform key suffix with `transforms.buildKey()` and its sizes to `config/custom.php`

**Example block template:**
```twig
{# Quote: a pull quote with who said it. #}
{% include '_components/quote' with {
    quote: block.quote,
    person: block.person,
} only %}
```

A block that renders blocks of its own passes `transformKey` and `contentWidth` on:

```twig
{% include '_builder/builder' with {
    context: 'my-block',
    blocks: block.content.eagerly().all(),
    transformKey: transformKey,
    contentWidth: contentWidth,
} only %}
```

## About Craft CMS

Craft is a content-first CMS that aims to make life enjoyable for developers and content managers alike. It is optimized for bespoke web and application development, offering developers a clean slate to build out exactly what they want, rather than wrestling with a theme.

Learn more about Craft at [craftcms.com](https://craftcms.com).

## Resources
- **[Documentation](https://craftcms.com/docs)** – Read the official docs.
- **[Guides](https://craftcms.com/guides)** – Follow along with the official guides.
- **[#craftcms](https://x.com/hashtag/craftcms)** – See the latest posts about Craft.
- **[Discord](https://craftcms.com/discord)** – Meet the community.
- **[Stack Exchange](http://craftcms.stackexchange.com)** – Get help and help others.
- **[CraftQuest](https://craftquest.io)** – Watch unlimited video lessons and courses.
- **[Craft CMS Newsletter](https://craftcms.com/newsletter)** – Stay in-the-know.
