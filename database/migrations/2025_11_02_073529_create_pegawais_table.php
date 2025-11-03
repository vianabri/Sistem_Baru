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
    Schema::create('pegawais', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->unique()->nullable()->constrained()->nullOnDelete();

        // Identitas resmi
        $table->string('nip', 30)->unique();
        $table->string('nama_lengkap', 100);
        $table->string('tempat_lahir', 100)->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

        // Kontak
        $table->string('email_kantor')->nullable()->unique();
        $table->string('no_hp', 25)->nullable();

        // Penempatan / Jabatan
        $table->string('jabatan', 100);
        $table->string('departemen', 100)->nullable();
        $table->string('lokasi_kerja', 100)->nullable();

        // Status Kepegawaian
        $table->date('tanggal_masuk')->nullable();
        $table->enum('status_kepegawaian', ['Tetap', 'Kontrak', 'Magang'])->default('Tetap');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
