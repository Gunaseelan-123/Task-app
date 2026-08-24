<div id="live-tracking-map" data-rider-id="{{ $rider->id ?? '' }}" style="width:100%; height:420px; border-radius:12px; overflow:hidden;"></div>

{{-- Usage:
    Include Leaflet CSS/JS in the page header/footer e.g.
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    Ensure Laravel Echo is available and configured (Echo + Pusher or laravel-websockets).
    To activate rider device broadcasting, add an element with data-driver-tracker on the rider device page.
--}}