<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Omnipay\Omnipay;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaypalController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); // Chế độ Sandbox
    }

   public function pay(Request $request)
{
    try {
        $response = $this->gateway->purchase([
            'amount' => $request->total,
            'currency' => env('PAYPAL_CURRENCY', 'USD'),
            'returnUrl' => url('/success') . '?session_id=' . session()->getId(),
            'cancelUrl' => url('/error'),
        ])->send();

        if ($response->isRedirect()) {
            return $response->redirect();
        } else {
            return response()->json(['error' => $response->getMessage()], 500);
        }
    } catch (\Throwable $th) {
        return response()->json(['error' => $th->getMessage()], 500);
    }
}

    public function success(Request $request)
{
    if ($request->has('session_id')) {
        session()->setId($request->get('session_id'));
        session()->start();
    }

    if ($request->filled('paymentId') && $request->filled('PayerID')) {
        $transaction = $this->gateway->completePurchase([
            'payer_id' => $request->input('PayerID'),
            'transactionReference' => $request->input('paymentId'),
        ]);

        $response = $transaction->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();

            $payment = new Payment();
            $payment->user_id = Auth::check() ? Auth::id() : null;
            $payment->payment_id = $data['id'];
            $payment->payer_id = $data['payer']['payer_info']['payer_id'];
            $payment->payment_email = $data['payer']['payer_info']['email'];
            $payment->amount = $data['transactions'][0]['amount']['total'];
            $payment->currency = env('PAYPAL_CURRENCY', 'USD');
            $payment->payment_status = $data['state'];
            $payment->save();

            session()->forget('cart');

            $token = Auth::check()
                ? Auth::user()->createToken('after-paypal')->plainTextToken
                : null;

            return redirect("http://localhost:5173/payment-success?token={$token}&payment_id={$payment->payment_id}");
        }

        return response()->json(['error' => $response->getMessage()], 500);
    }

    return response('Payment declined or missing required parameters', 400);
}



    public function error()
    {
        return 'User declined the payment!';
    }


}
