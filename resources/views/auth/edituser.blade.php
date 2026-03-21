@extends('layouts.admin')

@section('title', 'Ubah Data User')

@section('content')

<!-- Page Heading -->
    <p class="mb-4">Halaman ini menampilkan daftar seluruh pengguna yang terdaftar dalam sistem.</p>


    
            <div class="card mb-4 shadow">
                <div class="card-header font-weight-bold text-primary">Account Details</div>
                <div class="card-body">
                    <form action="{{ route('users.update') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="form-group">
                            <label class="small mb-1" for="inputUsername">Username (how your name will appear to other users on the site)</label>
                            <input class="form-control" id="inputUsername" name="username" type="text" placeholder="Enter your username" value="{{ $user->username }}">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputFirstName">First name</label>
                                <input class="form-control" id="inputFirstName" name="first_name" type="text" placeholder="Enter your first name" value="{{ $user->first_name }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputLastName">Last name</label>
                                <input class="form-control" id="inputLastName" name="last_name" type="text" placeholder="Enter your last name" value="{{ $user->last_name }}">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputOrgName">Organization name</label>
                                <input class="form-control" id="inputOrgName" name="organization" type="text" placeholder="Enter your organization name" value="{{ $user->organization }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputLocation">Location</label>
                                <input class="form-control" id="inputLocation" name="location" type="text" placeholder="Enter your location" value="{{ $user->location }}">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control" id="inputEmailAddress" name="email" type="email" placeholder="Enter your email address" value="{{ $user->email }}">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputPhone">Phone number</label>
                                <input class="form-control" id="inputPhone" name="phone" type="tel" placeholder="Enter your phone number" value="{{ $user->phone }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small mb-1" for="inputBirthday">Birthday</label>
                                <input class="form-control" id="inputBirthday" name="birthday" type="text" name="birthday" placeholder="Enter your birthday" value="{{ $user->birthday }}">
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </form>
                </div>
            </div>

<style>
/* Tambahkan ini untuk gaya navigasi garis bawah (underline) */
.nav-borders .nav-link.active {
    color: #0061f2;
    border-bottom-color: #0061f2;
}
.nav-borders .nav-link {
    color: #69707a;
    border-bottom-width: 0.125rem;
    border-bottom-style: solid;
    border-bottom-color: transparent;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    padding-left: 0;
    padding-right: 0;
    margin-left: 1rem;
    margin-right: 1rem;
}
</style>
@endsection