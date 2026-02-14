<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "جاري العمل"  ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           return inertia("Clients/Create")  ;  
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $req)
{
    // 1) Validation
    $data = $req->validate([
        'client_type'    => ['required', 'integer', 'in:1,2'],
        'client_subtype' => ['required', 'integer'],
        'entity_name'    => ['required', 'string', 'max:255'],
        'client_name'    => ['required', 'string', 'max:255'],
        'phone'          => ['required', 'string', 'max:50'],
        'note'           => ['nullable', 'string'],
    ]);

    // 2) Enforce subtype rules
    if ((int)$data['client_type'] === 1) {
        // منشأة صحية => (1 مستشفى) أو (2 مصحة)
        if (!in_array((int)$data['client_subtype'], [1, 2], true)) {
            return back()->withErrors([
                'client_subtype' => 'النوع الفرعي غير متوافق مع (منشأة صحية).',
            ])->withInput();
        }
    }

    if ((int)$data['client_type'] === 2) {
        // شركة => (3 جهة عامة) أو (4 جهة خاصة)
        if (!in_array((int)$data['client_subtype'], [3, 4], true)) {
            return back()->withErrors([
                'client_subtype' => 'النوع الفرعي غير متوافق مع (شركة).',
            ])->withInput();
        }

        // حسب شرطك: الجهة العامة/الخاصة لا يوجد زر حفظ أصلاً
        // إذن نمنع الحفظ من السيرفر كذلك
        return back()->withErrors([
            'client_type' => 'لا يمكن حفظ عميل من نوع (جهة عامة/جهة خاصة). المتاح فقط تنزيل النموذج.',
        ])->withInput();
    }

    // 3) Save (health facility only)
    \App\Models\Client::create([
        'client_type'    => $data['client_type'],
        'client_subtype' => $data['client_subtype'],
        'entity_name'    => $data['entity_name'],
        'client_name'    => $data['client_name'],
        'phone'          => $data['phone'],
        'note'           => $data['note'] ?? null,
    ]);

    return redirect()->back();
}

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        //
    }
}
