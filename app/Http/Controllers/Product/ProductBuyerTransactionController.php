<?php

namespace App\Http\Controllers\Product;

use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Product $product
     * @param User $buyer
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules =[
            'quantity' => ['required','integer','min:1']
        ];
        $this->validate($request,$rules);

        if($buyer->id == $product->seller_id){
            return $this->errorResponse('El comprador y el vendedor no pueden ser el mismo usuario',409);
        }
        if(!$buyer->esVerificado()){
            return $this->errorResponse('El comprador debe ser un usuario verificado',409);
        }
        if($product->seller->esVerificado()){
            return $this->errorResponse('El vendedor debe ser un usuario verificado',409);
        }

        if(!$product->estaDisponible()){
            return $this->errorResponse('El producto para la transaccion no esta disponible',409);
        }
        if($product->quantity < $request->quantity){
            return $this->errorResponse('El producto no tiene la cantidad disponible para la transaccion',409);
        }

        return DB::transaction(function() use($request,$product,$buyer){
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction,201);
        });
    }
}
