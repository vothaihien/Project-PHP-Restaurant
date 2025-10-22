@extends('layouts.app')

@section('extra-css')
    <style>
        .StripeElement {
            width: 100%;
            padding: 10px;
            background-color: white;
            height: 40px;
            padding: 10px 12px;
            border-radius: 4px;
            border: 1px solid transparent;
            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        #card-errors {
            color: #fa755a;
            text-align: left;
            font-size: 13px;
            line-height: 17px;
            margin-top: 12px;
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Order Summary & Payment</h4>
                    </div>

                    <div class="card-body">
                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h5>Order Details</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td class="text-end">${{ number_format($order->billing_subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Fee:</td>
                                        <td class="text-end">${{ number_format($order->billing_delivery, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax:</td>
                                        <td class="text-end">${{ number_format($order->billing_tax, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Driver Tip:</td>
                                        <td class="text-end">${{ number_format($order->driver_tip, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <th class="text-end">${{ number_format($order->billing_total, 2) }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <form id="payment-form" class="needs-validation" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label for="card-element" class="form-label">Credit or debit card</label>
                                <div id="card-element"></div>
                                <div id="card-errors" role="alert"></div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" id="submit-button">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"
                                    id="spinner"></span>
                                <span id="button-text">Pay ${{ number_format($order->billing_total, 2) }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            const stripe = Stripe('{{ config('
                    services.stripe.key ') }}');
            const elements = stripe.elements();

            const style = {
                base: {
                    color: '#32325d',
                    fontFamily: 'Nunito, "Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    lineHeight: '24px',
                    padding: '10px 14px',
                    '::placeholder': {
                        color: '#aab7c4'
                    },
                    iconColor: '#666ee8'
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Thêm CSS tùy chỉnh cho container
            const cardElementContainer = document.getElementById('card-element');
            cardElementContainer.style.padding = '10px';
            cardElementContainer.style.border = '1px solid #ced4da';
            cardElementContainer.style.borderRadius = '4px';
            cardElementContainer.style.backgroundColor = '#fff';

            const card = elements.create('card', {
                style: style,
                hidePostalCode: true
            });
            card.mount('#card-element');

            // Handle form submission
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('button-text');

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                setLoading(true);

                try {
                    const result = await stripe.createPaymentMethod({
                        type: 'card',
                        card: card,
                        billing_details: {
                            email: '{{ auth()->user()->email }}'
                        }
                    });

                    if (result.error) {
                        handleError(result.error);
                    } else {
                        const response = await fetch('/payment/create-intent/{{ $order->id }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.error) {
                            handleError({ message: data.error });
                        } else {
                            const confirm = await stripe.confirmCardPayment(data.clientSecret, {
                                payment_method: result.paymentMethod.id
                            });

                            if (confirm.error) {
                                handleError(confirm.error);
                                window.location.href = '/checkout/failed';
                            } else {
                                window.location.href = '/checkout/success/' + {{ $order->id }};
                            }
                        }
                    }
                } catch (error) {
                    handleError({ message: 'An unexpected error occurred.' });
                    console.error('Payment error:', error);
                }

                setLoading(false);
            });

            function handleError(error) {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                errorElement.style.display = 'block';
            }

            function setLoading(isLoading) {
                submitButton.disabled = isLoading;
                spinner.classList.toggle('d-none', !isLoading);
                buttonText.classList.toggle('d-none', isLoading);
            }

            // Handle real-time validation errors
            card.addEventListener('change', function (event) {
                const errorElement = document.getElementById('card-errors');
                if (event.error) {
                    errorElement.textContent = event.error.message;
                    errorElement.style.display = 'block';
                } else {
                    errorElement.textContent = '';
                    errorElement.style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection