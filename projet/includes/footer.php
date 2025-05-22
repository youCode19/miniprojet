
<section class="advantages neumorphic p-4 mb-5">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <div class="advantage-item p-3">
                    <i class="bi bi-truck fs-1 text-primary"></i>
                    <h5>Livraison Rapide</h5>
                    <p class="mb-0">Expédition sous 24h</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="advantage-item p-3">
                    <i class="bi bi-arrow-repeat fs-1 text-primary"></i>
                    <h5>Retours Faciles</h5>
                    <p class="mb-0">30 jours pour changer d'avis</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="advantage-item p-3">
                    <i class="bi bi-shield-check fs-1 text-primary"></i>
                    <h5>Paiement Sécurisé</h5>
                    <p class="mb-0">Cryptage SSL</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="advantage-item p-3">
                    <i class="bi bi-headset fs-1 text-primary"></i>
                    <h5>Support 24/7</h5>
                    <p class="mb-0">Assistance permanente</p>
                </div>
            </div>
        </div>
    </div>
</section>
</div> <!-- Fermeture du container principal si ouvert dans le header -->
    <footer class="mt-5 py-4 neumorphic">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Tous droits réservés.</p>
        </div>
        <button onclick="getLocationAndSendToSession()" class="btn btn-primary">📍 Afficher ma localisation sur Google Maps</button>
<p id="location-output"></p>
<div id="map-frame" style="margin-top: 15px;"></div>

<script>
  function getLocationAndSendToSession() {
    const output = document.getElementById("location-output");
    const mapFrame = document.getElementById("map-frame");

    if (!navigator.geolocation) {
      output.textContent = "La géolocalisation n'est pas prise en charge par ce navigateur.";
      return;
    }

    output.textContent = "Recherche de la localisation...";

    navigator.geolocation.getCurrentPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lon = position.coords.longitude;
        output.innerHTML = `📌 Votre position actuelle : Latitude: ${lat.toFixed(6)}, Longitude: ${lon.toFixed(6)}`;

        // Affiche la carte
        const mapUrl = `https://www.google.com/maps?q=${lat},${lon}&hl=fr&z=15&output=embed`;
        mapFrame.innerHTML = `<iframe width="50%" height="200" frameborder="0" style="border:0;border-radius:10px;" src="${mapUrl}" allowfullscreen></iframe>`;

        // Envoie la localisation à PHP via AJAX
        fetch('/projet/user/save_location.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/json'},
          body: JSON.stringify({lat, lon})
        });
      },
      (error) => {
        output.textContent = "Impossible de récupérer la localisation : " + error.message;
      }
    );
  }
</script>

                <a href="/projet/cart/checkout.php" class="btn btn-success">Passer à la caisse</a>
                </div>
            </div>
        </form>
    </footer>
    <script src="/projet/assets/js/cart.js"></script>
    <script src="/projet/assets/js/search.js"></script>
    <?php if (function_exists('isAdmin') && isAdmin()): ?>
        <script src="/projet/assets/js/admin.js"></script>
    <?php endif; ?>
    
</body>
</html>