<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;
        $productName = $request->product_name;
        $productPrice = $request->product_price;

        if (!isset($cart[$productId])) {
            $cart[$productId] = [
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => 1
            ];
        } else {
            $cart[$productId]['quantity']++;
        }

        session()->put('cart', $cart);

        return response()->json($this->getCartTotals($cart));
    }

    public function removeFromCart(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        return response()->json($this->getCartTotals($cart));
    }

    public function showCart()
    {
        $cart = session()->get('cart', []);
        return response()->json($this->getCartTotals($cart));
    }

    private function getCartTotals($cart)
    {
        $subTotal = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $discount = (count($cart) >= 3) ? 0.1 * $subTotal : 0;
        $total = $subTotal - $discount;

        return [
            'cart' => $cart,
            'subTotal' => round($subTotal, 2),
            'discount' => round($discount, 2),
            'total' => round($total, 2)
        ];
    }
}
