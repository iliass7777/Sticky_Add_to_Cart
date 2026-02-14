# 🚀 GUIDE D'INSTALLATION RAPIDE - Sticky Add to Cart

## ⚡ Installation en 3 Étapes

### 📦 Étape 1 : Préparer le Module
1. **Compresser le dossier** `stickyaddtocart` en fichier ZIP
   - Sur Windows : Clic droit → Envoyer vers → Dossier compressé
   - Le fichier doit s'appeler : `stickyaddtocart.zip`

### 📥 Étape 2 : Importer dans PrestaShop
1. Connectez-vous au **Back-Office** de votre boutique PrestaShop
2. Allez dans : **Modules** → **Module Manager**
3. Cliquez sur le bouton **"Installer un module"** (en haut à droite)
4. **Glissez-déposez** le fichier `stickyaddtocart.zip` ou cliquez pour le sélectionner
5. Attendez la fin de l'installation
6. Message de confirmation : "Module installé avec succès !"

### ⚙️ Étape 3 : Configurer le Module
1. Dans la liste des modules, recherchez **"Sticky Add to Cart"**
2. Cliquez sur **"Configurer"**
3. Ajustez les paramètres selon vos besoins :

   #### ✅ Paramètres Recommandés par Défaut
   ```
   📱 DEVICE SETTINGS
   ✓ Activer sur Mobile     : OUI
   ✓ Activer sur Tablette   : OUI
   ✓ Activer sur Desktop    : OUI
   
   📦 PRODUCT TYPE SETTINGS
   ✓ Produits Simples   : OUI
   ✓ Packs de Produits  : OUI
   ✓ Produits Virtuels  : OUI
   
   🎨 APPEARANCE SETTINGS
   - Texte du bouton        : "Ajouter au panier"
   - Afficher image produit : OUI
   - Afficher variations    : OUI
   
   🎨 COLOR SETTINGS
   - Couleur fond           : #ffffff (blanc)
   - Couleur bouton         : #25b9d7 (bleu)
   - Couleur hover          : #1fa3bf (bleu foncé)
   - Couleur texte bouton   : #ffffff (blanc)
   - Couleur prix           : #25b9d7 (bleu)
   
   🔧 BEHAVIOR SETTINGS
   - Seuil de défilement    : 300 px
   
   🚫 EXCLUSIONS
   - Catégories exclues     : (vide)
   - Produits exclus        : (vide)
   ```

4. Cliquez sur **"Enregistrer"**

### ✅ Étape 4 : Tester
1. Ouvrez votre boutique en **mode navigation privée**
2. Accédez à n'importe quelle **page produit**
3. **Faites défiler** la page vers le bas (300px)
4. Le bouton sticky doit apparaître en bas de l'écran !

---

## 🎨 PERSONNALISATION RAPIDE

### Changer les Couleurs aux Couleurs de Votre Thème
1. Allez dans **Configuration du module**
2. Section **"Color Settings"**
3. Utilisez le sélecteur de couleur pour chaque élément
4. Enregistrez → Testez sur le site

### Changer le Texte du Bouton
1. **Appearance Settings** → **Button Text**
2. Exemples :
   - `Acheter maintenant`
   - `Commander`
   - `J'achète`
   - `Ajouter au panier`

### Exclure des Catégories
1. **Exclusions** → **Excluded Categories**
2. Entrez les IDs séparés par des virgules : `3, 5, 8`
3. Le sticky ne s'affichera PAS sur les produits de ces catégories

### Exclure des Produits Spécifiques
1. **Exclusions** → **Excluded Products**
2. Entrez les IDs produits : `12, 25, 47`

---

## 🔍 COMMENT TROUVER LES IDs ?

### ID Catégorie
1. Back-Office → **Catalogue** → **Catégories**
2. Cliquez sur une catégorie
3. L'ID est dans l'URL : `...?id_category=**5**&...`

### ID Produit
1. Back-Office → **Catalogue** → **Produits**
2. Cliquez sur un produit
3. L'ID est dans l'URL : `...?id_product=**12**&...`

---

## ❓ PROBLÈMES FRÉQUENTS

### ❌ Le sticky ne s'affiche pas
**Solutions :**
1. Videz le cache : **Paramètres avancés** → **Performance** → **Vider le cache**
2. Vérifiez que le module est **activé**
3. Vérifiez les paramètres **Device Settings**
4. Testez en **mode navigation privée**

### ❌ Les couleurs ne changent pas
**Solutions :**
1. Videz le **cache PrestaShop**
2. Videz le **cache du navigateur** (Ctrl + F5)
3. Vérifiez qu'il n'y a pas de CSS en conflit dans votre thème

### ❌ Le bouton n'ajoute pas au panier
**Solutions :**
1. Vérifiez la **console JavaScript** (F12) pour erreurs
2. Testez avec un **autre navigateur**
3. Désactivez temporairement les **autres modules** de panier

### ❌ Problème sur mobile uniquement
**Solutions :**
1. Vérifiez **Device Settings** → **Enable on Mobile** = OUI
2. Testez le responsive : F12 → Mode responsive
3. Vérifiez le CSS responsive

---

## 📞 BESOIN D'AIDE ?

### 📖 Documentation Complète
Consultez le fichier **README.md** pour la documentation complète

### 🐛 Rapport de Bug
Si vous rencontrez un problème :
1. Notez la **version PrestaShop** utilisée
2. Notez le **navigateur** et appareil
3. Faites une **capture d'écran**
4. Vérifiez les **erreurs console** (F12)

### ✉️ Contact
Email support : support@example.com

---

## 🎉 FÉLICITATIONS !

Votre module **Sticky Add to Cart** est maintenant installé et configuré !

**Profitez d'une meilleure expérience utilisateur et augmentez vos conversions !** 🚀

---

**Développé par Iliass Haidi - 2026**
