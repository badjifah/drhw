    </main>

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" aria-label="Retour en haut">
    <i class="fas fa-arrow-up"></i>
</button>

<footer class="site-footer" role="contentinfo">
    <div class="site-footer-grid">

        <!-- Colonne marque -->
        <div>
            <div class="site-footer-brand-icon" aria-hidden="true">
                <i class="fas fa-shield-halved"></i>
            </div>
            <p class="site-footer-brand-name">Direction des Ressources Humaines</p>
            <p class="site-footer-brand-desc">
                Portail officiel de la DRH du Ministère de la Sécurité et de la Protection Civile.<br>
                Au service des agents et du personnel du ministère.
            </p>
        </div>

        <!-- Colonne navigation rapide -->
        <div class="site-footer-col">
            <h4>Navigation</h4>
            <ul class="site-footer-links">
                <li><a href="<?= BASE_URL ?>/index.php"><i class="fas fa-house"></i> Accueil</a></li>
                <li><a href="<?= BASE_URL ?>/about.php"><i class="fas fa-circle-info"></i> À propos</a></li>
                <li><a href="<?= BASE_URL ?>/services.php"><i class="fas fa-building"></i> Services</a></li>
                <li><a href="<?= BASE_URL ?>/actualites.php"><i class="fas fa-newspaper"></i> Actualités</a></li>
                <li><a href="<?= BASE_URL ?>/documents.php"><i class="fas fa-file-lines"></i> Documents</a></li>
                <li><a href="<?= BASE_URL ?>/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
            </ul>
        </div>

        <!-- Colonne contact -->
        <div class="site-footer-col">
            <h4>Contact</h4>
            <ul class="site-footer-links">
                <li>
                    <a href="tel:+22500000000">
                        <i class="fas fa-phone"></i>
                        <?= htmlspecialchars(getSetting('site_phone', CONTACT_PHONE)) ?>
                    </a>
                </li>
                <li>
                    <a href="mailto:<?= htmlspecialchars(getSetting('site_email', CONTACT_EMAIL)) ?>">
                        <i class="fas fa-envelope"></i>
                        <?= htmlspecialchars(getSetting('site_email', CONTACT_EMAIL)) ?>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/contact.php">
                        <i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars(getSetting('site_address', CONTACT_ADDRESS)) ?>
                    </a>
                </li>
                <li>
                    <a href="#" aria-label="Horaires d'ouverture">
                        <i class="fas fa-clock"></i>
                        Lun–Ven : 08h00 – 17h00
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="site-footer-bottom">
        <span>&copy; <?= date('Y') ?> DRH — Direction des Ressources Humaines. Tous droits réservés.</span>
        <span>
            <a href="<?= BASE_URL ?>/admin/login.php"><i class="fas fa-lock"></i> Administration</a>
        </span>
    </div>
</footer>

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
