const serverUrl = "http://localhost/project";
        let correctCoordinates = null;
        let guessCoordinates = null;

        const map = L.map('map').setView([0, 0], 2);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let guessMarker = L.marker([0, 0], { draggable: true })
            .addTo(map)
            .bindPopup("Your Guess")
            .openPopup();

        guessMarker.on('dragend', () => {
            guessCoordinates = guessMarker.getLatLng();
        });

        async function fetchRandomLocation() {
            const randomLat = (Math.random() * 180 - 90).toFixed(6);
            const randomLon = (Math.random() * 360 - 180).toFixed(6);

            try {
                const response = await fetch(`${serverUrl}get_location.php?lat=${randomLat}&lon=${randomLon}`);
                const data = await response.json();

                if (data.error) {
                    alert("Failed to load location data. Try again!");
                    return;
                }

                document.getElementById('controls').innerHTML += `<p>Location: ${data.locationName}</p>`;

                correctCoordinates = { lat: data.latitude, lon: data.longitude };

                map.setView([data.latitude, data.longitude], 2);
                guessMarker.setLatLng([data.latitude, data.longitude]);

            } catch (error) {
                console.error("Error fetching location:", error);
            }
        }

        function submitGuess() {
            if (!guessCoordinates || !correctCoordinates) {
                alert("You need to place your marker and load a location first!");
                return;
            }

            const guessedLat = guessCoordinates.lat;
            const guessedLon = guessCoordinates.lng;
            const actualLat = correctCoordinates.lat;
            const actualLon = correctCoordinates.lon;

            const distance = calculateDistance(guessedLat, guessedLon, actualLat, actualLon);

            const result = document.getElementById('result');
            result.textContent = `Your guess is ${distance.toFixed(2)} km away from the actual location.`;

            L.marker([actualLat, actualLon])
                .addTo(map)
                .bindPopup("Correct Location")
                .openPopup();
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = ((lat2 - lat1) * Math.PI) / 180;
            const dLon = ((lon2 - lon1) * Math.PI) / 180;

            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);

            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        document.addEventListener('DOMContentLoaded', fetchRandomLocation);

        document.getElementById('submitGuess').addEventListener('click', submitGuess);