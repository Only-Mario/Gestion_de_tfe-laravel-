<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTfeModificationsTable extends Migration
{
    public function up()
    {
        Schema::create('tfe_modifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tfe_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('motif');
            $table->string('justificatif_path');
            $table->string('ancien_document_path');
            $table->longText('anciennes_donnees');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tfe_modifications');
    }
}
