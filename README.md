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
├── _layouts/          Base HTML layouts
├── _partials/         Layout partials (header, footer, skip-link)
├── _components/       Reusable UI components; they take the values they show
│   └── cards/         One card per kind (content)
├── _builder/          Reads CMS content and passes it to components
│   ├── blocks/        One template per content block
│   ├── cards/         Reads an entry for its card (default.twig, or one per entry type)
│   ├── builder.twig   Block renderer
│   ├── section.twig   Section wrapper
│   └── container.twig Container wrapper
├── _pages/            Page-specific templates
├── _macros/           Twig macros for utilities
├── _dev/              Development utilities (breakpoint helper)
├── _critical-css/     Critical (inline) CSS partials
└── _errors/           Error page templates
```

### Template Hierarchy

Templates follow an inheritance pattern:

1. **base.twig** - HTML document structure, head tags, Vite script integration
2. **master.twig** - Extends base, adds header/footer and `<main>` landmark
3. **Page templates** - Specific page layouts that extend master

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

All templates follow WCAG 2.1 AA standards:
- Proper semantic HTML with landmarks (`<main>`, `<header>`, `<footer>`, `<nav>`)
- Skip-to-content link for keyboard navigation
- ARIA attributes where appropriate (`aria-label`, `aria-current`, `aria-hidden`)
- Proper heading hierarchy
- Decorative images/icons marked with `aria-hidden="true"`
- Form labels and error messages

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
- `sectionTextContainer(preference)` - Gutter for text beside full bleed media

**Media (`_macros/media.twig`):**
- `aspectRatio(preference)` - The class for an aspect ratio, or with `'value'` the ratio as a number. The one list of ratios; its keys match the Aspect Ratio field's options

**Transforms (`_macros/transforms.twig`):**
- `buildKey(segments)` - Builds transform keys from array of segments

### Creating New Blocks

1. Create a new entry type in Craft CMS Control Panel
2. Add a template in `templates/_builder/blocks/{block-name}.twig`
3. (Optional) Add context-specific override in `_builder/blocks/{context}/{block-name}.twig`
4. If the block contains images, add transform configuration in `config/custom.php`

**Example block template:**
```twig
{# My Block - Description of what this block does #}
{% set myData = block.myField.eagerly().all() %}

<div class="my-block">
    {# Block content #}
</div>
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
