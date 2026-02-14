<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function Profile () {
        $user = User::findOrFail(Auth::id());
        // return         $user  ;
        // $countries = Country::all ();
        // return     $user  ;
        return inertia('Profile/Profile', [
            'user' => $user,
         ]);
    }


    public function ProfileUpdate(Request $request)
{
                  
        $user = User::findOrFail(Auth::id());
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|min:1|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
    
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        // Update profile photo if provided
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }
    
        $user->save();
    
        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
}


public function Index(Request $request)
{
    $user = Auth::user();
    $filters = $request->only(['search',   'role']);
    $per_page = $request->input('per_page', 5);

    $query = User::filter($filters); 

    $users = $query->orderByDesc('id')->paginate($per_page)->appends($request->all()); 
    return inertia('Users/Index', [
        'users' => $users,
        'filters' => $filters, 
    ]);
}


    public function Create()
    {
  

        return inertia('Users/Create', [ 
        ]);
    }

    public function Store(Request $req)
{
    $auth_user =   Auth::user() ;  
    $validated = $req->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'confirmed'],
        'phone' => ['required', 'string', 'max:20'],
        'location' => ['nullable', 'string', 'max:500'],
        'role' => ['required', Rule::in([1, 2, 3, 4])],
 
        'added_by' => ['nullable', 'exists:users,id'],
    ]);

     $user = new User();
    $user->name = $req['name'];
    $user->email = $req['email'];
    $user->password = Hash::make($req['password']);
    $user->phone = $req['phone'];
    $user->location = $req['location'] ?? null;
    $user->role = $req['role'];

    $user->added_by =   Auth::user()->id ;
    $user->save();

    return  redirect()->back();
}

public function Edit(Request $request)
{
    $user = User::findOrFail($request->id);

    return inertia('Users/Edit', [
        'user_data' => $user,
    ]);
}

public function Update(Request $request)
{
    $auth_user =   Auth::user() ;  
    $user = User::findOrFail($request->id);

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'password' => ['nullable', 'string',  'confirmed'],
        'phone' => ['required', 'string', 'max:20'],
        'location' => ['nullable', 'string', 'max:500'],
        'role' => ['required', Rule::in([1, 2, 3, 4])],

    ]);

    $user->name = $validated['name'];
    $user->email = $validated['email'];
    $user->phone = $validated['phone'];
    $user->location = $validated['location'] ?? null;
    $user->role = $validated['role'];

    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    $user->save();

    return  redirect()->back();
}



public function Delete (  $id )
{

   $user =  User::find($id)->delete();
    if (   $user ) 
        return redirect() -> back() ;
    else 
    return redirect() -> back()->withErrors([]) ;  
 
}



}
