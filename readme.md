# Kirby CLI

Kirby's command line interface helps you with regular tasks like the installation of the Kirby starterkit and updating your Kirby installation. It also offers a comfortable way to install templates, snippets, controllers and blueprints.

## Requirements

The Kirby CLI is being installed with Composer, the PHP package manager. For installation instructions for Composer, please visit the [Composer website](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

## Installation 

After installing composer, you can install Kirby CLI by running the following command:

```
composer global require getkirby-v2/cli
```

Make sure to place the…

```
~/.composer/vendor/bin
```

…directory in your PATH so the kirby executable can be located by your system.

### Troubleshooting

It can happen that you get into dependency issues with other global composer packages. In this case, try to install the CLI to a custom location without the `global` option and set the path accordingly. 

```
composer require getkirby-v2/cli
```

## Update

To update the CLI, run the following composer command: 

```
composer global update getkirby-v2/cli
```

## Commands

### kirby install

Creates a new Kirby installation. 

```
kirby install
```

By default the starterkit will be installed in a new directory called kirby. You can specify the directory with the second argument

```
kirby install mywebsite
```

You can also install any other official Kirby kit (starterkit, plainkit, langkit) with the `--kit` option

```
kirby install --kit langkit
kirby install --kit plainkit
kirby install --kit starterkit
```

#### Dev version

You can also use the install command to install the latest version from the development branch to test beta features. This is not recommended for production.

```
kirby install --dev
kirby install --dev --kit langkit
kirby install --dev --kit plainkit
kirby install --dev --kit starterkit
```

### kirby install:core

If you've already setup your site structure and you want to add the Kirby core, you can run kirby install:core instead of kirby install

```
kirby install:core
```

#### Dev version

To install the core from the develop branch, use the --dev flag:

```
kirby install:core --dev
```

### kirby install:panel

If you want to add the panel to an existing installation, you can run…

```
kirby install:panel
```

#### Dev version

To install the panel from the develop branch, use the --dev flag:

```
kirby install:panel --dev
```

### kirby install:index.php

Sometimes it might make sense to reinstall the index.php or use this in combination with kirby install:core and kirby install:panel to create your own folder structure:

```
kirby install:index.php
```

### kirby install:htaccess

If you add the .htaccess manually or reinstall it, you can run…

```
kirby install:htaccess
```

****

### kirby uninstall

You can wipe the current Kirby installation with…

```
kirby uninstall
```

This will remove the core, all kirby files in your document root, the thumbs folder and the panel. Your content and assets won't be removed. 

### kirby uninstall:panel

Uninstall the panel only with…

```
kirby uninstall:panel
```

****

### kirby update

To update an existing Kirby installation, you can run…

```
kirby update
```

This will update the kirby and panel folder, if it exists. You must run this within an existing Kirby installation, which follows Kirby's default folder structure. 

#### Dev version

To update to the latest version from the develop branch, use the --dev flag.

```
kirby update --dev
```

****

### kirby plugin:install

You can install Kirby plugins with a valid package.json file and plugin type field from any repo on Github. 

```
kirby plugin:install getkirby-plugins/cachebuster-plugin
```

You must pass the correct Github repository path as the second argument. The package.json file has to contain a `type` field with one of the following values: 

- kirby-plugin (will be installed in /site/plugins)
- kirby-field (will be installed in /site/fields)
- kirby-tag (will be installed in /site/tags)

If you want to install a plugin from Kirby's official Plugins organisation (<https://github.com/getkirby-plugins>) you can omit the full path and just specify the repo name: 

```
kirby plugin:install cachebuster-plugin
```

### kirby plugin:update

To update an existing plugin, you can use…

```
kirby plugin:update getkirby-plugins/cachebuster-plugin
```

The shortcut for official plugins is working here as well:

```
kirby plugin:update cachebuster-plugin
```

****

### kirby make:blueprint

To create a boilerplate blueprint for a particular template, you can use the kirby make:blueprint command:

```
kirby make:blueprint projects
```

This will start the blueprint builder and take you through a couple questions to setup the blueprint options.

If you prefer to just create a clean boilerplate, you can use…

```
kirby make:blueprint projects --bare
```

This will create a fresh blueprint: /site/blueprints/projects.yml

### kirby make:controller

To create a boilerplate controller for a particular template, you can use the kirby make:controller command:

```
kirby make:controller projects
```

This will create a fresh controller: /site/controllers/projects.php

### kirby make:snippet

To create a snippet, you can use the kirby make:snippet command:

```
kirby make:snippet header
```

This will create a fresh snippet: /site/snippets/header.php

### kirby make:template

To create a template, you can use the kirby make:template command:

```
kirby make:template projects
```

This will create a fresh template: /site/templates/template.php

### kirby make:user

To create a new user account, you can use the kirby make:user command:

```
kirby make:user -u home -p simpson -e homer@simpsons.com
```

The email is optional. You can also use the full option names:

```
kirby make:user --username home --password simpson --email homer@simpsons.com
```

This will create a new user in /site/accounts/homer.php

### kirby make:tag

To create a new kirbytext tag template, you can use the kirby make:tag command:

```
kirby make:tag mytag
```

This will create a new tag boilerplate in /site/tags/mytag.php

### kirby make:plugin

To create a new plugin boilerplate, you can use the kirby make:plugin command:

```
kirby make:plugin myplugin
```

This will create a new plugin boilerplate in /site/plugins/myplugin/myplugin.php

****

### kirby delete:blueprint

To delete a blueprint, you can run the delete:blueprint command, which will give you a list of all deleteable blueprints

```
kirby delete:blueprint
```

To delete a particular blueprint, run…

```
kirby delete:blueprint myblueprint
```

### kirby delete:controller

To delete a controller, you can run the delete:controller command, which will give you a list of all deleteable controllers

```
kirby delete:controller
```

To delete a particular controller, run…

```
kirby delete:controller mycontroller
```

### kirby delete:plugin

To delete a plugin, you can run the delete:plugin command, which will give you a list of all deleteable plugins

```
kirby delete:plugin
```

To delete a particular plugin, run…

```
kirby delete:plugin myplugin
```

### kirby delete:snippet

To delete a snippet, you can run the delete:snippet command, which will give you a list of all deleteable snippets

```
kirby delete:snippet
```

To delete a particular snippet, run…

```
kirby delete:snippet mysnippet
```

### kirby delete:tag

To delete a tag, you can run the delete:tag command, which will give you a list of all deleteable tags

```
kirby delete:tag
```

To delete a particular tag, run…

```
kirby delete:tag mytag
```

### kirby delete:template

To delete a template, you can run the delete:template command, which will give you a list of all deleteable templates

```
kirby delete:template
```

To delete a particular template, run…

```
kirby delete:template mytemplate
```

### kirby delete:user

To delete a user account, you can run the delete:user command, which will give you a list of all deleteable users

```
kirby delete:user
```

To delete a particular user, run…

```
kirby delete:user username
```


****

### kirby clear:cache

Clears the cache directory

```
kirby clear:cache
```

### kirby clear:thumbs

Delets all thumbnails in /thumbs

```
kirby clear:thumbs
```

****

### kirby version

Prints the current version of the core, the panel and the toolkit

```
kirby version
```

****

## License 

<http://www.opensource.org/licenses/mit-license.php>

## Author

Bastian Allgeier   
<bastian@getkirby.com>  
<https://getkirby.com>  
<http://twitter.com/getkirby>
