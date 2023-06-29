<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::when($request->has('search'), function($q)use($request){
            $q->where('name', 'like', '%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%');
        })->latest()->paginate(10);
        return ContactResource::collection($contacts);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:contacts,name',
            'email' => 'required|email|min:3|unique:contacts,email',
            'phone' => 'required|numeric',
        ]);

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
            
        return response()->json(['success' => 'Contact added successfully'], 201);
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json($contact, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|min:3|unique:contacts,name,'.$id,
            'email' => 'required|email|min:3|unique:contacts,email,'.$id,
            'phone' => 'required|numeric',
        ]);

        $contact = Contact::findOrFail($id);

        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
            
        return response()->json(['success' => 'Contact updated successfully'], 201);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(['success' => 'Contact deleted sucessfullfy'], 200);
    }
}
