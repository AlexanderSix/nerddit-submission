<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Log;

class RawSqlMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rawsql {--production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The database migration using Raw SQL';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createUsersTable();
        $this->createPostsTable();
        $this->CreateCommentsTable();

        if ($this->option('production') == 1) {
            // Run Production seeder
            $this->call('db:seed', ['--class' => 'TestSeeder']);
        } else {
            // Run Testing seeder
            $this->call('db:seed', ['--class' => 'TestSeeder']);
        }

    }

    /**
     * Creates the users table
     */
    private function createUsersTable()
    {
        try {
            $this->info("Creating table: users");

            DB::connection()->getPdo()->exec(
                "create table `users` (
                    `id` int unsigned not null auto_increment primary key,
                    `first_name` varchar(191) not null,
                    `last_name` varchar(191) not null,
                    `username` varchar(191) not null unique,
                    `email` varchar(191) not null unique,
                    `password` varchar(191) not null,
                    `is_admin` boolean not null,
                    `remember_token` varchar(100) null,
                    `created_at` timestamp null,
                    `updated_at` timestamp null
                    )
                    default character set utf8mb4 collate utf8mb4_unicode_ci"
            );

        } catch (\PDOException $e) {
            $this->error("Users table already exists! Skipping...");
        }


    }

    /**
     * Creates the posts table
     */
    private function createPostsTable()
    {
        try {
            $this->info("Creating table: posts");

            DB::connection()->getPdo()->exec(
                "create table `posts` (
                    `id` int unsigned not null auto_increment primary key,
                    `user_id` int not null,
                    `title` varchar(191) not null,
                    `content` text not null,
                    `link` text null,
                    `likes` int not null,
                    `created_at` timestamp null,
                    `updated_at` timestamp null
                    )
                default character set utf8mb4 collate utf8mb4_unicode_ci"
            );

        } catch (\PDOException $e) {
            $this->error("Posts table already exists! Skipping...");
        }


    }

    /**
     * Creates the comments table
     */
    private function CreateCommentsTable()
    {
        try {
            $this->info("Creating table: comments");

            DB::connection()->getPdo()->exec(
                "create table `comments` (
                    `id` int unsigned not null auto_increment primary key,
                    `user_id` int not null,
                    `post_id` int not null,
                    `content` text not null,
                    `created_at` timestamp null,
                    `updated_at` timestamp null
                )
                default character set utf8mb4 collate utf8mb4_unicode_ci"
            );

        } catch (\PDOException $e) {
            $this->error("Comments table already exists! Skipping...");
        }
    }
}
