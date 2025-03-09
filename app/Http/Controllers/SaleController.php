<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->latest()->get();
        return response()->json($sales);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,card,transfer'
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);
            
            $sale = Sale::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'unit_price' => $product->price,
                'total_amount' => $product->price * $request->quantity,
                'payment_method' => $request->payment_method,
                'status' => 'completed'
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Venta realizada con éxito',
                'sale' => $sale->load('product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al procesar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Sale $sale)
    {
        return response()->json($sale->load('product'));
    }

    public function getSalesByDate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        $sales = Sale::with('product')
            ->whereBetween('created_at', [$request->start_date, $request->end_date])
            ->get();

        $total = $sales->sum('total_amount');

        return response()->json([
            'sales' => $sales,
            'total' => $total
        ]);
    }

    public function getTopProducts()
    {
        $topProducts = Product::orderBy('sales_count', 'desc')
            ->take(10)
            ->get(['id', 'name', 'price', 'sales_count']);

        return response()->json($topProducts);
    }

    public function destroy(Sale $sale)
    {
        try {
            DB::beginTransaction();
            $sale->delete();
            DB::commit();
            
            return response()->json([
                'message' => 'Venta eliminada con éxito'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al eliminar la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
