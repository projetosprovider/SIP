<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock\Product;
use App\Models\Stock\Stock;
use DateTime;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!$request->has('product_id')) {
          notify()->flash('Erro', 'error', [
            'text' => 'Produto não informado.'
          ]);

          return back();
        }

        $product = Product::uuid($request->get('product_id'));

        return view('stock.stock.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->request->all();

        if(!$request->has('product_id')) {
          notify()->flash('Erro', 'error', [
            'text' => 'Produto não informado.'
          ]);

          return back();
        }

        $product = Product::uuid($request->get('product_id'));

        $data['product_id'] = $product->id;

        if($request->filled('buyed_at')) {
            $data['buyed_at'] = DateTime::createFromFormat('d/m/Y', $data['buyed_at']);
        }

        Stock::create($data);

        notify()->flash('Sucesso', 'success', [
          'text' => 'Novo Item adicionado ao Produto ' . $product->name
        ]);

        return redirect()->route('products.show', $product->uuid);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock::uuid($id);
        return view('stock.stock.edit', compact('stock'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->request->all();

        if($request->filled('buyed_at')) {
            $data['buyed_at'] = DateTime::createFromFormat('d/m/Y', $data['buyed_at']);
        }

        $stock = Stock::uuid($id);

        $stock->update($data);

        notify()->flash('Sucesso', 'success', [
          'text' => 'Item atualizado com sucesso'
        ]);

        return redirect()->route('products.show', $stock->product->uuid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
