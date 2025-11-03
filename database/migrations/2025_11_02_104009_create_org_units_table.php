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
    Schema::create('org_units', function (Blueprint $table) {
        $table->id();
        $table->string('code', 30)->unique();        // misal: CBG-A, KC-01, UNIT-CR
        $table->string('name', 120);                 // nama unit / cabang
        $table->enum('type', ['Cabang', 'Koorcab', 'Unit'])->index();
        $table->foreignId('parent_id')->nullable()->constrained('org_units')->nullOnDelete(); // tree
        $table->foreignId('head_pegawai_id')->nullable()->constrained('pegawais')->nullOnDelete(); // pimpinan unit
        $table->string('alamat')->nullable();
        $table->string('telepon')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('org_units');
    }
};
