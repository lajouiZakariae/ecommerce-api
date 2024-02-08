<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PaymentMethodResource;
use App\Models\PaymentMethod;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\ApiResource;

/**
 * @group Payment Methods
 */
#[ApiResource('payment-methods')]
class PaymentMethodController extends Controller
{
    /**
     * Display a listing of payment methods.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        $paymentMethods = PaymentMethod::query();

        $paymentMethods = request()->input("sortBy") === "oldest"
            ? $paymentMethods->oldest()
            : $paymentMethods->latest();

        return response()->make(PaymentMethodResource::collection($paymentMethods->get()));
    }

    /**
     * Display the specified payment method.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $paymentMethod): Response
    {
        return response()->make(new PaymentMethodResource($paymentMethod));
    }

    /**
     * Store a newly created payment method in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(): Response
    {
        $data = request()->validate([
            'name' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string', 'min:1', 'max:500'],
        ]);

        $paymentMethod = PaymentMethod::create($data);

        return response()->make('', Response::HTTP_CREATED);
    }

    /**
     * Update the specified payment method in storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethod $paymentMethod): Response
    {
        $data = request()->validate([
            'name' => ['string', 'min:1', 'max:255'],
            'description' => ['string', 'min:1', 'max:500']
        ]);

        $paymentMethod->update($data);

        return response()->noContent();
    }

    /**
     * Remove the specified payment method from storage.
     *
     * @param  \App\Models\PaymentMethod  $paymentMethod
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod): Response
    {
        $paymentMethod->delete();

        return response()->noContent();
    }
}
