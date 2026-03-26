@extends('layouts.admin')

@section('title', 'Daftar Pengguna')

@section('content')

<!-- Page Heading -->
<p class="mb-4">Halaman ini menampilkan daftar seluruh pengguna yang terdaftar dalam sistem.</p>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <div>
            <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#modal-AddUsers" align="right">
                <i class="fa fa-plus"></i> Tambah
            </button>
        </div>
    </div>
</div>
<!-- DataTales -->
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
                            <a href="{{route('users.show' ,[$row->id])}}" class="d-none d-sm-inline-block btn btn-sm btn-warning shadow-sm" title="Ganti Kata Sandi">
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

<!-- Modal Add Users -->
<div class="modal inmodal fade" id="modal-AddUsers" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xs">
        <form name="frm_add" id="frm_AddUsers" class="form-horiontal" action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Tambah Data</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-20 control-label">Nama User</label>
                        <div class="col-lg-10">
                            <input type="text" name="username" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-20 control-label">Email</label>
                        <div class="col-lg-10">
                            <input type="email" name="email" required class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-20 control-label">Akses</label>
                        <div class="col-lg-10">
                            <select id="roles" name="roles" class="form-control" required>
                                <option value="">--Pilih Akses--</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection