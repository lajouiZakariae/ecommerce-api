<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\CouponCode;
use finfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\CouponCodeController
 */
final class CouponCodeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $couponCodes = CouponCode::factory()->count(3)->create();

        $response = $this->get(route('coupon-codes.index'));

        $response->assertOk();

        $response->assertJsonStructure(['*' => ['id', 'code', 'amount']]);

        $response->assertJson(function (AssertableJson $json) {
            $json->has(3);
        });
    }

    #[Test]
    public function store_saves(): void
    {
        $code = $this->faker->word;
        $amount = $this->faker->numberBetween(0, 100);

        $response = $this->post(route('coupon-codes.store'), [
            'code' => $code,
            'amount' => $amount,
        ]);

        $couponCodes = CouponCode::query()
            ->where('code', $code)
            ->where('amount', $amount)
            ->get();
        $this->assertCount(1, $couponCodes);
        $couponCode = $couponCodes->first();

        $response->assertCreated();
    }

    #[Test]
    public function show_behaves_as_expected(): void
    {
        $couponCode = CouponCode::factory()->create();

        $response = $this->get(route('coupon-codes.show', $couponCode));

        $response->assertOk();
        $response->assertJsonStructure(['id', 'code', 'amount']);
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $couponCode = CouponCode::factory()->create();
        $code = $this->faker->word;
        $amount = $this->faker->numberBetween(0, 100);

        $response = $this->put(route('coupon-codes.update', $couponCode), [
            'code' => $code,
            'amount' => $amount,
        ]);

        $couponCode->refresh();

        $response->assertNoContent();

        $this->assertEquals($code, $couponCode->code);
        $this->assertEquals($amount, $couponCode->amount);
    }

    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $couponCode = CouponCode::factory()->create();

        $response = $this->delete(route('coupon-codes.destroy', $couponCode));

        $response->assertNoContent();

        $this->assertModelMissing($couponCode);
    }
}
