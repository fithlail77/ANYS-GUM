@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<hr>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Activity Log</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Deskripsi</th>
                        <th>Data Lama</th>
                        <th>Data Baru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                        <td>{{ $log->causer->name ?? 'System' }}</td>
                        <td>{{ $log->event }}</td>
                        <td>{{ $log->description }}</td>
                        <td>
                            @if(isset($log->properties['asset_info']))
                                <div class="badge badge-secondary mb-1">[{{ $log->properties['asset_info']['number'] ?? '-' }}] {{ $log->properties['asset_info']['name'] }}</div>
                            @elseif($log->subject_type === 'App\Models\Asset' && $log->subject)
                                <div class="badge badge-secondary mb-1">[{{ $log->subject->asset_number }}] {{ $log->subject->asset_name }}</div>
                            @endif
                        
                            @if(isset($log->properties['old']))
                                <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                    @foreach($log->properties['old'] as $key => $value)
                                        <li>
                                            <strong>{{ $key }}:</strong> 
                                            {{ in_array($key, ['created_at', 'updated_at', 'acquisition_date']) && $value ? \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta')->format('d-m-Y H:i') : $value }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else - @endif
                        </td>
                        <td>
                            @if(isset($log->properties['asset_info']))
                                <div class="badge badge-primary mb-1">[{{ $log->properties['asset_info']['number'] ?? '-' }}] {{ $log->properties['asset_info']['name'] }}</div>
                            @elseif($log->subject_type === 'App\Models\Asset' && $log->subject)
                                <div class="badge badge-primary mb-1">[{{ $log->subject->asset_number }}] {{ $log->subject->asset_name }}</div>
                            @endif
                        
                            @if(isset($log->properties['attributes']))
                                <ul class="list-unstyled mb-0" style="font-size: 0.8rem;">
                                    @foreach($log->properties['attributes'] as $key => $value)
                                        <li>
                                            <strong>{{ $key }}:</strong> 
                                            {{ in_array($key, ['created_at', 'updated_at', 'acquisition_date']) && $value ? \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta')->format('d-m-Y H:i') : $value }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else - @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection