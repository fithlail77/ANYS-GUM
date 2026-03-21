@extends('layouts.admin')

@section('title', 'Daftar Pengguna')

@section('content')

    <!-- Page Heading -->
    <p class="mb-4">Halaman ini menampilkan daftar seluruh pengguna yang terdaftar dalam sistem.</p>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <div>
                <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#modal-AddFFBInt" align="right">
                    <i class="fa fa-plus"></i> Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ( $user as $row )
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->email }}</td>
                            <td>
                                @foreach ($row->roles as $r)
                                {{ $r->name }}
                                @endforeach
                            </td>
                            <td>
                                <a href="{{route('users.edit' ,[$row->id])}}" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm" title="Ubah Data">
                                    <i class="fas fa-edit fa-sm text-white-50"></i>
                                </a>
                                <a href="{{route('users.password' ,[$row->id])}}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" title="Ganti Kata Sandi">
                                    <i class="fas fa-key fa-sm text-white-50"></i>
                                </a>
                                <a href="/user/hapus/{{ $row->id }}" onclick="return confirm('Yakin Ingin menghapus data?')" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" title="Hapus Data">
                                    <i class="fas fa-trash-alt fa-sm text-white-50"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $no++; ?>    
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection