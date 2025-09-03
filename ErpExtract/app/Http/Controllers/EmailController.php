<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emails = Email::all();
        return view('content.config.emails.index', compact('emails'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.config.emails.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:emails,email',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Email::create($request->only(['email', 'name']));

        return redirect()->route('emails.index')->with('success', 'Email creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Email $email)
    {
        return view('content.config.emails.show', compact('email'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Email $email)
    {
        return view('content.config.emails.edit', compact('email'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Email $email)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:emails,email,' . $email->id,
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $email->update($request->only(['email', 'name']));

        return redirect()->route('emails.index')->with('success', 'Email actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Email $email)
    {
        $email->delete();

        return redirect()->route('emails.index')->with('success', 'Email eliminado exitosamente.');
    }
}
