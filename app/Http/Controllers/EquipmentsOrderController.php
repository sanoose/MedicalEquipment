<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EquipmentsCard;
use App\Models\EquipmentsOrder;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class EquipmentsOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
       public function index(Request $request)
    {
        $filters = $request->only(['search', 'equipment_order_status']);
        $per_page = $request->input('per_page', 10);

       $query = \App\Models\EquipmentsOrder::query()
        ->with(['client:id,entity_name,client_name,phone'])
        ->with(['details.equipment:id,name'])   // ✅ مهم للمودال
        ->withCount('details')
        ->withSum('details as total_amount', 'amount')
        ->filter($filters);

        $orders = $query->orderByDesc('id')
            ->paginate($per_page)
            ->appends($request->all());

        return inertia('EquipmentsOrders/Index', [
            'orders' => $orders,
            'filters' => $filters,
        ]);
    }

    public function ChangeStatus(Request $req)
    {
        $data = $req->validate([
            'id' => ['required', 'integer', 'exists:equipments_orders,id'],
            'equipment_order_status' => ['required', 'integer', 'in:1,2,3'],
        ]);

        \App\Models\EquipmentsOrder::where('id', $data['id'])
            ->update(['equipment_order_status' => (int)$data['equipment_order_status']]);

        return redirect()->back();
    }

    // للعرض داخل المودال (لو تحب تجلبه من نفس الصفحة بدون راوت إضافي: نحن أصلاً نحمل details داخل props عند فتح المودال)
    // لكن لو تريد راوت عرض منفصل:
    public function show($id)
    {
        $order = \App\Models\EquipmentsOrder::with([
            'client:id,entity_name,client_name,phone',
            'details.equipment:id,name',
        ])->findOrFail($id);

        return inertia('EquipmentsOrders/Show', ['order' => $order]);
    }
    /**
     * Show the form for creating a new resource.
     */
        public function create()
        {
            $equipments_cards = \App\Models\EquipmentsCard::orderBy('name')->get();

            // نحضر العملاء + معداتهم مسبقاً حتى نعرف هل لديه معدات أم لا بمجرد الاختيار
            $clients = \App\Models\Client::with([
                'clients_equipments'   ])->orderByDesc('id')->get();
    // return  $clients ; 
            return inertia('EquipmentsOrders/Create', [
                'clients' => $clients,
                'equipments_cards' => $equipments_cards,
            ]);
        }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $req)
{
    $data = $req->validate([
        'client_id' => ['required', 'integer', 'exists:clients,id'],
        'note'      => ['nullable', 'string'],

        // تفاصيل الطلب (معدات جديدة مطلوبة)
        'details'                 => ['required', 'array', 'min:1'],
        'details.*.equipment_id'  => ['required', 'integer', 'exists:equipments_cards,id'],
        'details.*.amount'        => ['required', 'numeric', 'min:1'],

        // معدات العميل الحالية (اختيارية: أول مرة أو لإضافة ناقص)
        'client_equipments'                      => ['nullable', 'array'],
        'client_equipments.*.equipment_id'       => ['required_with:client_equipments', 'integer', 'exists:equipments_cards,id'],
        'client_equipments.*.description'        => ['required_with:client_equipments', 'string', 'max:255'],
        'client_equipments.*.manufacturer'       => ['required_with:client_equipments', 'string', 'max:255'],
        'client_equipments.*.serial_number'      => ['required_with:client_equipments', 'string', 'max:255'],
        'client_equipments.*.note'               => ['nullable', 'string'],
    ]);

    DB::transaction(function () use ($data) {

        $client_id = (int) $data['client_id'];

        // 1) حفظ معدات العميل الحالية إن وُجدت في الطلب
        if (!empty($data['client_equipments']) && is_array($data['client_equipments'])) {

            foreach ($data['client_equipments'] as $ce) {
                // منع التكرار: نفس العميل + نفس الجهاز + نفس السيريال
                \App\Models\ClientsEquipments::firstOrCreate([
                    'client_id'     => $client_id,
                    'equipment_id'  => (int) $ce['equipment_id'],
                    'serial_number' => $ce['serial_number'],
                ], [
                    'description'   => $ce['description'],
                    'manufacturer'  => $ce['manufacturer'],
                    'note'          => $ce['note'] ?? null,
                ]);
            }
        }

        // 2) حفظ الطلب الأساسي
        $order = \App\Models\EquipmentsOrder::create([
            'client_id'              => $client_id,
            'note'                   => $data['note'] ?? null,
            'equipment_order_status' => 1, // onhold
            // order_date useCurrent في DB
        ]);

        // 3) حفظ تفاصيل الطلب
        foreach ($data['details'] as $d) {
            \App\Models\EquipmentsOrdersDetail::create([
                'equipment_order_id' => $order->id,
                'equipment_id'    => (int) $d['equipment_id'],
                'amount'          => (double) $d['amount'],
            ]);
        }
    });

    return redirect()->back()->with('success', 'تم حفظ طلب المعدات بنجاح.');
}
  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentsOrder $equipmentsOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentsOrder $equipmentsOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentsOrder $equipmentsOrder)
    {
        //
    }
}
