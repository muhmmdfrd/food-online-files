<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_path', 512)->nullable();
            $table->string('file_name', 128)->nullable();
            $table->string('origin_file_name', 128)->nullable();
            $table->float('file_size');
            $table->integer('file_type')->nullable();
            $table->string('note', 255)->nullable();
            $table->integer('upload_type')->nullable();
            $table->bigInteger('reference_id')->nullable();
            $table->integer('data_status_id')->default(1)->nullable();
            $table->string('unique_id', 50)->nullable();
            $table->timestamps();
            
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
