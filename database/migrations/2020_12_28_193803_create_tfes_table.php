<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\User;

class CreateTfesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('tves', function (Blueprint $table) {
            $table->id();
            $table->integer('document_id')->unsigned()->index();
            $table->string('theme');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('auteurs');
            $table->integer('status')->default(0);
            $table->string('annee_de_realisation');
            $table->string('groupe_pedagogique');
            $table->string('tuteur_de_stage');
            $table->string('lieu_de_stage');
            $table->string('email_tuteur');
            $table->string('maitre_memoire');
            $table->string('email_maitre_memoire');
            $table->timestamp('create_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tves');
    }
}
