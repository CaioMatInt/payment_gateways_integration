<?php

use App\DTOs\Payment\PaymentCreationDto;
use App\Enums\Payment\PaymentCurrencyEnum;
use App\Enums\Payment\PaymentGenericStatusEnum;
use App\Enums\PaymentMethod\PaymentMethodEnum;
use App\Models\Payment;
use App\Models\PaymentGenericStatus;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\PaymentGenericStatus\PaymentGenericStatusService;
use App\Services\PaymentMethod\PaymentMethodService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentService', function () {

    beforeEach(function () {

    });

    test('can create a payment', function () {
        $user = User::factory()->create();
        $this->actingAs($user);

        $initialPaymentStatus = PaymentGenericStatus::factory()->create([
            'name' => PaymentGenericStatusEnum::PENDING,
        ]);

        $paymentGenericStatusService = Mockery::mock(PaymentGenericStatusService::class);
        $paymentGenericStatusService->shouldReceive('getCachedInitialStatus')->andReturn($initialPaymentStatus);

        $paymentMethod = PaymentMethod::factory()->create([
            'name' => PaymentMethodEnum::CREDIT_CARD->value,
        ]);

        $paymentMethodService = Mockery::mock(PaymentMethodService::class);
        $paymentMethodService->shouldReceive('findCachedByName')->andReturn($paymentMethod);

        $amount = 100;
        $paymentCreationDto = new PaymentCreationDto([
            'payment_method' => PaymentMethodEnum::CREDIT_CARD->value,
            'amount' => $amount,
            'currency' => PaymentCurrencyEnum::USD->value,
        ]);

        $paymentService = new PaymentService(
            new Payment(),
            $paymentGenericStatusService,
            $paymentMethodService,
        );

        $payment = $paymentService->create($paymentCreationDto);

        expect($payment->amount)->toBe(100)
            ->and($payment->user_id)->toBe($user->id)
            ->and($payment->company_id)->toBe($user->company_id)
            ->and($payment->currency)->toBe(PaymentCurrencyEnum::USD->value)
            ->and($payment->payment_generic_status_id)->toBe($initialPaymentStatus->id)
            ->and($payment->payment_method_id)->toBe($paymentMethod->id);

        $this->assertDatabaseHas('payments', [
            'amount' => $amount,
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'currency' => PaymentCurrencyEnum::USD->value,
            'payment_generic_status_id' => $initialPaymentStatus->id,
            'payment_method_id' => $paymentMethod->id,
        ]);
    });
});
