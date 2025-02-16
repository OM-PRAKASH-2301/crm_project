<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller {
    
    public function index() {
        return view('contacts.index');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email',
            'phone' => 'required|string|max:15|unique:contacts,phone',
            'gender' => 'required|in:Male,Female,Other',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'additional_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
        ]);
    
        // Handle file uploads
        $profileImagePath = $request->file('profile_image') ? $request->file('profile_image')->store('profiles', 'public') : null;
        $additionalFilePath = $request->file('additional_file') ? $request->file('additional_file')->store('files', 'public') : null;
    
        // Handle custom fields safely
        $customFields = $request->has('custom_fields') ? json_encode($request->input('custom_fields')) : json_encode([]);
    
        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'profile_image' => $profileImagePath,
            'additional_file' => $additionalFilePath,
            'custom_fields' => $customFields,
        ]);
    
        return response()->json(['message' => 'Contact saved successfully!', 'contact' => $contact]);
    }
    

    public function list() {
        $contacts = Contact::where('deleted_at', 'N')->get();
        $html = '';

        foreach ($contacts as $contact) {
            $html .= "<tr>
                        <td>{$contact->name}</td>
                        <td>{$contact->email}</td>
                        <td>{$contact->phone}</td>
                        <td>{$contact->gender}</td>
                        <td><button class='btn btn-danger btn-sm deleteContact' data-id='{$contact->id}'>Delete</button></td>
                      </tr>";
        }
        
        return response($html);
    }

    public function search(Request $request) {
        $query = $request->input('search');
        $contacts = Contact::where('name', 'like', "%$query%")
                            ->orWhere('email', 'like', "%$query%")
                            ->orWhere('phone', 'like', "%$query%")
                            ->get();
        $html = '';

        foreach ($contacts as $contact) {
            $html .= "<tr>
                        <td>{$contact->name}</td>
                        <td>{$contact->email}</td>
                        <td>{$contact->phone}</td>
                        <td>{$contact->gender}</td>
                        <td><button class='btn btn-danger btn-sm deleteContact' data-id='{$contact->id}'>Delete</button></td>
                      </tr>";
        }

        return response($html);
    }

    public function delete(Request $request) {
        $contact = Contact::find($request->id);

        if ($contact) {
            $contact->update(['deleted_at' => 'Y']); // Soft delete by updating deleted_at to 'Y'
            return response()->json(['message' => 'Contact deleted successfully!', 'status' => 'Success']);
        } else {
            return response()->json(['message' => 'Contact not found!', 'status' => 'Error'], 404);
        }
    }
}

