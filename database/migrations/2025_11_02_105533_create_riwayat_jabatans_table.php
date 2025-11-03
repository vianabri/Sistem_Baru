<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_jabatans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();

            $table->date('tanggal')->index();
            $table->enum('jenis', ['Mutasi', 'Promosi', 'Demosi', 'Rotasi'])->index();

            // before/after jabatan dan unit
            $table->string('dari_jabatan', 100)->nullable();
            $table->string('ke_jabatan', 100)->nullable();

            $table->foreignId('dari_org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();
            $table->foreignId('ke_org_unit_id')->nullable()->constrained('org_units')->nullOnDelete();

            $table->text('keterangan')->nullable();

            // pencatat
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_jabatans');
    }
};
