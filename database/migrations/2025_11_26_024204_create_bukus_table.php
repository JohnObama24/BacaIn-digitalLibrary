<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->unsignedBigInteger('kategori_id');
            $table->string('isbn');
            $table->integer('stok');
            $table->integer('jumlah_halaman');
            $table->text('deskripsi');
            $table->string('cover')->nullable();
            $table->string('isi_buku')->nullable();
            $table->string('status_buku');
            $table->timestamps();


            $table->foreign('kategori_id')
                ->references('id')->on('kategoris')
                ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
