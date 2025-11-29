<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('buku_id');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian');
            $table->date('tanggal_kembali_actual')->nullable(); // tanggal user beneran kembalikan

            // Status peminjaman
            $table->enum('status_peminjaman', [
                'pending',     
                'approved',
                'rejected',
                'dipinjam',
                'dikembalikan',
                'selesai'
            ])->default('pending');

            // Denda
            $table->decimal('denda', 10, 2)->default(0);
            $table->boolean('denda_lunas')->default(true);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('buku_id')
                ->references('id')->on('bukus')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
