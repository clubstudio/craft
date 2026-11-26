# Agent instructions

This repository is `clubstudio/craft`, a Craft CMS 5 starter, not a site. New projects are created
from it with `composer create-project`, and each gets its own copy, so a change here only reaches
projects created afterwards.

## What ships to projects

Everything in the repository is copied into a new project, then `post-create-project-cmd` in
`composer.json` swaps or removes these files:

- `composer.json.default` becomes the project's `composer.json`.
- `README.md.default` becomes the project's `README.md`.
- This file and `CHANGELOG.md` are removed.

Keep templates, config and project config generic: no client names, content or credentials.
Instructions and coding rules for projects are added to each project separately, so don't add
project-level guidance here. How the templates are structured is described in `README.md`.

Record each change under the next release in `CHANGELOG.md`, with an upgrade note when an existing
site would have to make it by hand.

## Commands

Everything runs through DDEV:

```bash
ddev start
ddev composer install
ddev npm install
ddev npm run dev                  # Vite dev server; pages render without styles unless it's running
ddev npm run build                # production build to web/dist/
ddev craft up                     # run migrations and apply config/project/ after a pull
```

## Testing a change

Push the change to a branch, then create a project from it in an empty folder:

```bash
ddev config --project-type=craftcms --docroot=web
ddev composer create-project "clubstudio/craft:dev-<branch>"
ddev craft install
```

Check that the `.default` files were swapped in, this file and `CHANGELOG.md` are gone, and the
site renders.
