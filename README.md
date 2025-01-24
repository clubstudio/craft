<img src="./src/svg/logo.svg" width="64">

# Craft CMS Boilerplate

A scaffolding package to help you hit the ground running with your next [Craft CMS](https://craftcms.com) project.

## What's included?

* [Craft CMS](https://craftcms.com)
* [Tailwind CSS](https://tailwindcss.com)
* [DDEV](https://ddev.com/) configuration
* A sensible directory structure
* Commonly used Craft CMS plugins

## Getting Started

Install or update [DDEV](https://ddev.com/), then follow these steps:

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
   ddev composer create -y "clubstudio/craft"
   ```
4. Run `ddev craft install` and answer each prompt
5. Run `ddev launch` to view the project in your browser

Next, feel free to read the offical [Craft installation documentation](https://craftcms.com/docs/5.x/install.html).

## Developing

After setting up Craft, you're almost ready to start building your new project! Before getting started you'll want to configure Vite and pull in all frontend dependencies.

First, add the following to your `.ddev/config.yaml`:

```
web_extra_exposed_ports:
    - name: vite
      container_port: 5173
      http_port: 5172
      https_port: 5173
```

and run `ddev restart`.

Next, install frontend dependencies by running:

```
ddev npm install
```

Once the dependencies have been installed, you can compile assets and start a watcher using:

```
ddev npm run dev
```

That's it! Happy coding! 🎉

When you've finished working, run `ddev stop` to shut down the project containers and free up resources.

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
