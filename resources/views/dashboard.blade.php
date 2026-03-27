@extends('layouts.admin')

@section('title', 'Dashboard Utama')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    Selamat Datang, <strong>{{ Auth::user()->name }}</strong>! 
                    <p class="text-muted small">Role: {{ Auth::user()->getRoleNames()->first() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencarian Asset Section -->
    <div class="row">
        <div class="col-xl-6 col-md-8 col-sm-12">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-search"></i> Cari Data Asset</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="searchCode">Scan Barcode atau Masukkan Nomor Asset</label>
                        <div class="input-group">
                            <input type="text" id="searchCode" class="form-control" placeholder="Contoh: 202401-GUM-0001" autofocus>
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="btnSearch" type="button">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-success btn-block py-3" id="btnOpenScanner">
                            <i class="fas fa-camera"></i> Scan Menggunakan Kamera
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Scanner Kamera -->
    <div class="modal fade" id="modal-Scanner" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Scan QR / Barcode Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeScanner">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="reader" style="width: 100%; background: #000;"></div>
                    <div id="scannerPlaceholder" class="text-center p-5 text-muted">
                        <i class="fas fa-video-slash fa-3x mb-3"></i>
                        <p>Menginisialisasi Kamera...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Asset (Reuse Logic from Index) -->
    <div class="modal fade" id="modal-ShowDataAsset" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Data Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Diisi via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    $(document).ready(function() {
        let html5QrCode;

        // Fungsi untuk mengambil detail aset berdasarkan ID
        function showAssetDetail(assetId) {
            const url = "{{ route('assets.show', ':id') }}".replace(':id', assetId);
            const modal = $('#modal-ShowDataAsset');
            const modalBody = modal.find('.modal-body');

            modalBody.html('<div class="text-center p-5"><i class="fa fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-2">Memuat data...</p></div>');
            modal.modal('show');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    modalBody.html(response);
                },
                error: function() {
                    modalBody.html('<div class="alert alert-danger">Gagal memuat data.</div>');
                }
            });
        }

        // Fungsi mencari asset berdasarkan kode
        function searchAsset(code) {
            code = code.toString().trim();
            if (!code) return;
            
            $.ajax({
                url: "{{ url('assets/find-by-code') }}/" + encodeURIComponent(code),
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        showAssetDetail(response.id);
                        $('#searchCode').val('');
                    } else {
                        toastr.error(response.message);
                        $('#searchCode').select();
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Terjadi kesalahan sistem saat mencari data.');
                }
            });
        }

        // Cek koneksi HTTPS
        function checkSecureContext() {
            if (window.location.protocol !== 'https:' && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1') {
                console.warn("Aplikasi berjalan di HTTP. Pastikan 'Insecure origins treated as secure' sudah diaktifkan di browser.");
                return true; // Izinkan lanjut untuk mencoba (dengan asumsi flag browser sudah diset)
            }
            return true;
        }

        // Event Enter di input
        $('#searchCode').on('keypress', function(e) {
            if (e.which == 13) {
                searchAsset($(this).val());
            }
        });

        $('#btnSearch').on('click', function() {
            searchAsset($('#searchCode').val());
        });

        // Buka modal dan inisialisasi scanner
        $('#btnOpenScanner').on('click', function() {
            if (!checkSecureContext()) return;
            
            $('#modal-Scanner').modal('show');
            
            // Beri sedikit delay agar elemen #reader dirender sempurna oleh modal
            setTimeout(() => {
                if (!html5QrCode) {
                    html5QrCode = new Html5Qrcode("reader");
                }
                
                const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                
                html5QrCode.start(
                    { facingMode: "environment" }, 
                    config,
                    (decodedText) => {
                        html5QrCode.stop().then(() => {
                            $('#modal-Scanner').modal('hide');
                            searchAsset(decodedText);
                        });
                    }
                ).then(() => {
                    $('#scannerPlaceholder').hide();
                }).catch((err) => {
                    console.error("Gagal menjalankan kamera", err);
                    toastr.error("Gagal mengakses kamera. Pada koneksi HTTP (Lokal), Anda harus mengaktifkan izin khusus di pengaturan browser.");
                    
                    // Berikan petunjuk teknis di console untuk admin
                    console.error("TIPS: Buka chrome://flags/#unsafely-treat-insecure-origin-as-secure di browser HP, lalu masukkan URL: " + window.location.origin);
                    $('#modal-Scanner').modal('hide');
                });
            }, 500);
        });

        // Stop kamera jika modal ditutup
        $('#modal-Scanner').on('hidden.bs.modal', function () {
            if (html5QrCode) {
                html5QrCode.stop().catch(err => console.error("Error stopping scanner", err));
                $('#scannerPlaceholder').show();
            }
        });
    });
</script>
@endpush