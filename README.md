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
3. Scaffold the project from this starter project. Craft's installer runs automatically at the end, so answer each prompt when asked:
   ```
   ddev composer create -y "clubstudio/craft"
   ```
4. Run `ddev launch` to view your site in the browser
5. Log in to the Craft control panel at `/admin` (or run `ddev launch /admin`) with the admin account you created during install

Next, feel free to read the official [Craft installation documentation](https://craftcms.com/docs/5.x/install.html).

## Developing

After setting up Craft, you're almost ready to start building! This starter ships a `.ddev/config.vite.yaml` config that exposes the Vite dev server port (5173), so there's no DDEV configuration to edit by hand.

Apply the shipped config and pull in frontend dependencies:

```
ddev restart
ddev npm install
```

Once the dependencies have been installed, you can compile assets and start a watcher (with hot-module reloading) using:

```
ddev npm run dev
```

Leave this running while you work. When you're ready to generate optimised, production-ready assets, run:

```
ddev npm run build
```

This writes hashed, cache-busted files to `web/dist/`.

That's it! Happy coding! 🎉

When you've finished working, run `ddev stop` to shut down the project containers and free up resources.

## Project Config

Craft's [project config](https://craftcms.com/docs/5.x/system/project-config.html) is the source of truth for your site's schema — fields, entry types, sections and settings. It's stored as version-controlled YAML in `config/project/`.

- After pulling changes from a teammate, sync your database with:
  ```
  ddev craft project-config/apply
  ```
- In `dev`, `CRAFT_ALLOW_ADMIN_CHANGES=true` lets you edit the schema from the control panel. Craft writes those edits back to `config/project/`, so commit the updated YAML alongside your code.

## Template System

This boilerplate uses a flexible content builder pattern with reusable components and macros.

### Directory Structure

```
templates/
├── _layouts/          Base HTML layouts
├── _partials/         Layout partials (header, footer, skip-link)
├── _components/       Reusable UI components
├── _builder/          Content builder system
│   ├── blocks/        Individual content blocks
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
    transformKey: 'section:contain'
} only %}
```

**How it works:**
- Iterates through content blocks
- Includes appropriate templates with context-aware fallbacks
- Supports nested blocks for complex layouts

**Template Resolution Order:**
1. `_builder/blocks/{context}/{block-type}.twig` (context-specific override)
2. `_builder/blocks/{block-type}.twig` (default)

**Example contexts:** `page`, `hero`, `footer`, `columns/2`, `content-cards`

### Image Transform System

Images use contextual transforms defined in `config/custom.php` for optimal responsive delivery:

**Transform Key Pattern:**
Keys are built hierarchically as blocks nest:
- `section:none` - Full-width section
- `section:contain` - Constrained section
- `section:contain:columns:2` - 2-column layout in constrained section
- `section:contain:text-and-media` - Text + media block

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
In development mode, HTML comments show the transform key used for each image.

### Template Conventions

- Block templates receive `block` and `transformKey` variables
- Use `block.fieldName.eagerly().one()` or `.all()` for related content
- Use `{{ 'Text'|t }}` for all user-facing strings (translation support)
- Include SVGs with `{{ svg('@svg/path.svg')|attr({...}) }}`

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
- `paddingTop(preference)` - Responsive top padding
- `paddingBottom(preference)` - Responsive bottom padding
- `maxWidth(preference)` - Maximum width with horizontal padding
- `horizontalAlignment(preference)` - Horizontal alignment classes
- `verticalAlignment(preference)` - Flexbox vertical alignment
- `backgroundColor(preference)` - Background color utilities
- `textAlignment(preference)` - Text alignment classes
- `aspectRatio(preference)` - Aspect ratio for media
- `spacer(preference)` - Vertical spacing utilities
- `columnLayout(preference)` - Grid column layouts

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
