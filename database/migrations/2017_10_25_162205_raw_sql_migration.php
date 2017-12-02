<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use DB;

class RawSqlMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(
            "create table `users` (
                `id` int unsigned not null auto_increment primary key,
                `first_name` varchar(191) not null,
                `last_name` varchar(191) not null,
                `username` varchar(191) not null,
                `email` varchar(191) not null,
                `password` varchar(191) not null,
                `remember_token` varchar(100) null,
                `created_at` timestamp null,
                `updated_at` timestamp null
                )
                default character set utf8mb4 collate utf8mb4_unicode_ci"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(
            'DROP TABLE users'
        );
    }
}
