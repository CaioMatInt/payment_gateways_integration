<?php

use App\Models\Company;
use App\Models\PaymentGateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payment_gateway_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PaymentGateway::class);
            $table->foreignIdFor(Company::class);
            $table->text('key');
            $table->string('type')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_gateway_keys');
    }
};
