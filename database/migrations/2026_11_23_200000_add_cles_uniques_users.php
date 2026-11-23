<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Interdit tout nouvel email ou matricule en double, sans toucher aux doublons existants :
 * seul le compte le plus ancien reçoit la clé, les autres gardent NULL.
 */
class AddClesUniquesUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_cle')->nullable();
            $table->string('matricule_cle')->nullable();
        });
        foreach ([['email_cle', 'email', 'LOWER(TRIM(email))'], ['matricule_cle', 'matricule', "UPPER(REPLACE(TRIM(matricule), ' ', ''))"]] as [$cle, $colonne, $sql]) {
            DB::statement("UPDATE users SET $cle = $sql WHERE id IN (
                SELECT id FROM (SELECT MIN(id) AS id FROM users WHERE $colonne IS NOT NULL AND TRIM($colonne) <> '' GROUP BY $sql) premiers
            )");
        }
        Schema::table('users', function (Blueprint $table) {
            $table->unique('email_cle', 'users_email_cle_unique');
            $table->unique('matricule_cle', 'users_matricule_cle_unique');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_cle_unique');
            $table->dropUnique('users_matricule_cle_unique');
            $table->dropColumn(['email_cle', 'matricule_cle']);
        });
    }
}
