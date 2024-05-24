<?php

namespace App\Livewire\Pos;

use App\Models\Customer;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Pos extends Component
{
    use WithPagination, WithoutUrlPagination;



    public function render()
    {
        $todayDate = Carbon::now();
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }
        return view('livewire.pos.pos', [
            'customers' => Customer::all()->sortBy('name'),
            'productItem' => Cart::content(),
            'products' => Product::where('expire_date', '>', $todayDate)->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query()),
        ]);
    }

    public function mount(){


    }

    public function addCart($productId,$productName , $productPrice, )
    {


        Cart::add([
            'id' => $productId,
            'name' => $productName,
            'qty' => 1,
            'price' => $productPrice,
            'options' => ['size' => 'large']
        ]);

        //return Redirect::back()->with('success', 'Product has been added!');
    }

        public function updateCart(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::update($rowId, $validatedData['qty']);

        return Redirect::back()->with('success', 'Cart has been updated!');
    }

    public function deleteCart(String $rowId)
    {
        Cart::remove($rowId);

        return Redirect::back()->with('success', 'Cart has been deleted!');
    }

    public function createInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required'
        ];

        $validatedData = $request->validate($rules);
        $customer = Customer::where('id', $validatedData['customer_id'])->first();
        $content = Cart::content();

        return view('pos.create-invoice', [
            'customer' => $customer,
            'content' => $content
        ]);
    }

    public function printInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required'
        ];

        $validatedData = $request->validate($rules);
        $customer = Customer::where('id', $validatedData['customer_id'])->first();
        $content = Cart::content();

        return view('pos.print-invoice', [
            'customer' => $customer,
            'content' => $content
        ]);
    }
}
