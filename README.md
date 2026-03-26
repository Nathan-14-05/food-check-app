# 🍎 Food-Check

**Food-Check** est une application web intelligente développée avec **Laravel 11**, conçue pour aider les utilisateurs à suivre leur inventaire alimentaire tout en analysant la qualité de leur nutrition.

L'application automatise la saisie des données en communiquant directement avec l'API **Open Food Facts** pour récupérer les informations nutritionnelles via un code-barres.

---

## ✨ Fonctionnalités

* 🔍 **Scan & Auto-fill** : Saisie d'un code-barres avec récupération instantanée (Nom, Marque, Calories, Nutriscore) via l'API Open Food Facts.
* 📊 **Dashboard Analytique** : Un tableau de bord dynamique qui calcule la moyenne calorique et affiche la répartition de l'équilibre alimentaire.
* 🟢 **Visualisation Simplifiée** : Un graphique en "Donut" (Chart.js) classant les produits en trois catégories : *Sain (A/B)*, *Modéré (C)* et *À limiter (D/E)*.
* 🛠️ **Gestion Complète (CRUD)** : Possibilité d'ajouter, de lister et de supprimer des produits de son historique personnel.
* 📱 **Interface Responsive** : Design moderne et épuré conçu avec Tailwind CSS.

---

## 🛠️ Architecture Technique

* **Framework :** Laravel 11 (PHP 8.2+)
* **Frontend :** Blade Templates & Tailwind CSS
* **Data Visualization :** Chart.js
* **API Integration :** Open Food Facts API (REST)
* **Base de données :** SQLite (ou MySQL selon configuration)

---

## ⚙️ Installation Rapide

1. **Cloner le dépôt**
   ```bash
   git clone [https://github.com/votre-utilisateur/votre-repo.git](https://github.com/votre-utilisateur/votre-repo.git)
   cd votre-repo
