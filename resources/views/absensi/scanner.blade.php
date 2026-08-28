@extends('layouts.app')

@section('title', 'Absensi QR Scanner')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-semibold mb-4">Absensi QR Scanner</h1>
    <div id="qr-reader" style="width:100%; max-width:500px;"></div>
    <div id="qr-result" class="mt-4 text-lg"></div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
<script>
    const resultDiv = document.getElementById('qr-result');
    function onScanSuccess(decodedText, decodedResult) {
        const scanner = window.html5QrcodeScannerInstance;
        if (scanner) {
            scanner.clear().then(_ => {
                resultDiv.innerHTML = `<span class="text-green-600">Mencatat kehadiran...</span>`;
                fetch('{{ route('absensi.scan') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ token: decodedText })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        resultDiv.innerHTML = `<span class="text-green-600">Berhasil ${data.type} pada ${data.waktu}</span>`;
                    } else {
                        resultDiv.innerHTML = `<span class="text-red-600">Error: ${data.message || 'Gagal'}.</span>`;
                    }
                })
                .catch(err => {
                    resultDiv.innerHTML = `<span class="text-red-600">Network error.</span>`;
                });
            });
        }
    }

    const config = { fps: 10, qrbox: 250 };
    const scannerInstance = new Html5Qrcode("qr-reader");
    window.html5QrcodeScannerInstance = scannerInstance;
    scannerInstance.start({ facingMode: "environment" }, config, onScanSuccess);
</script>
@endpush
