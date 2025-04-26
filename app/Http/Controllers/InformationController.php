<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationController extends Controller
{
    public function create()
    {
        $data = Information::where('user_id', Auth::id())->first();
        return view('admin.information.create', compact('data'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'venue_name' => 'required',
            'website' => 'nullable|url|max:100',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'additional_information' => 'required',
        ]);

        Information::updateOrCreate(
            ['id' => $request->id],
            array_merge($validatedData, ['user_id' => Auth::id()])
        );

        return back()->with('success', 'Information updated successfully');
    }
}
