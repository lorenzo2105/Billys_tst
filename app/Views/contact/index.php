<section class="section">
    <div class="container" style="max-width: 800px;">
        <h1 class="section__title">Nous Contacter</h1>
        <p class="section__subtitle">Une question ? Une suggestion ? N'hésitez pas à nous écrire !</p>

        <div class="contact-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin-top: 2rem;">
            
            <!-- Contact Form -->
            <div class="admin-card">
                <div class="admin-card__header">
                    <h2>📧 Envoyez-nous un message</h2>
                </div>
                <form method="POST" action="<?= $baseUrl ?>/contact/send" class="form">
                    <div class="form-group">
                        <label for="name">Nom complet *</label>
                        <input type="text" id="name" name="name" required
                               value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone"
                               value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="subject">Sujet *</label>
                        <select id="subject" name="subject" required>
                            <option value="">-- Choisir un sujet --</option>
                            <option value="question" <?= ($old['subject'] ?? '') === 'question' ? 'selected' : '' ?>>Question générale</option>
                            <option value="commande" <?= ($old['subject'] ?? '') === 'commande' ? 'selected' : '' ?>>Problème de commande</option>
                            <option value="suggestion" <?= ($old['subject'] ?? '') === 'suggestion' ? 'selected' : '' ?>>Suggestion</option>
                            <option value="reclamation" <?= ($old['subject'] ?? '') === 'reclamation' ? 'selected' : '' ?>>Réclamation</option>
                            <option value="autre" <?= ($old['subject'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message">Message *</label>
                        <textarea id="message" name="message" rows="6" required><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn--primary btn--lg">
                            Envoyer le message
                        </button>
                    </div>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="contact-info" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                <?php foreach ($restaurants as $restaurant): ?>
                <div class="admin-card">
                    <div class="admin-card__header">
                        <h3>🏪 <?= htmlspecialchars($restaurant['name']) ?></h3>
                    </div>
                    <div style="padding: 1rem;">
                        <p style="margin-bottom: 0.75rem;">
                            <strong>📍 Adresse :</strong><br>
                            <?= htmlspecialchars($restaurant['address']) ?>
                        </p>
                        <?php if ($restaurant['phone']): ?>
                        <p style="margin-bottom: 0.75rem;">
                            <strong>📞 Téléphone :</strong><br>
                            <a href="tel:<?= htmlspecialchars($restaurant['phone']) ?>" style="color: var(--primary)">
                                <?= htmlspecialchars($restaurant['phone']) ?>
                            </a>
                        </p>
                        <?php endif; ?>
                        <?php if ($restaurant['opening_hours']): ?>
                        <p style="margin-bottom: 0;">
                            <strong>🕐 Horaires :</strong><br>
                            <?= nl2br(htmlspecialchars($restaurant['opening_hours'])) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>
