@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Scan QR Code for {{ $event['name'] }}</h1>

    <div id="qr-reader" style="width: 500px"></div>
    <div id="qr-reader-results"></div>

    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle on success condition with the decoded text or result.
            console.log(`Scan result: ${decodedText}`, decodedResult);
            alert(`Scan result: ${decodedText}`);

            // Send the decoded text to the server
            fetch('/api/attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    qr_code: decodedText,
                    event_id: '{{ $eventId }}'
                })
            }).then(response => response.json())
              .then(data => {
                  console.log('Success:', data);
                  alert('Attendance marked successfully!');
              })
              .catch((error) => {
                  console.error('Error:', error);
                  alert('Error marking attendance.');
              });
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</div>
@endsection
