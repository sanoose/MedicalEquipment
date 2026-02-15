<?php

namespace App\Http\Controllers;

use App\Models\SuppliesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SuppliesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
      public function index(Request $request)
    {
        $filters = $request->only(['search', 'supply_order_status']);
        $per_page = $request->input('per_page', 10);

        $query = \App\Models\SuppliesOrder::query()
            ->with(['client:id,entity_name,client_name,phone'])
            ->with(['details.supply:id,name'])              // ✅ للمودال
            ->withCount('details')
            ->withSum('details as total_amount', 'amount')
            ->filter($filters);

        $orders = $query->orderByDesc('id')
            ->paginate($per_page)
            ->appends($request->all());
    // return  $orders ; 
        return inertia('SuppliesOrders/Index', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    public function ChangeStatus(Request $req)
    {
        $data = $req->validate([
            'id' => ['required', 'integer', 'exists:supplies_orders,id'],
            'supply_order_status' => ['required', 'integer', 'in:1,2,3'],
        ]);

        \App\Models\SuppliesOrder::where('id', $data['id'])
            ->update(['supply_order_status' => (int)$data['supply_order_status']]);

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = \App\Models\Client::all();
        $supplies_cards = \App\Models\SuppliesCard::all();

        return inertia('SuppliesOrders/Create', [
            'clients' => $clients,
            'supplies_cards' => $supplies_cards,
        ]);
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'note'      => ['nullable', 'string'],

            'details'                => ['required', 'array', 'min:1'],
            'details.*.supply_id'    => ['required', 'integer', 'exists:supplies_cards,id'],
            'details.*.amount'       => ['required', 'numeric', 'min:1'],
        ]);

        DB::transaction(function () use ($data) {

            $order = \App\Models\SuppliesOrder::create([
                'client_id'          => (int) $data['client_id'],
                'note'               => $data['note'] ?? null,
                'supply_order_status'=> 1, // onhold
                // order_date: default(now()) في الجدول
            ]);

            foreach ($data['details'] as $d) {
                \App\Models\SuppliesOrdersDetail::create([
                    'supply_order_id' => $order->id,
                    'supply_id'       => (int) $d['supply_id'],
                    'amount'          => (double) $d['amount'],
                ]);
            }
        });

        return redirect()->back()->with('success', 'تم حفظ طلب المستلزمات بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuppliesOrder $suppliesOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuppliesOrder $suppliesOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuppliesOrder $suppliesOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuppliesOrder $suppliesOrder)
    {
        //
    }
}
