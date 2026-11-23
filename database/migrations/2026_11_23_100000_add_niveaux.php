<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/** Uniquement des ajouts : aucune colonne ni ligne existante n'est supprimée. */
class AddNiveaux extends Migration
{
    public function up()
    {
        Schema::create('niveaux', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('nom', 50);
            $table->unsignedTinyInteger('ordre');
            $table->timestamps();
        });
        foreach (['licence' => 'Licence', 'master' => 'Master', 'doctorat' => 'Doctorat'] as $code => $nom) {
            DB::table('niveaux')->insert([
                'code' => $code, 'nom' => $nom, 'ordre' => DB::table('niveaux')->count() + 1,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }

        Schema::table('filieres', function (Blueprint $table) {
            $table->unsignedBigInteger('niveau_id')->nullable()->index();
            $table->boolean('active')->default(true);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('niveau_id')->nullable()->index();
        });
        Schema::table('tves', function (Blueprint $table) {
            $table->unsignedBigInteger('niveau_id')->nullable()->index();
            $table->unsignedBigInteger('filiere_id')->nullable()->index();
            $table->text('motif_rejet')->nullable();
        });
        Schema::create('journal_admin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('auteur');
            $table->string('action', 100);
            $table->text('details')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();
        });

        $this->backfill();
    }

    private function backfill()
    {
        $niveaux = DB::table('niveaux')->pluck('id', 'code');
        $parNiveau = [
            'licence' => ['GEI', 'GC', 'MS', 'GE', 'GMP'],
            'master' => ['BTPCD', 'FC'],
        ];
        foreach ($parNiveau as $code => $noms) {
            DB::table('filieres')->whereIn('nom', $noms)->update(['niveau_id' => $niveaux[$code]]);
            DB::table('users')->whereIn('entity', $noms)->update(['niveau_id' => $niveaux[$code]]);
        }
        foreach (DB::table('filieres')->whereNotNull('niveau_id')->get() as $filiere) {
            DB::table('tves')->where('groupe_pedagogique', $filiere->nom)->update([
                'niveau_id' => $filiere->niveau_id,
                'filiere_id' => $filiere->id,
            ]);
        }
    }

    public function down()
    {
        // Volontairement sans effet : annuler supprimerait des données saisies depuis.
    }
}
