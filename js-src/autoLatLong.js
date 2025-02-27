export default function autoLatLong() {
    const button = document.getElementById('auto-fill');

    button?.addEventListener('click', () => {
        const cityInput = document.getElementById('title');
        const city = cityInput.value.trim();
        const latInput = document.getElementById('city_latitude');
        const lonInput = document.getElementById('city_longitude');

        if (!city) {
            alert("Please enter a city name.");
            return;
        }

        fetch(`https://nominatim.openstreetmap.org/search?city=${city}&format=json`)
            .then(response => response.json())
            .then(locationData => {
                if (locationData.length > 0) {
                    const { lat, lon } = locationData[0];

                    // Update input fields
                    latInput.value = lat;
                    lonInput.value = lon;

                    // Simulate real input change event for WordPress autosave
                    latInput.dispatchEvent(new Event('input', { bubbles: true }));
                    lonInput.dispatchEvent(new Event('input', { bubbles: true }));

                } else {
                    alert("Location not found.");
                }
            })
            .catch(error => console.error('Geocoding API Error:', error));
    });
}
