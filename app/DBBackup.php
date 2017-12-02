<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Log;
use DB;
use Storage;
use Artisan;

class DBBackup extends Model
{
    /**
     * Entry function for backing up each table in the DB
     * @param  string $table_name   The name of the table to be backed up
     * @return null|boolean         null if successful, false otherwise
     */
    public function backUpTable($table_name)
    {
        switch ($table_name) {
            case 'users':
                $this->backUpUsers();
                break;
            case 'posts':
                $this->backUpPosts();
                break;
            case 'comments':
                $this->backUpComments();
                break;
            case 'all':
                $this->backUpAll();
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Backs up the users table using mysqldump
     * @return null
     */
    private function backUpUsers()
    {
        $filename = "backup-users-" . date("d-m-Y H:i:s") . ".sql.gz";
        $mime = "application/x-gzip";

        header( "Content-Type: " . $mime );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $cmd = "/Applications/MAMP/Library/bin/mysqldump -u 'root' --password='root' nerddit 'users' | gzip --best";

        passthru( $cmd );
        return;

    }

    /**
     * Backs up the posts table using mysqldump
     * @return null
     */
    private function backUpPosts()
    {
        $filename = "backup-posts-" . date("d-m-Y H:i:s") . ".sql.gz";
        $mime = "application/x-gzip";

        header( "Content-Type: " . $mime );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $cmd = "/Applications/MAMP/Library/bin/mysqldump -u 'root' --password='root' nerddit 'posts' | gzip --best";

        passthru( $cmd );
        return;

    }

    /**
     * Backs up the comments table using mysqldump
     * @return null
     */
    private function backUpComments()
    {
        $filename = "backup-comments-" . date("d-m-Y H:i:s") . ".sql.gz";
        $mime = "application/x-gzip";

        header( "Content-Type: " . $mime );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $cmd = "/Applications/MAMP/Library/bin/mysqldump -u 'root' --password='root' nerddit 'comments' | gzip --best";

        passthru( $cmd );
        return;


    }

    /**
     * Backs up all tables using mysqldump
     * @return null
     */
    private function backUpAll()
    {
        $filename = "backup-" . date("d-m-Y H:i:s") . ".sql.gz";
        $mime = "application/x-gzip";

        header( "Content-Type: " . $mime );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $cmd = "/Applications/MAMP/Library/bin/mysqldump -u 'root' --password='root' nerddit | gzip --best";

        passthru( $cmd );
        return;
    }

    /**
     * Restores all tables using an unzipped .sql file
     * @param  File $file The file input by an Admin User on the frontend
     * @return null
     */
    public function restoreTable($file)
    {
        Storage::disk('local')->put($file, file_get_contents($file));

        Artisan::call('migrate:rawdropall');

        DB::connection()->getPdo()->exec(Storage::get($file));

        return;

    }
}
