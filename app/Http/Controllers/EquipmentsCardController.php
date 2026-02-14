<?php

namespace App\Http\Controllers;

use App\Models\EquipmentsCard;
use Illuminate\Http\Request;

class EquipmentsCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return "سيتم الاضافة لاحقا " ; 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return inertia("EquipmentsCards/Create") ; 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
           $data = $request->validate([
        'name' => ['required', 'string', 'max:255', 'unique:equipments_cards,name'],
    ]);

        EquipmentsCard::create([
            'name' => $data['name'],
        ]);

        return redirect()->back();
     }

    /**
     * Display the specified resource.
     */
    public function show(EquipmentsCard $equipmentsCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentsCard $equipmentsCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentsCard $equipmentsCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentsCard $equipmentsCard)
    {
        //
    }
}
