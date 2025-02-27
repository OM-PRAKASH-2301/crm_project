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
    
        return response()->json(['message' => 'Contact saved successfully!', 'contact' => $contact, 'status' => 'Success']);
    }
    

    public function list() {
        $contacts = Contact::where('deleted_at', 'N')->get();
        $html = '';
        if(!empty($contacts)){
            foreach ($contacts as $contact) {
                // Convert stored path to a full URL
                $imagePath = !empty($contact->profile_image) 
                    ? asset("storage/{$contact->profile_image}") // Generate correct URL
                    : "No Image";
        
                $html .= "<tr>
                            <td>{$contact->name}</td>
                            <td>{$contact->email}</td>
                            <td>{$contact->phone}</td>
                            <td>{$contact->gender}</td>
                            <td>" . 
                                (!empty($contact->profile_image) 
                                    ? "<img src='{$imagePath}' alt='Profile Image' width='100'>" 
                                    : "No Image") . 
                            "</td>
                            <td>
                                <button class='btn btn-success btn-sm editContact' data-id='{$contact->id}'>Edit</button> 
                                <button class='btn btn-danger btn-sm deleteContact' data-id='{$contact->id}'>Delete</button>
                            </td>
                        </tr>";
            }
        } else {
            $html .= "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
        }
        return response($html);
    }
    
    

    public function search(Request $request) {
        $query = Contact::query(); 

        if ($request->filled('name')) {
            $query->where('name', 'like', "%{$request->input('name')}%");
        }
    
        if ($request->filled('email')) {
            $query->orWhere('email', 'like', "%{$request->input('email')}%");
        }
    
        if ($request->filled('phone')) {
            $query->orWhere('phone', 'like', "%{$request->input('phone')}%");
        }
    
        $query->where('deleted_at', 'N'); 
    
        $contacts = $query->get();
        $html = '';
    
        if ($contacts->isNotEmpty()) {
            foreach ($contacts as $contact) {
                // Convert stored path to a full URL
                $imagePath = !empty($contact->profile_image) 
                    ? asset("storage/{$contact->profile_image}") // Generate correct URL
                    : null;
    
                $html .= "<tr>
                            <td>{$contact->name}</td>
                            <td>{$contact->email}</td>
                            <td>{$contact->phone}</td>
                            <td>{$contact->gender}</td>
                            <td>" . 
                                (!empty($imagePath) 
                                    ? "<img src='{$imagePath}' alt='Profile Image' width='100'>" 
                                    : "No Image") . 
                            "</td>
                            <td>
                                <button class='btn btn-success btn-sm editContact' data-id='{$contact->id}'>Edit</button> 
                                <button class='btn btn-danger btn-sm deleteContact' data-id='{$contact->id}'>Delete</button>
                            </td>
                          </tr>";
            }
        } else {
            $html .= "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
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

    public function edit($id) {
        $contact = Contact::find($id); // No need for first()
    
        if (!$contact) {
            return response()->json(['status' => 'error', 'message' => 'Contact not found!']);
        }
        $contact->profile_image = $contact->profile_image 
        ? asset("storage/{$contact->profile_image}") 
        : asset('default_image.jpg'); 
        return response()->json([
            'status' => 'success',
            'contact' => $contact
        ]);
    }
    
    
    public function update(Request $request) {
        // Validate fields with custom messages
        $request->validate([
            'id' => 'required|exists:contacts,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:contacts,email,' . $request->id,
            'phone' => 'required|string|min:10|max:10|unique:contacts,phone,' . $request->id, 
            'gender' => 'required|in:Male,Female,Other',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'Name is required!',
            'email.required' => 'Email is required!',
            'email.unique' => 'Email is already in use!',
            'phone.required' => 'Phone number is required!',
            'phone.unique' => 'Phone number is already in use!',
            'phone.min' => 'Phone number must be exactly 10 digits!',
            'phone.max' => 'Phone number must be exactly 10 digits!',
            'gender.required' => 'Please select gender!',
            'profile_image.image' => 'File must be an image!',
            'profile_image.mimes' => 'Allowed formats: jpg, jpeg, png!',
            'profile_image.max' => 'Max file size: 2MB!'
        ]);

        $contact = Contact::find($request->id);
    
        if (!$contact) {
            return response()->json(['status' => 'error', 'message' => 'Contact not found!']);
        }
    
        // Update contact details
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->gender = $request->gender;
    
        // Handle image upload
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $contact->profile_image = $imagePath;
        }
    
        $contact->save();
    
        return response()->json([
            'status' => 'success',
            'message' => 'Contact updated successfully!',
            'contact' => $contact
        ]);
    }
    
    
    
}

