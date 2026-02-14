<?php

namespace App\Http\Controllers;

use App\Models\SuppliesCard;
use Illuminate\Http\Request;

class SuppliesCardController extends Controller
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
        return inertia ("SuppliesCards/Create") ; 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            $data = $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:supplies_cards,name'],
            ]);

            SuppliesCard::create([
                'name' => $data['name'],
            ]);

            return redirect()->back();
        }

    /**
     * Display the specified resource.
     */
    public function show(SuppliesCard $suppliesCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuppliesCard $suppliesCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuppliesCard $suppliesCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuppliesCard $suppliesCard)
    {
        //
    }
}
