<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ShippingRule;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function getAddressData()
    {
        $addresses = UserAddress::where('user_id', Auth::user()->id)->get();
        return response()->json(['addresses' => $addresses]);
    }

    public function getShippingMethods()
    {
        $shippingMethods = ShippingRule::where('status', 1)->get();
        return response()->json(['shippingMethods' => $shippingMethods]);
    }

    public function createAddress(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:200'],
            'phone' => ['required', 'max:200'],
            'email' => ['required', 'email'],
            'country' => ['required', 'max: 200'],
            'state' => ['required', 'max: 200'],
            'city' => ['required', 'max: 200'],
            'zip' => ['required', 'max: 200'],
            'address' => ['required', 'max: 200']
        ]);

        $address = new UserAddress();
        $address->user_id = Auth::user()->id;
        $address->name = $request->name;
        $address->phone = $request->phone;
        $address->email = $request->email;
        $address->country = $request->country;
        $address->state = $request->state;
        $address->city = $request->city;
        $address->zip = $request->zip;
        $address->address = $request->address;
        $address->save();

        return response()->json(['message' => 'Address created successfully']);
    }

    public function checkoutFormSubmit(Request $request)
    {
        $request->validate([
            'shipping_method_id' => ['required', 'integer'],
            'shipping_address_id' => ['required', 'integer'],
        ]);

        $shippingMethod = ShippingRule::findOrFail($request->shipping_method_id);
        if ($shippingMethod) {
            Session::put('shipping_method', [
                'id' => $shippingMethod->id,
                'name' => $shippingMethod->name,
                'type' => $shippingMethod->type,
                'cost' => $shippingMethod->cost
            ]);
        }
        $address = UserAddress::findOrFail($request->shipping_address_id)->toArray();
        if ($address) {
            Session::put('address', $address);
        }

        return response()->json(['status' => 'success', 'redirect_url' => route('user.payment')]);
    }
}
