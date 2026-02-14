# Sticky Add to Cart - PrestaShop Module

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PrestaShop](https://img.shields.io/badge/PrestaShop-1.7.0%20to%208.2-green.svg)
![License](https://img.shields.io/badge/license-MIT-orange.svg)

## 📝 Description

Module PrestaShop qui ajoute un bouton "Ajouter au panier" sticky (fixe) sur les pages produits qui reste visible pendant le défilement. Augmentez vos taux de conversion avec cette fonctionnalité essentielle !

## ✨ Fonctionnalités

### 🎯 Fonctionnalités Principales
- ✅ Barre sticky fixe en bas de page
- ✅ Affichage de l'image du produit (optionnel)
- ✅ Affichage du nom et prix du produit
- ✅ Affichage des variations sélectionnées (taille, couleur, etc.)
- ✅ Ajout au panier AJAX sans rechargement de page
- ✅ Message de confirmation animé
- ✅ Design 100% responsive (Desktop, Tablette, Mobile)
- ✅ Compatible avec toutes les versions PrestaShop 1.7.0 à 8.2

### ⚙️ Configuration Complète (Back-Office)

#### 📱 **Paramètres par Device**
- Activer/Désactiver sur Mobile
- Activer/Désactiver sur Tablette
- Activer/Désactiver sur Desktop

#### 📦 **Paramètres par Type de Produit**
- Activer/Désactiver pour produits simples
- Activer/Désactiver pour packs de produits
- Activer/Désactiver pour produits virtuels

#### 🎨 **Personnalisation Visuelle**
- Texte du bouton personnalisable (ex: "Acheter maintenant")
- Afficher/Masquer l'image du produit
- Afficher/Masquer les variations du produit
- **Couleurs personnalisables :**
  - Couleur de fond de la barre
  - Couleur du bouton
  - Couleur du bouton au survol
  - Couleur du texte du bouton
  - Couleur du prix

#### 🔧 **Paramètres de Comportement**
- Seuil de défilement personnalisable (en pixels)
- Transitions et animations fluides

#### 🚫 **Système d'Exclusions**
- Exclure des catégories spécifiques (par ID)
- Exclure des produits spécifiques (par ID)

#### 💻 **Paramètres Avancés**
- CSS personnalisé pour ajustements fins
- Compatible avec modules tiers (Zendesk, Intercom, etc.)

## 📦 Installation

### Depuis le Back-Office
1. Connectez-vous au back-office de votre boutique PrestaShop
2. Allez dans **Modules** → **Module Manager**
3. Cliquez sur **Installer un module**
4. Sélectionnez le fichier `stickyaddtocart.zip`
5. Recherchez "Sticky Add to Cart" dans la liste des modules
6. Cliquez sur **Configurer**

### Par FTP
1. Décompressez le fichier `stickyaddtocart.zip`
2. Uploadez le dossier `stickyaddtocart` dans `/modules/` de votre boutique
3. Allez dans **Modules** → **Module Manager**
4. Recherchez "Sticky Add to Cart"
5. Cliquez sur **Installer**

## ⚙️ Configuration

1. Allez dans **Modules** → **Module Manager**
2. Recherchez "Sticky Add to Cart"
3. Cliquez sur **Configurer**
4. Ajustez les paramètres selon vos besoins :
   - **Device Settings** : Activez/désactivez par type d'appareil
   - **Product Type Settings** : Choisissez les types de produits
   - **Appearance Settings** : Personnalisez le texte et l'affichage
   - **Color Settings** : Définissez votre palette de couleurs
   - **Behavior Settings** : Ajustez le seuil de défilement
   - **Exclusions** : Excluez catégories/produits (IDs séparés par virgules)
   - **Advanced Settings** : Ajoutez du CSS personnalisé
5. Cliquez sur **Enregistrer**

## 🎨 Personnalisation

### Couleurs par Défaut
- **Fond de la barre** : `#ffffff` (blanc)
- **Bouton** : `#25b9d7` (bleu turquoise)
- **Bouton Hover** : `#1fa3bf` (bleu foncé)
- **Texte bouton** : `#ffffff` (blanc)
- **Prix** : `#25b9d7` (bleu turquoise)

### CSS Personnalisé
Ajoutez votre CSS dans **Advanced Settings** → **Custom CSS**

Exemple :
```css
.sticky-add-to-cart {
    box-shadow: 0 -5px 15px rgba(0,0,0,0.2);
}

.sticky-add-btn {
    border-radius: 25px;
    text-transform: uppercase;
}
```

## 📱 Responsive Design

### Desktop (> 768px)
- Affichage complet : Image + Nom + Variations + Prix + Bouton avec texte

### Tablette (577px - 768px)
- Image réduite + Nom + Prix + Bouton avec texte

### Mobile (≤ 576px)
- Petite image + Nom + Prix + Bouton icône uniquement
- Variations masquées pour optimiser l'espace

## 🔄 Compatibilité

### PrestaShop
- ✅ 1.7.0 à 1.7.8
- ✅ 8.0 à 8.2

### Modules
- ✅ Compatible avec blockcart (affichage modal panier)
- ✅ Compatible avec attributs et déclinaisons produits
- ✅ Compatible avec produits personnalisables
- ✅ Compatible multisite

## 🚀 Performance

- **Optimisations :**
  - Throttling du scroll (100ms) pour limiter la charge CPU
  - Chargement conditionnel (uniquement pages produits)
  - Lazy loading des images
  - CSS/JS minifiés (en production)

## 📁 Structure des Fichiers

```
stickyaddtocart/
├── stickyaddtocart.php          [Module principal - 600+ lignes]
├── index.php                     [Protection sécurité]
├── README.md                     [Documentation]
├── translations/
│   └── index.php
├── views/
    ├── css/
    │   ├── stickyaddtocart.css  [Styles - 240+ lignes]
    │   └── index.php
    ├── js/
    │   ├── stickyaddtocart.js   [Logique frontend - 170+ lignes]
    │   └── index.php
    └── templates/hook/
        ├── sticky-button.tpl     [Template Smarty]
        └── index.php
```

## 🔧 Hooks Utilisés

| Hook | Description |
|------|-------------|
| `displayHeader` | Charge CSS/JS sur pages produits |
| `displayFooterProduct` | Affiche la barre sticky |

## 🐛 Dépannage

### La barre sticky ne s'affiche pas
1. Vérifiez que le module est activé dans **Modules**
2. Vérifiez les paramètres **Device Settings** (mobile/tablette/desktop)
3. Vérifiez que le produit/catégorie n'est pas dans les exclusions
4. Videz le cache PrestaShop

### Le bouton n'ajoute pas au panier
1. Vérifiez que JavaScript est activé
2. Vérifiez la console navigateur pour erreurs
3. Vérifiez la compatibilité avec le thème

### Les couleurs ne changent pas
1. Videz le cache navigateur (Ctrl+F5)
2. Videz le cache PrestaShop
3. Vérifiez qu'il n'y a pas de CSS en conflit

### Problème de responsive
1. Vérifiez que le CSS du module est bien chargé
2. Testez avec le CSS personnalisé désactivé

## 📊 Statistiques

- **Lignes de code** : ~1000+ lignes
- **Fichiers PHP** : 6
- **Fichiers JS** : 1 (170 lignes)
- **Fichiers CSS** : 1 (240 lignes)
- **Templates** : 1

## 📝 Changelog

### Version 1.0.0 (2026-02-13)
- ✨ Version initiale complète
- ✅ Page de configuration back-office
- ✅ Détection device (Mobile/Tablette/Desktop)
- ✅ Détection type de produit (Simple/Pack/Virtual)
- ✅ Affichage image produit
- ✅ Affichage variations produit
- ✅ Personnalisation complète des couleurs
- ✅ Système d'exclusions (produits/catégories)
- ✅ CSS personnalisé
- ✅ Seuil de défilement configurable
- ✅ Design responsive complet
- ✅ Animations et transitions fluides

## 👨‍💻 Auteur

**Iliass Haidi**
- Email : votre.email@example.com
- GitHub : https://github.com/votre-username

## 📄 Licence

MIT License - Copyright (c) 2026 Iliass Haidi

## 🙏 Remerciements

Inspiré par le module officiel PrestaShop Addons :
https://addons.prestashop.com/en/express-checkout-process/30901-sticky-add-to-cart-on-product-pages.html

## 📞 Support

Pour toute question ou problème :
1. Consultez cette documentation
2. Vérifiez les problèmes connus ci-dessus
3. Contactez le support : support@example.com

---

**Note**: Module développé et testé sur PrestaShop 1.7.x. Pour les versions 8.x, des ajustements mineurs peuvent être nécessaires.
