<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

use function Laravel\Prompts\alert;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\warning;

/**
 * Class DemoCommand
 *
 * This command shows a menu with some options to init the application for demo purposes
 */
class DemoCommand extends Command
{
    protected $signature = 'mc:demo';

    protected $description = 'This command shows a menu with some options to init the application for demo purposes';

    /**
     * Execute the console command.     */
    public function handle() : void
    {

        info(<<<'TXT'
MOCA Conference Management System
====================================================
Author: Mircha Emanuel `ryuujin` D'Angelo
GitHub: https://github.com/mirchaemanuel/moca-conf
====================================================
TXT
        );
        warning('This app is a demo project for my speech: Rapid Application Development with Laravel and Filament and it is not intended for production use.');

        if(App::environment() !== 'local') {
            alert('This command can run only in local environment');
            return;
        }

        /** @var string $option */
        $option = select(
            label: 'What do you want to do?',
            options: [
                'migrateFresh' => 'Run a fresh migration',
                'migrateFreshAndSeed' => 'Run a fresh migration and seed the database',
                'seed' => 'Seed the database',
                'createDemoUser' => 'Create a demo user',
            ]
        );

        if (method_exists($this, $option)) {
            $this->{$option}();
        } else {
            alert('Invalid option');
        }
    }

    private function migrateFresh(): void
    {
        info('Running Migration');
        $this->call('migrate'); //asks to create the database if it does not exist
        $this->call('migrate:fresh');
    }

    private function migrateFreshAndSeed(): void
    {
        info('Running Migration with seed');
        $this->call('migrate'); //asks to create the database if it does not exist
        $this->call('migrate:fresh', ['--seed' => true]);
    }

    private function seed(): void
    {
        info('Seeding the database');
        $this->call('db:seed');
    }

    private function createDemoUser(): void
    {
        info('Creating a demo user');
        $faker = FakerFactory::create();

        $email = $faker->safeEmail;
        $password = Str::password(8, letters: true, numbers: false, symbols: false);
        $name = $faker->name;

        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        info("User created with email: $email and password: $password");
    }
}
