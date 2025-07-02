document.addEventListener('DOMContentLoaded', function() {
  if (!window.saabScreeningLocations || !window.L) return;
  const map = L.map('leaflet-map', {
    scrollWheelZoom: false,
    zoomControl: true,
    attributionControl: false
  }).setView([34.0, 18.0], 2);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    minZoom: 2,
    maxZoom: 18,
    opacity: 0.95
  }).addTo(map);

  // Custom yellow SVG marker
  const yellowIcon = L.divIcon({
    className: 'custom-leaflet-marker',
    html: `<svg width="32" height="42" viewBox="0 0 32 42" fill="none" xmlns="http://www.w3.org/2000/svg">
      <ellipse cx="16" cy="16" rx="14" ry="14" fill="#FFD700" stroke="#FFD700" stroke-width="2"/>
      <path d="M16 41C16 41 28 27.5 28 16C28 8.26801 21.732 2 14 2C6.26801 2 0 8.26801 0 16C0 27.5 16 41 16 41Z" fill="#FFD700" stroke="#FFD700" stroke-width="2"/>
    </svg>`,
    iconSize: [32, 42],
    iconAnchor: [16, 42],
    popupAnchor: [0, -36]
  });

  window.saabScreeningLocations.forEach(loc => {
    const marker = L.marker([loc.lat, loc.lng], { icon: yellowIcon }).addTo(map);
    marker.bindPopup(`<a href="${loc.permalink}" style="font-weight:bold; color:#1E3A8A;">${loc.title}</a>`);
    marker.on('click', function() { window.location = loc.permalink; });
  });
}); 