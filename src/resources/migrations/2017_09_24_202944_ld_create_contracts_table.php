<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class LdCreateContractsTable
 */
class LdCreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billwerk_contracts', function (Blueprint $table) {
            $table->string('id', 24)->primary();
            $table->timestamps();

            $table->unsignedInteger('customer_id');
            $table->string('plan_id', 24);
            $table->string('plan_variant_id', 24)->nullable();
            $table->string('reference_code', 9)->unique();
            $table->dateTime('end_date')->nullable();

            $table->foreign('customer_id')
                ->references('id')
                ->on('billwerk_customers')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billwerk_contracts');
    }
}
