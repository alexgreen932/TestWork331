export default function weatherApi() {
    const items = document.querySelectorAll('[data-city]');
    
    items.forEach(item => {
        if (item.dataset.fetched) return; // Prevent duplicate fetch calls
        
        const city = item.getAttribute('data-city');
        
        fetch(`https://nominatim.openstreetmap.org/search?city=${city}&format=json`)
            .then(response => response.json())
            .then(locationData => {
                if (locationData.length > 0) {
                    const { lat, lon } = locationData[0];
                    
                    fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`)
                        .then(response => response.json())
                        .then(weatherData => {
                            const temperature = weatherData.current_weather.temperature;
                            item.innerHTML = `<p>Temperature: ${temperature}°C</p>`;
                            item.dataset.fetched = "true"; // Mark as fetched
                        })
                        .catch(error => console.error('Weather API Error:', error));
                } else {
                    item.innerHTML = `<p>Location not found.</p>`;
                }
            })
            .catch(error => console.error('Geocoding API Error:', error));
    });
}
