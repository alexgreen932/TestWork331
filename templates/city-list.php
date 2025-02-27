<?php
/**
 * Template Name: City List Page
 *
 * A custom template for displaying a paginated list of cities with search.
 */

global $wpdb;
get_header();

do_action('before_city_list');

$cities = CityListPage::get_cities(20);
?>
<div class="uk-container uk-container-center">
    <h1><?php the_title(); ?></h1>
    
    <!-- Search Field -->
    <div class="search-container">
        <input type="text" id="city-search" placeholder="Search for a city..." />
        <button id="search-button">Search</button>
    </div>
    
    <!-- Table -->
    <table id="city-list">
        <thead>
            <tr>
                <th>City</th>
                <th>Country</th>
                <th>Weather Now</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cities as $city): ?>
                <tr>
                    <td><?php echo esc_html($city->post_title); ?></td>
                    <td><?php echo esc_html($city->country ?? 'Unknown'); ?></td>
                    <td><span data-city="<?php echo esc_attr($city->post_title); ?>"></span></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    function fetchCities(searchQuery) {
        fetch(ajaxurl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=city_search&search=${encodeURIComponent(searchQuery)}`
        })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#city-list tbody');
            tableBody.innerHTML = '';
            data.forEach(city => {
                tableBody.innerHTML += `<tr>
                    <td>${city.post_title}</td>
                    <td>${city.country ?? 'Unknown'}</td>
                    <td><span data-city="${city.post_title}"></span></td>
                </tr>`;
            });
            fetchWeather(); // Fetch weather after updating cities list
        })
        .catch(error => console.error('Error fetching cities:', error));
    }

    function fetchWeather() {
        document.querySelectorAll('[data-city]').forEach(item => {
            const city = item.getAttribute('data-city');
            fetch(`https://nominatim.openstreetmap.org/search?city=${city}&format=json`)
                .then(response => response.json())
                .then(locationData => {
                    if (locationData.length > 0) {
                        const { lat, lon } = locationData[0];
                        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`)
                            .then(response => response.json())
                            .then(weatherData => {
                                item.innerText = `${weatherData.current_weather.temperature}°C`;
                            })
                            .catch(error => console.error('Weather API Error:', error));
                    } else {
                        item.innerText = 'Location not found';
                    }
                })
                .catch(error => console.error('Geocoding API Error:', error));
        });
    }

    document.getElementById('city-search').addEventListener('input', function() {
        fetchCities(this.value.trim());
    });
    
    document.getElementById('search-button').addEventListener('click', function() {
        const searchInput = document.getElementById('city-search');
        fetchCities(searchInput.value.trim());
    });
    
    fetchWeather(); // Fetch weather on initial load
</script>
<?php
do_action('after_city_list');
get_footer();