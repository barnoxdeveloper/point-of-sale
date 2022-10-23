<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->string('slug');
            $table->integer('category_id');
            $table->integer('store_id');
            $table->integer('old_price');
            $table->integer('new_price');
            $table->integer('limit_stock');
            $table->integer('stock');
            $table->enum('type', ['PCS', 'PACK', 'LITER', 'ROLL', 'METER', 'KILOGRAM']);
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status', ['ACTIVE', 'NON-ACTIVE'])->default('NON-ACTIVE');
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
        Schema::dropIfExists('products');
    }
};
