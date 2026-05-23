var _ed = window.eventoData || {};

try {
    if (_ed.latitud && _ed.longitud) {
        var _mapa = L.map('mapa-evento').setView([_ed.latitud, _ed.longitud], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
        }).addTo(_mapa);
        var _icono = L.divIcon({
            html: '<div style="background:linear-gradient(135deg,#7c3aed,#a855f7);width:32px;height:32px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid white;box-shadow:0 2px 8px rgba(0,0,0,0.3)"></div>',
            iconSize:    [32, 32],
            iconAnchor:  [16, 32],
            popupAnchor: [0, -35],
            className:   ''
        });
        L.marker([_ed.latitud, _ed.longitud], { icon: _icono })
            .addTo(_mapa)
            .bindPopup('<strong>' + (_ed.nombreUbicacion || '') + '</strong>')
            .openPopup();
    }
} catch(e) {
    console.warn('Mapa error:', e);
}
