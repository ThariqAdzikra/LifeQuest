// Motivational Quotes
const quotes = [
    "Kesuksesan adalah hasil dari persiapan kecil yang dilakukan berulang kali.",
    "Setiap hari adalah kesempatan baru untuk menjadi lebih baik.",
    "Mulailah dari mana kamu berada, gunakan apa yang kamu punya.",
    "Kegagalan adalah kesempatan untuk memulai lagi dengan lebih cerdas.",
    "Konsistensi adalah kunci kesuksesan.",
    "Jangan menunggu waktu yang tepat, ciptakan waktu itu.",
    "Perjalanan seribu mil dimulai dengan satu langkah.",
    "Kesuksesan bukan final, kegagalan bukan fatal.",
    "Masa depanmu diciptakan oleh apa yang kamu lakukan hari ini.",
    "Impian tidak bekerja kecuali kamu bekerja."
];

const quoteElement = document.getElementById('motivational-quote');
if (quoteElement) {
    const randomQuote = quotes[Math.floor(Math.random() * quotes.length)];
    quoteElement.innerHTML = `<i class="bi bi-quote me-2"></i> "${randomQuote}"`;
}

// Clock functionality
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    
    const clockEl = document.getElementById('clock');
    const dateEl = document.getElementById('date');

    if(clockEl) {
        clockEl.textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    if(dateEl) {
        dateEl.textContent = now.toLocaleDateString('id-ID', options);
    }
}

updateClock();
setInterval(updateClock, 1000);

// Weather functionality
async function fetchWeather() {
    const weatherContent = document.getElementById('weather-content');
    
    try {
        const API_KEY = '93b7587a55f39ff4f0dc94e189ea5bd3'; 
        const city = 'Pekanbaru'; // Anda bisa mengubah kota ini
        
        const response = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&lang=id&appid=${API_KEY}`
        );
        
        if (!response.ok) {
            throw new Error('Gagal mengambil data cuaca');
        }
        
        const data = await response.json();
        
        const weatherIcons = {
            'Clear': 'bi-brightness-high-fill',
            'Clouds': 'bi-cloud-fill',
            'Rain': 'bi-cloud-rain-heavy-fill',
            'Drizzle': 'bi-cloud-drizzle-fill',
            'Thunderstorm': 'bi-cloud-lightning-rain-fill',
            'Snow': 'bi-cloud-snow-fill',
            'Mist': 'bi-cloud-fog2-fill',
            'Fog': 'bi-cloud-fog2-fill'
        };
        
        const iconClass = weatherIcons[data.weather[0].main] || 'bi-cloud-sun-fill';
        
        weatherContent.innerHTML = `
            <div class="weather-main">
                <div class="weather-icon">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="weather-temp">${Math.round(data.main.temp)}°C</div>
            </div>
            <div class="weather-description">${data.weather[0].description}</div>
            <div class="weather-detail">
                <i class="bi bi-droplet-half"></i>
                <span>Kelembaban: ${data.main.humidity}%</span>
            </div>
            <div class="weather-detail">
                <i class="bi bi-wind"></i>
                <span>Angin: ${Math.round(data.wind.speed * 3.6)} km/h</span>
            </div>
        `;
    } catch (error) {
        weatherContent.innerHTML = `
            <div class="error-message">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <span>Tidak dapat memuat data cuaca.</span>
            </div>
        `;
        console.error('Weather API Error:', error);
    }
}

fetchWeather();
setInterval(fetchWeather, 600000);