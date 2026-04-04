<?php
session_start();
require_once 'autoload.php';
?>



<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Avella — Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        cara: {
          100: '#f4e5d9', 200: '#eddcce', 400: '#ccab89',
          500: '#c99f85', 600: '#a8825f', 700: '#75502a',
          800: '#5d4133', 900: '#382301',
        },
        gold: '#6c550f',
      },
      fontFamily: {
        display: ['"Playfair Display"', 'serif'],
      },
    },
  },
}
</script>
<style>
  body { font-family: 'DM Sans', sans-serif; }
  @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
  .live-dot { animation: pulse-dot 2s infinite; }
  @keyframes modal-up { from{transform:translateY(12px);opacity:0} to{transform:translateY(0);opacity:1} }
  .modal-box { animation: modal-up .2s ease; }
</style>
</head>
<body class="bg-cara-100 text-cara-800 min-h-screen flex text-sm">

<!-- ════════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
<aside class="w-64 min-h-screen bg-cara-500 border-r border-cara-600/30 flex flex-col fixed top-0 left-0 bottom-0 z-50">

  <!-- Logo -->
  <div class="px-5 py-6 border-b border-cara-600/30">
    <span class="font-display text-4xl font-bold text-cara-100 block leading-none">AVELLA</span>
    <span class="text-[10px] text-cara-800 tracking-[3px] uppercase mt-1 block">Administration</span>
  </div>

  <!-- Navigation -->
  <nav class="flex-1 py-3 overflow-y-auto">

    <!-- Exemple de groupe -->
    <span class="text-[10px] tracking-[2px] uppercase text-cara-800/60 px-5 py-3 block">Général</span>
    <button onclick="goTo('dashboard', this)" class="nav-btn active-nav flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      <!-- Icône SVG ici -->
      Dashboard
    </button>

    <span class="text-[10px] tracking-[2px] uppercase text-cara-800/60 px-5 py-3 block mt-1">Catalogue</span>
    <button onclick="goTo('produits', this)" class="nav-btn flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      Produits
    </button>
    <button onclick="goTo('categories', this)" class="nav-btn flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      Catégories
    </button>
    <button onclick="goTo('boutiques', this)" class="nav-btn flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      Boutiques
    </button>

    <span class="text-[10px] tracking-[2px] uppercase text-cara-800/60 px-5 py-3 block mt-1">Membres</span>
    <button onclick="goTo('users', this)" class="nav-btn flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      Utilisateurs
    </button>
    <button onclick="goTo('commandes', this)" class="nav-btn flex items-center gap-2.5 w-[calc(100%-20px)] mx-2.5 px-3 py-2.5 rounded-xl text-cara-900 text-sm transition-all hover:bg-white/40">
      Commandes
    </button>

  </nav>

  <!-- Pied de sidebar -->
  <div class="px-4 py-4 border-t border-cara-600/30">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-full bg-cara-400 border-2 border-cara-800 flex items-center justify-center font-display font-bold text-cara-900">A</div>
      <div>
        <div class="text-sm font-medium text-cara-900">Admin Avella</div>
        <div class="text-[10px] text-cara-700 uppercase tracking-widest">Super Admin</div>
      </div>
    </div>
  </div>

</aside>

<!-- ════════════════════════════════════════════
     MAIN
════════════════════════════════════════════ -->
<main class="ml-64 flex-1 flex flex-col min-h-screen">

  <!-- Topbar -->
  <header class="h-16 bg-cara-500 border-b border-cara-600/30 flex items-center justify-between px-7 sticky top-0 z-40">
    <span id="page-title" class="font-display text-xl italic text-cara-900">Tableau de bord</span>
    <div class="flex items-center gap-2 text-xs text-cara-800 bg-white/30 px-3 py-1.5 rounded-full border border-cara-600/20">
      <span class="live-dot w-2 h-2 rounded-full bg-green-500 inline-block"></span>
      Avella · Live
    </div>
  </header>

  <!-- Contenu -->
  <div class="flex-1 p-7">

    <!-- ─── PAGE : DASHBOARD ─── -->
    <div id="page-dashboard" class="page">

      <!-- Cartes stats  →  remplir à ta façon -->
      <div class="grid grid-cols-4 gap-4 mb-8">
        <!-- Exemple de carte -->
        <div class="bg-cara-500 border border-cara-600/30 rounded-2xl p-5 relative overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all">
          <div class="font-display text-4xl font-bold text-cara-900 leading-none mb-1"><?php $user=new users(); echo $user->count() ; ?></div>
          <div class="text-[10px] tracking-[2.5px] uppercase text-cara-700">Utilisateurs</div>
        </div>
        <!-- ... autres cartes ... -->
          <div class="bg-cara-500 border border-cara-600/30 rounded-2xl p-5 relative overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all">
   <div class="font-display text-4xl font-bold text-cara-900 leading-none mb-1"><?php $boutiq=new boutiques(); echo $boutiq->count() ; ?></div>
   <div class="text-[10px] tracking-[2.5px] uppercase text-cara-700">Boutiques</div>
 </div>
 <!-- autre -->
   <div class="bg-cara-500 border border-cara-600/30 rounded-2xl p-5 relative overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all">
   <div class="font-display text-4xl font-bold text-cara-900 leading-none mb-1"><?php $cat=new categories(); echo $cat->count() ; ?></div>
   <div class="text-[10px] tracking-[2.5px] uppercase text-cara-700">Catégories</div>
 </div>
 <!-- autre -->
    <div class="bg-cara-500 border border-cara-600/30 rounded-2xl p-5 relative overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all">
    <div class="font-display text-4xl font-bold text-cara-900 leading-none mb-1"><?php $prod=new produits(); echo $prod->count() ; ?></div>
    <div class="text-[10px] tracking-[2.5px] uppercase text-cara-700">Produits</div>
  </div>
      </div>

      <!-- Actions rapides  →  remplir à ta façon -->
      <h2 class="font-display text-xl italic text-gold mb-4">Actions rapides</h2>
      <div class="grid grid-cols-4 gap-4">
        <!-- Exemple de bouton rapide -->
        <button class="bg-cara-500 border border-cara-600/30 rounded-2xl p-5 text-center hover:bg-cara-400 hover:-translate-y-1 hover:shadow-lg transition-all group">
          <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">➕</div>
          <span class="font-display text-sm text-cara-900">Nouveau Admin</span>
        </button>
        <!-- ... autres boutons ... -->
      </div>

    </div>

    <!-- ─── PAGE : PRODUITS ─── -->
    <div id="page-produits" class="page hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl italic text-gold">Produits</h2>
        <button onclick="openModal('modal-produit')" class="bg-cara-400 text-cara-900 border border-cara-600/40 px-4 py-2 rounded-xl text-sm font-medium hover:bg-amber-200 transition-all">+ Ajouter</button>
      </div>

      <!-- Table  →  remplir à ta façon -->
      <div class="bg-cara-500 border border-cara-600/30 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-cara-600/20 bg-white/10">
          <input type="text" placeholder="Rechercher…" oninput="filterTable(this, 'table-produits')"
            class="w-full max-w-sm bg-cara-200 border border-cara-600/30 rounded-full px-4 py-2 text-sm text-cara-900 placeholder-cara-600 focus:outline-none focus:border-cara-600">
        </div>
        <table class="w-full border-separate border-spacing-0 rounded-lg overflow-hidden" id="table-produits">
          <thead>
            <tr>
              <!-- Colonnes  →  à personnaliser -->
              <th class="th">#id</th>
              <th class="th">Nom</th>
              <th class="th">Catégorie</th>
              <th class="th">description</th>
                <th class="th">boutique</th>
              <th class="th">Prix</th>
              <th class="th">image</th>
              <th class="th">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $prod=new produits();
            $tab=$prod->products_info();
            foreach($tab as $row):
              ?>
              <tr class="h-20 ">
              <td class="border-b border-l border-white/40 p-2 text-center" id="id"> <?php echo $row->id ?></td>
               <td class="border-b border-white/40 text-center"> <?php echo $row->nom ?></td>
                <td class="border-b border-white/40 text-center"> <?php echo $row->categorie ?></td>
                 <td class="border-b border-white/40 text-center"> <?php echo $row->description?></td>
                  <td class="border-b border-white/40 text-center"> <?php echo $row->boutique ?></td>
                  <td class="border-b border-white/40 text-center"> <?php echo $row->prix ?></td>
                   <td class="border-b border-white/40 "><img src='<?php echo $row->image ?>' alt=""class=" ml-2 h-12 w-12 rounded-full"></td>
                    <td class="border-b border-white/40 border-r gap-1 text-center"><button class="h-6 w-8 bg-cara-500  hover:bg-white/40">
                    <img src="../images/eye-line.png" alt=""  class="ml-1"></button> 
                      <button data-id="<?php echo $row->id ?>" class="h-6 w-6 bg-cara-500 hover:bg-white/40 modif" >
                 <img src="../images/edit-2-fill.png" alt=""  class="ml-1"></button>
                <!-- button pour supprimer -->
               <button  data-id="<?php echo $row->id ?>" class="h-6 w-8 bg-cara-500 hover:bg-white/40 admin eraser">
              <img src="../images/eraser-fill.png" alt=""  class="ml-1"></button>
                </td>
              </tr>
              <?php endforeach ?>
           
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── PAGE : CATÉGORIES ─── -->
    <div id="page-categories" class="page hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl italic text-gold">Catégories</h2>
        <button onclick="openModal('modal-categorie')" class="bg-cara-400 text-cara-900 border border-cara-600/40 px-4 py-2 rounded-xl text-sm font-medium hover:bg-amber-200 transition-all">+ Ajouter</button>
      </div>
      <div class="bg-cara-500 border border-cara-600/30 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full">
          <thead>
            <tr>
              <th class="th">#</th>
              <th class="th">Nom</th>
              <th class="th">Photo</th>
              <th class="th">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Lignes PHP ici -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── PAGE : BOUTIQUES ─── -->
    <div id="page-boutiques" class="page hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl italic text-gold">Boutiques</h2>
        <button onclick="openModal('modal-boutique')" class="bg-cara-400 text-cara-900 border border-cara-600/40 px-4 py-2 rounded-xl text-sm font-medium hover:bg-amber-200 transition-all">+ Ajouter</button>
      </div>
      <div class="bg-cara-500 border border-cara-600/30 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-cara-600/20 bg-white/10">
          <input type="text" placeholder="Rechercher…" oninput="filterTable(this, 'table-boutiques')"
            class="w-full max-w-sm bg-cara-200 border border-cara-600/30 rounded-full px-4 py-2 text-sm text-cara-900 placeholder-cara-600 focus:outline-none focus:border-cara-600">
        </div>
        <table class="w-full" id="table-boutiques">
          <thead>
            <tr>
              <th class="th">#</th>
              <th class="th">Boutique</th>
              <th class="th">Vendeur</th>
              <th class="th">Statut</th>
              <th class="th">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Lignes PHP ici -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── PAGE : UTILISATEURS ─── -->
    <div id="page-users" class="page hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl italic text-gold">Utilisateurs</h2>
        <button onclick="openModal('modal-user')" class="bg-cara-400 text-cara-900 border border-cara-600/40 px-4 py-2 rounded-xl text-sm font-medium hover:bg-amber-200 transition-all">+ Ajouter</button>
      </div>
      <div class="bg-cara-500 border border-cara-600/30 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-4 py-3 border-b border-cara-600/20 bg-white/10">
          <input type="text" placeholder="Rechercher…" oninput="filterTable(this, 'table-users')"
            class="w-full max-w-sm bg-cara-200 border border-cara-600/30 rounded-full px-4 py-2 text-sm text-cara-900 placeholder-cara-600 focus:outline-none focus:border-cara-600">
        </div>
        <table class="w-full" id="table-users">
          <thead>
            <tr>
              <th class="th">#</th>
              <th class="th">Prénom Nom</th>
              <th class="th">Email</th>
              <th class="th">Téléphone</th>
              <th class="th">Rôle</th>
              <th class="th">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Lignes PHP ici -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- ─── PAGE : COMMANDES ─── -->
    <div id="page-commandes" class="page hidden">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-display text-xl italic text-gold">Commandes</h2>
      </div>
      <div class="bg-cara-500 border border-cara-600/30 rounded-2xl overflow-hidden shadow-sm">
        <table class="w-full">
          <thead>
            <tr>
              <th class="th">#</th>
              <th class="th">Client</th>
              <th class="th">Total</th>
              <th class="th">Statut</th>
              <th class="th">Date</th>
              <th class="th">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Lignes PHP ici -->
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /contenu -->
</main>

<!-- ════════════════════════════════════════════
     MODALS  (structure réutilisable)
════════════════════════════════════════════ -->

<!-- Modal Produit -->
<div id="modal-produit" class="modal hidden fixed inset-0 bg-cara-900/50 z-[200] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Ajouter un produit</span>
      <button onclick="closeModal('modal-produit')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6">
      <form method="POST" action="ajout_produit.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter">
        <div class="grid grid-cols-2 gap-4">

          <!-- Champs  →  à remplir à ta façon -->
          <div class="col-span-2 field">
            <label>Nom du produit</label>
            <input type="text" name="nom" required placeholder="Ex : Robe Avella Rose" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900">
          </div>
          <div class="field">
            <label>Prix (TND)</label>
            <input type="number" name="prix" step="0.01" required placeholder="0.00" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900">
          </div>
           <div class="field">
           <label>id Boutique</label>
           <input type="number" name="boutique_id" step="0.01" required placeholder="0.00" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900" >
           </div>
              
              
              
              
              
              
          <div class="field">
            <label>Catégorie</label>
            <select name="categorie_id" required>
              <option value=''>— Choisir —</option>
                   <?php
                 $cat=new categories();
                $tab=$cat->categorie();
               foreach($tab as $row): ?>
                 <option value='<?php echo $row->id ?>'><?php echo $row->nom ?></option>
                 <?php endforeach ?>
            </select>
          </div>
          <div class="col-span-2 field">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Description…" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900"></textarea>
          </div>
          <div class="col-span-2 field">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" >
          </div>

        </div>
        <!-- Footer modal -->
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-cara-600/20">
          <button type="button" onclick="closeModal('modal-produit')" class="btn-ghost">Annuler</button>
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- modifier produit -->
 <div id="modal-modif" class=" hidden fixed inset-0 bg-cara-900/50 z-[200] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Modifier un produit</span>
      <button onclick="closeModal('modif-produit')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6">
      <form method="POST" action="modif_produit.php" enctype="multipart/form-data">
        <input type="hidden" name="id" >
        <div class="grid grid-cols-2 gap-4">


          <!-- Champs  →  à remplir à ta façon -->
          <div class="col-span-2 field">
            <label>Nom du produit</label>
            <input type="text" name="nom" required placeholder="Ex : Robe Avella Rose" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900">
          </div>
          <div class="field">
            <label>Prix (TND)</label>
            <input type="number" name="prix" step="0.01" required placeholder="0.00" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900">
          </div>
           <div class="field">
           <label>id Boutique</label>
           <input type="number" name="boutique_id" step="0.01" required placeholder="0.00" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900" >
           </div>
          <div class="field">
            <label>Catégorie</label>
            <select name="categorie_id" required>
              <option value=''>— Choisir —</option>
                   <?php
                 $cat=new categories();
                $tab=$cat->categorie();
               foreach($tab as $row): ?>
                 <option value='<?php echo $row->id ?>'><?php echo $row->nom ?></option>
                 <?php endforeach ?>
            </select>
          </div>
          <div class="col-span-2 field">
            <label>Description</label>
            <textarea name="description" rows="3" placeholder="Description…" class=" border-2 border-cara-700 focus:border-cara-700 focus:outline-none text-cara-900"></textarea>
          </div>
          <div class="col-span-2 field">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" >
          </div>


        </div>
        <!-- Footer modal -->
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-cara-600/20">
          <button type="button" onclick="closeModal('modif-produit')" class="btn-ghost">Annuler</button>
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- Modal Catégorie -->
<div id="modal-categorie" class="modal hidden fixed inset-0 bg-cara-900/50 z-[200] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-lg shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Ajouter une catégorie</span>
      <button onclick="closeModal('modal-categorie')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6">
      <form method="POST" action="actions/categorie.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="ajouter">
        <div class="grid grid-cols-1 gap-4">
          <div class="field"><label>Nom</label><input type="text" name="nom" required placeholder="Ex : Chaussures"></div>
          <div class="field"><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
        </div>
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-cara-600/20">
          <button type="button" onclick="closeModal('modal-categorie')" class="btn-ghost">Annuler</button>
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Boutique -->
<div id="modal-boutique" class="modal hidden fixed inset-0 bg-cara-900/50 z-[200] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-lg shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Ajouter une boutique</span>
      <button onclick="closeModal('modal-boutique')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6">
      <form method="POST" action="actions/boutique.php">
        <input type="hidden" name="action" value="ajouter">
        <div class="grid grid-cols-1 gap-4">
          <div class="field"><label>Nom</label><input type="text" name="nom" required placeholder="Ex : Boutique Sarah"></div>
          <div class="field"><label>User ID propriétaire</label><input type="number" name="user_id" placeholder="ID"></div>
          <div class="field"><label>Description</label><textarea name="description" rows="2"></textarea></div>
          <div class="field"><label>Statut</label>
            <select name="statut"><option value="actif">Actif</option><option value="inactif">Inactif</option></select>
          </div>
        </div>
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-cara-600/20">
          <button type="button" onclick="closeModal('modal-boutique')" class="btn-ghost">Annuler</button>
          <button type="submit" class="btn-primary">Enregistrer</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Utilisateur -->
<div id="modal-user" class="modal hidden fixed inset-0 bg-cara-900/50 z-[200] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-lg shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Ajouter un utilisateur</span>
      <button onclick="closeModal('modal-user')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6">
      <form method="POST" action="actions/user.php">
        <input type="hidden" name="action" value="ajouter">
        <div class="grid grid-cols-2 gap-4">
          <div class="field"><label>Nom</label><input type="text" name="nom" required></div>
          <div class="field"><label>Prénom</label><input type="text" name="prenom" required></div>
          <div class="col-span-2 field"><label>Email</label><input type="email" name="email" required></div>
          <div class="field"><label>Mot de passe</label><input type="password" name="password" required></div>
          <div class="field"><label>Téléphone</label><input type="text" name="telephone"></div>
          <div class="col-span-2 field"><label>Rôle</label>
            <select name="role">
              <option value="client">Client</option>
              <option value="vendeur">Vendeur</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="flex justify-end gap-3 pt-4 mt-4 border-t border-cara-600/20">
          <button type="button" onclick="closeModal('modal-user')" class="btn-ghost">Annuler</button>
          <button type="submit" class="btn-primary">Créer</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- delete-produit -->
 <script>

document.querySelectorAll('.eraser').forEach(btn => {
  btn.addEventListener('click', function() {
     const row = this.closest('tr');
    const id = this.dataset.id; // récupère l'id depuis data-id
    deleteItem('produit',id , row);  // ta fonction déjà existante !
  });
});
document.querySelectorAll('.modif').forEach(btn => {
  btn.addEventListener('click', function () {
    const id = this.dataset.id;

    // Étape 1 : récupère les données et pré-remplit la modal
    fetch('get_produit.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id: id })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const p = data.produit;
          const set = (name, val) => {
            const el = document.querySelector(`#modal-modif [name="${name}"]`);
            if (el) el.value = val;
            else console.warn(`Champ introuvable : ${name}`); // 👈 dira lequel manque
        };

        set('id',           p.id);
        set('nom',          p.nom);
        set('prix',         p.prix);
        set('boutique_id',  p.boutique_id);
        set('categorie_id', p.categorie_id);
        set('description',  p.description);










        openModal('modal-modif');
      }
    });
  });
});













 </script>

<!-- ════════════════════════════════════════════
     STYLES UTILITAIRES (réutilisables partout)
════════════════════════════════════════════ -->
<style>
  /* Cellule th */
  .th {
    @apply px-4 py-3 text-left text-[10px] tracking-[2px] uppercase text-cara-700 font-medium
           border-b border-cara-600/20 bg-white/15;
  }
  /* Cellule td */
  .td       { @apply px-4 py-3 text-sm text-cara-700; }
  .td-bold  { @apply px-4 py-3 text-sm text-cara-900 font-medium; }
  /* Ligne */
  .tr { @apply border-b border-cara-600/20 last:border-0 hover:bg-white/20 transition-colors; }
  /* Boutons */
  .btn-primary { @apply bg-cara-400 text-cara-900 border border-cara-600/40 px-4 py-2 rounded-xl text-sm font-medium hover:bg-amber-200 transition-all cursor-pointer; }
  .btn-ghost   { @apply bg-white/30 text-cara-800 border border-cara-600/20 px-3 py-1.5 rounded-xl text-xs font-medium hover:bg-white/50 transition-all cursor-pointer; }
  .btn-danger  { @apply bg-red-50 text-red-700 border border-red-200 px-3 py-1.5 rounded-xl text-xs font-medium hover:bg-red-100 transition-all cursor-pointer; }
  /* Badges / pills */
  .pill-green  { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800; }
  .pill-amber  { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800; }
  .pill-red    { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800; }
  .pill-blue   { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800; }
  /* Champs de formulaire */
  .field       { @apply flex flex-col gap-1; }
  .field label { @apply text-[10px] tracking-[1.5px] uppercase text-cara-700 font-medium; }
  .field input,
  .field select,
  .field textarea {
    @apply bg-cara-200 border border-cara-600/30 rounded-xl px-3 py-2
           text-cara-900 text-sm placeholder-cara-500
           focus:outline-none focus:border-cara-600;
  }
  /* Nav active */
  .active-nav { @apply bg-white/40 font-medium; }
</style>

<!-- ════════════════════════════════════════════
     JAVASCRIPT (navigation + modals + filtres)
════════════════════════════════════════════ -->
<script>
const PAGE_TITLES = {
  dashboard:  'Tableau de bord',
  produits:   'Produits',
  categories: 'Catégories',
  boutiques:  'Boutiques',
  users:      'Utilisateurs',
  commandes:  'Commandes',
};

function goTo(page, btn = null) {
  document.querySelectorAll('.page').forEach(p => p.classList.add('hidden'));
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active-nav'));
  document.getElementById('page-' + page)?.classList.remove('hidden');
  if (btn) btn.classList.add('active-nav');
  document.getElementById('page-title').textContent = PAGE_TITLES[page] || page;
}

function openModal(id) {
  const m = document.getElementById(id);
  m.classList.remove('hidden');
  m.classList.add('flex');
}

function closeModal(id) {
  const m = document.getElementById(id);
  m.classList.add('hidden');
  m.classList.remove('flex');
}

// Fermer en cliquant sur l'overlay
document.querySelectorAll('.modal').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

// Filtre de recherche générique
function filterTable(input, tableId) {
  const q = input.value.toLowerCase();
  document.getElementById(tableId)?.querySelectorAll('tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// Notification toast
function notify(msg, type = 's') {
  const colors = {
    s: 'bg-green-100 text-green-800 border-green-300',
    d: 'bg-red-100 text-red-800 border-red-300',
    w: 'bg-amber-100 text-amber-800 border-amber-300',
  };
  const el = document.createElement('div');
  el.className = `fixed bottom-6 right-6 z-[999] flex items-center gap-2 px-4 py-3 rounded-xl border text-sm shadow-lg ${colors[type]}`;
  el.textContent = msg;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 3000);
}

// Suppression AJAX générique
function deleteItem(type, id, row = null) {
  // Ouvre la modal
  openModal('modal-confirm');

  // Bouton confirmer — clone pour éviter les doublons d'événements
  const yes = document.getElementById('confirm-yes');
  const yesCopy = yes.cloneNode(true);
  yes.parentNode.replaceChild(yesCopy, yes);

  yesCopy.addEventListener('click', function () {
    closeModal('modal-confirm');

    fetch('delete.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `type=${type}&id=${id}`,
    })
    .then(r => r.json())
    .then(d => {
      notify(d.success ? '✓ Supprimé' : '✗ ' + d.message, d.success ? 's' : 'd');
      if (d.success) {
        if (row) row.remove();
        else setTimeout(() => location.reload(), 700);
      }
    })
    .catch(() => notify('✗ Erreur réseau', 'd'));
  });
}
















// Feedback URL (?success=1 ou ?error=msg)
const qs = new URLSearchParams(location.search);
if (qs.get('success')) notify('✓ Opération réussie');
if (qs.get('error'))   notify('✗ ' + qs.get('error'), 'd');
</script>
<!-- Modal Confirmation Suppression -->
<div id="modal-confirm" class="modal hidden fixed inset-0 bg-cara-900/50 z-[300] items-center justify-center backdrop-blur-sm">
  <div class="modal-box bg-cara-100 border border-cara-600/30 rounded-2xl w-full max-w-sm shadow-2xl mx-4">
    <div class="flex items-center justify-between px-6 py-4 border-b border-cara-600/20 bg-cara-500 rounded-t-2xl">
      <span class="font-display text-lg italic text-cara-900">Confirmation</span>
      <button onclick="closeModal('modal-confirm')" class="text-cara-700 hover:text-cara-900 text-2xl leading-none">&times;</button>
    </div>
    <div class="p-6 text-center">
      <div class="text-4xl mb-3">🗑️</div>
      <p class="text-cara-800 text-sm mb-1">Voulez-vous vraiment supprimer</p>
      <div class="flex justify-center gap-3">
        <button onclick="closeModal('modal-confirm')" class="btn-ghost">Annuler</button>
        <button id="confirm-yes" class="bg-cara-500 text-white border border-cara-500 px-4 py-2 rounded-xl text-sm font-medium hover:bg-cara-200 transition-all cursor-pointer">Supprimer</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>


































































































