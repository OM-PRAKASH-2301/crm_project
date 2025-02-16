@extends('layouts.app')

@section('title', 'Customer Relationship Management')

@section('content')
    <h2 class="crm-heading">Customer Relationship Management</h2>

    <div class="toggle-buttons">
        <button class="btn btn-primary" id="addContactBtn">Add New Contact</button>
        <button class="btn btn-secondary" id="showContactBtn" style="display: none;">Show Contacts</button>
    </div>

    <div id="contentBox" class="card p-4 mb-4">
        @include('contacts.list')  {{-- Contact List --}}
        @include('contacts.form')  {{-- Contact Form --}}
    </div>
@endsection
