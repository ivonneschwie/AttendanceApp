@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white shadow-md rounded-lg p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Scan QR Code</h2>
        
        <div id="reader" width="100%"></div>
        
        <form id="attendance-form" action="/student/scan" method="POST" class="hidden">
            @csrf
            <input type="hidden" name="room_code" id="room_code">
        </form>
    </div>
</div>

@section('scripts')
<script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
<script>
    function onScanSuccess(decodedText, decodedResult) {
        document.getElementById('room_code').value = decodedText;
        document.getElementById('attendance-form').submit();
    }

    var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection
