<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class DropAllTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rawdropall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drops all tables using raw SQL';

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
        $this->dropUsers();
        $this->dropPosts();
        $this->dropComments();

    }

    /**
     * Drops the users table
     */
    private function dropUsers()
    {
        try {
            $this->info("Dropping table: users");

            DB::connection()->getPdo()->exec(
                "drop table `users`"
            );

        } catch (\PDOException $e) {
            $this->error("Users table doesn't exist!");
        }

    }

    /**
     * Drops the posts table
     */
    private function dropPosts()
    {
        try {
            $this->info("Dropping table: posts");

            DB::connection()->getPdo()->exec(
                "drop table `posts`"
            );

        } catch (\PDOException $e) {
            $this->error("Posts table doesn't exist!");
        }
    }

    /**
     * Drops the comments table
     */
    private function dropComments()
    {
        try {
            $this->info("Dropping table: comments");

            DB::connection()->getPdo()->exec(
                "drop table `comments`"
            );

        } catch (\PDOException $e) {
            $this->error("Comments table doesn't exist!");
        }
    }
}
