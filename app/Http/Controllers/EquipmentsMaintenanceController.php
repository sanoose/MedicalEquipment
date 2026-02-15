<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\EquipmentsCard;
use App\Models\EquipmentsMaintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentsMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $req)
{
    $filters  = $req->only(['search', 'maintenance_status']);
    $per_page = $req->input('per_page', 10);

    $query = \App\Models\EquipmentsMaintenance::with(['client', 'equipment'])
        ->filter($filters);

    $maintenances = $query
        ->orderByDesc('id')
        ->paginate($per_page)
        ->appends($req->all());

    return inertia('EquipmentsMaintenances/Index', [
        'maintenances' => $maintenances,
        'filters'      => $filters,
    ]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $equipments_cards = EquipmentsCard::all() ; 
        $clients = Client::where("client_type" , 1 )->get()  ; 
        return inertia("EquipmentsMaintenances/Create" , ["clients" => $clients , "equipments_cards" => $equipments_cards  ] ) ;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $data = $req->validate([
            'client_id'      => ['required', 'integer', 'exists:clients,id'],
            'equipment_id'   => ['required', 'integer', 'exists:equipments_cards,id'],
            'description'    => ['required', 'string', 'max:255'],
            'manufacturer'   => ['required', 'string', 'max:255'],
            'serial_number'  => ['required', 'string', 'max:255'],
            'note'           => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {

            // ابحث في مخزون معدات العميل
            $stored = \App\Models\ClientsEquipments::where('client_id', $data['client_id'])
                ->where('equipment_id', $data['equipment_id'])
                ->where('serial_number', $data['serial_number'])->lockForUpdate()
                ->first();

            if ($stored) {
                // موجود: استخدم البيانات المخزنة
                $payload = [
                    'client_id'           => $stored->client_id,
                    'equipment_id'        => $stored->equipment_id,
                    'description'         => $stored->description,
                    'manufacturer'        => $stored->manufacturer,
                    'serial_number'       => $stored->serial_number,
                    'note'                => $stored->note,
                    'maintenance_status'  => 1,
                ];
            } else {
                // غير موجود: خزّنه أولاً في clients_equipments
                $stored = \App\Models\ClientsEquipments::create([
                    'client_id'     => $data['client_id'],
                    'equipment_id'  => $data['equipment_id'],
                    'description'   => $data['description'],
                    'manufacturer'  => $data['manufacturer'],
                    'serial_number' => $data['serial_number'],
                    'note'          => $data['note'] ?? null,
                ]);

                // ثم احفظ طلب الصيانة ببيانات request
                $payload = [
                    'client_id'           => $data['client_id'],
                    'equipment_id'        => $data['equipment_id'],
                    'description'         => $data['description'],
                    'manufacturer'        => $data['manufacturer'],
                    'serial_number'       => $data['serial_number'],
                    'note'                => $data['note'] ?? null,
                    'maintenance_status'  => 1,
                ];
            }

            \App\Models\EquipmentsMaintenance::create($payload);
        }) ;

        return redirect()->back();
    }
    /**
     * Display the specified resource.
     */
    public function show(EquipmentsMaintenance $equipmentsMaintenance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentsMaintenance $equipmentsMaintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentsMaintenance $equipmentsMaintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentsMaintenance $equipmentsMaintenance)
    {
        //
    }

    public function ChangeStatus(Request $request)
{
    $data = $request->validate([
        'id'                 => ['required', 'integer', 'exists:equipments_maintenances,id'],
        'maintenance_status' => ['required', 'integer', 'in:1,2,3'],
    ]);

    \App\Models\EquipmentsMaintenance::where('id', $data['id'])
        ->update(['maintenance_status' => $data['maintenance_status']]);

    return redirect()->back();
}
}
