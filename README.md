# MOCA CONF

IT IS JUST A DEMO PROJECT FOR THE SPEECH

IT IS STILL UNDER ACTIVE DEVELOPMENT

## What is it?

Welcome to the moca-conf application repository! This application is designed to help organize conferences, manage  
speakers, and schedule programs using Laravel and Filament.

## About the Talk

This repository is part of my upcoming speech at [MOCA 2024](https://moca.camp), titled **"Rapid Application Development with Laravel and Filament"**.

During the talk, I will demonstrate how to quickly build robust applications using Laravel and Filament,  
focusing on how these tools can streamline the development process.

## Application Features

- **Conference Management:** Easily create and manage conferences.
- **Speaker Management:** Add, edit, and organize speaker information.
- **Program Scheduling:** Plan and schedule conference programs and sessions.

and why not, a little of AI to help us to manage the conference.

## Application Stages

### 00: Installing Laravel

In this stage, we will install Laravel and set up the project.

```bash
laraven new moca-conf
```

I've choosen SQLite as the database for this project and Pest as the testing framework.

### 01: INIT TailwindCSS, Pest, Larapint, PHPStan and Larastan

- **TailwindCSS:** A utility-first CSS framework for rapidly building custom designs.
- **Laravel Pint:** Code style fixer for minimalists
- **Pest:** A delightful PHP Testing Framework with a focus on simplicity.
- **PHPStan:** A PHP Static Analysis Tool that focuses on finding errors in your code without actually running it.
- **Larastan:** A PHPStan wrapper for Laravel that focuses on finding errors in your Laravel code.

#### TailwindCSS

```bash
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p
```

or with Bun

```
bun install -D tailwindcss postcss autoprefixer
bun tailwindcss init -p
```

then follow the instructions in the [TailwindCSS documentation](https://tailwindcss.com/docs/guides/laravel).

#### Laravel Pint

```bash
composer require laravel/pint --dev
```

#### Pest

In the laravel installation wizard we've choosen Pest as the testing framework, so we don't need to install it. I'm
installing
some additional plugins and package to help us to write tests.

```bash
composer require pestphp/pest-plugin-livewire --dev
composer require pestphp/pest-plugin-faker --dev
```

#### PHPStan and Larastan

```bash
composer require --dev "larastan/larastan:^2.0"
```

configure `phpstan.neon` file

```neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:

    paths:
        - app/

    # Level 9 is the highest level
    level: 9

#    ignoreErrors:
#        - '#PHPDoc tag @var#'
#
#    excludePaths:
#        - ./*/*/FileToBeExcluded.php
#
#    checkMissingIterableValueType: false
```

and run first analysis

```bash
./vendor/bin/phpstan analyse
```

in first instance we should fix `User` model specifying the factory class.
