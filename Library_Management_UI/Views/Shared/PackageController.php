<?php

namespace App\Http\Controllers\Api\Package;

use Carbon\Carbon;
use App\Models\Package;
use App\Traits\StripeCard;
use App\Models\Transaction;
use App\Models\UserPackages;
use Illuminate\Http\Request;
use App\Traits\StripePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    use StripeCard, StripePayment;
    public function getSubscriptionPackages()
    {
        $packages = Package::latest('id')->get();
        return response()->json(compact('packages'));
    }

    public function getSubscriptionPackageDetails(Request $request)
    {
        $packages_details = Package::where('id',$request->id)->first();
        return response()->json($packages_details);
    }

    public function paySubscription(Request $request){

        $package = Package::where('id', request('subscription_id'))->first();
        $stripe_payment = $this->stripe(request('card_number'), request('expiry_month'), request('expiry_year'), request('cvv'), $package->package_amount, auth()->user()->email);
        if ($stripe_payment->original['status'] !== 'error') {

            DB::beginTransaction();
            $user_package = new UserPackages();
            $user_package->user_id = \auth()->id();
            $user_package->package_id = $package->id;
            $user_package->purchase_date = Carbon::now()->format('Y-m-d');
            $user_package->expire_date = Carbon::now()->addMonth(12)->format('Y-m-d');
            $user_package->save();

            $payment = new Transaction();
            $payment->description = $stripe_payment->original['data'];
            $payment->transactor = \auth()->id();
            $payment->transaction_id = $stripe_payment->original['customer'];
            $payment->status = 'Paid';
            $payment->amount = $package->package_amount;
            $payment->transitionable_id = $request->subscription_id;
            $payment->save();
            $user_package->transaction()->save($payment);

            DB::commit();

            return $this->sendResponse($package, __('Successfully Purchased Subscription'));

        } else {
            return response()->json('Error');
        }
    }

    public function getSubscriptionLogs(){
        $logs= UserPackages::with('packages')->where('user_id',auth()->user()->id)->get();
        return response()->json(compact('logs'));
    }

    public function sendResponse($result = 'null', $message)
    {
        $response = [
            'status' => 'Success',
            'message' => $message ?? null,
            'data' => $result,
        ];

        return response($response, 200);
    }

}
