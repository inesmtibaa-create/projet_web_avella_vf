<?php
session_start();

$host='127.0.0.1'; $db='avella_base'; $user='root'; $pass='Chahd21*12*2005'; $charset='utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
} catch(PDOException $e){ $db_error=$e->getMessage(); }

$stats=['produits'=>0,'boutiques'=>0,'users'=>0,'commandes'=>0];
if(isset($pdo)) foreach(['produits','boutiques','users','commandes'] as $t) try{$stats[$t]=$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();}catch(PDOException $e){}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Avella — Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --bg:#f4e5d9;--bg2:#eddcce;
  --caramel:#c99f85;--caramel-lt:#dbbfa8;--caramel-dk:#a8825f;
  --brown-dark:#382301;--brown:#5d4133;--brown-mid:#75502a;--brown-lt:#9c8d7d;
  --gold:#6c550f;
  --btn-bg:#ccab89;--btn-hover:#f3d38d;
  --text-h:#4c3e14;--text:#5d4133;--text-lt:#75502a;--text-muted:#9c8d7d;
  --wsoft:rgba(255,255,255,0.45);--whover:rgba(255,255,255,0.70);
  --border:rgba(56,35,1,0.18);--border2:rgba(56,35,1,0.30);
  --sw:260px;--r:16px;--rsm:10px;
}
body{font-family:'DM Sans',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex;font-size:14px;line-height:1.6}

/* SIDEBAR */
.sidebar{width:var(--sw);min-height:100vh;background:var(--caramel);border-right:1px solid var(--border);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:100;box-shadow:4px 0 20px rgba(56,35,1,.10)}
.sb-logo{padding:26px 22px 18px;border-bottom:1px solid var(--border)}
.logo-big{font-family:'Playfair Display',serif;font-size:34px;font-weight:700;color:#ebd5c5;text-shadow:0 2px 8px rgba(56,35,1,.2);display:block;line-height:1}
.logo-sub{font-size:10px;color:var(--brown);letter-spacing:3px;text-transform:uppercase;margin-top:4px;display:block}
.sb-nav{flex:1;padding:10px 0;overflow-y:auto}
.nav-lbl{font-size:10px;letter-spacing:2.5px;text-transform:uppercase;color:var(--brown);padding:14px 20px 4px;display:block;opacity:.65}
.nav-item{display:flex;align-items:center;gap:10px;margin:2px 10px;padding:10px 13px;border-radius:var(--rsm);color:var(--brown-dark);text-decoration:none;font-weight:400;transition:all .2s;cursor:pointer;border:1px solid transparent;background:none;width:calc(100% - 20px);font-size:14px;font-family:'DM Sans',sans-serif}
.nav-item:hover{background:var(--whover);border-color:var(--caramel-dk)}
.nav-item.active{background:var(--wsoft);border-color:var(--caramel-dk);font-weight:500}
.ni{width:17px;height:17px;flex-shrink:0;color:var(--brown-mid)}
.nav-item.active .ni,.nav-item:hover .ni{color:var(--brown-dark)}
.sb-foot{padding:14px 18px;border-top:1px solid var(--border);background:rgba(56,35,1,.05)}
.av-badge{display:flex;align-items:center;gap:10px}
.av-ava{width:36px;height:36px;border-radius:50%;background:var(--btn-bg);border:2px solid var(--brown);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:15px;font-weight:700;color:var(--brown-dark);flex-shrink:0}
.av-name{font-size:13px;font-weight:500;color:var(--brown-dark)}
.av-role{font-size:10px;color:var(--brown-mid);letter-spacing:1px;text-transform:uppercase}

/* MAIN */
.main{margin-left:var(--sw);flex:1;min-height:100vh;display:flex;flex-direction:column}
.topbar{height:62px;background:var(--caramel);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;padding:0 28px;position:sticky;top:0;z-index:50;box-shadow:0 2px 12px rgba(56,35,1,.08)}
.tb-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:500;color:var(--brown-dark);font-style:italic}
.tb-right{display:flex;align-items:center;gap:12px}
.badge-live{display:flex;align-items:center;gap:6px;font-size:12px;color:var(--brown);background:var(--wsoft);padding:5px 12px;border-radius:100px;border:1px solid var(--border)}
.dot-live{width:7px;height:7px;border-radius:50%;background:#5a9e5a;animation:pulse 2s infinite}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.3)}}

.content{flex:1;padding:28px}
.page{display:none}
.page.active{display:block}

/* STAT CARDS */
.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px}
.stat-card{background:var(--caramel);border:1px solid var(--border);border-radius:var(--r);padding:22px 20px;position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;box-shadow:0 2px 12px rgba(56,35,1,.06)}
.stat-card:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(56,35,1,.12)}
.stat-num{font-family:'Playfair Display',serif;font-size:36px;font-weight:700;color:var(--brown-dark);line-height:1;margin-bottom:5px}
.stat-lbl{font-size:10px;color:var(--brown-mid);letter-spacing:2.5px;text-transform:uppercase}
.stat-ico{position:absolute;top:16px;right:16px;opacity:.15;color:var(--brown-dark)}

/* SECTION HEADER */
.sec-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:16px}
.sec-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:500;color:var(--gold);font-style:italic}

/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:var(--rsm);font-size:13px;font-weight:500;font-family:'DM Sans',sans-serif;cursor:pointer;border:1.5px solid transparent;transition:all .2s;text-decoration:none}
.btn-primary{background:var(--btn-bg);color:var(--text-h);border-color:var(--caramel-dk);font-family:'Playfair Display',serif}
.btn-primary:hover{background:var(--btn-hover);border-color:var(--brown)}
.btn-ghost{background:var(--wsoft);color:var(--brown);border-color:var(--border2)}
.btn-ghost:hover{background:var(--whover);color:var(--brown-dark)}
.btn-danger{background:rgba(160,60,40,.1);color:#8b3020;border-color:rgba(160,60,40,.25)}
.btn-danger:hover{background:rgba(160,60,40,.18)}
.btn-sm{padding:5px 12px;font-size:12px}

/* TABLE */
.table-wrap{background:var(--caramel);border:1px solid var(--border);border-radius:var(--r);overflow:hidden;box-shadow:0 2px 12px rgba(56,35,1,.06)}
.tb-toolbar{display:flex;align-items:center;gap:12px;padding:14px 18px;border-bottom:1px solid var(--border);flex-wrap:wrap;background:rgba(255,255,255,.2)}
.search-input{flex:1;min-width:180px;background:var(--bg2);border:1px solid var(--border2);border-radius:100px;padding:8px 16px;color:var(--brown-dark);font-size:13px;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s}
.search-input:focus{border-color:var(--caramel-dk)}
.search-input::placeholder{color:var(--text-muted)}
table{width:100%;border-collapse:collapse}
thead th{background:rgba(255,255,255,.25);padding:11px 18px;text-align:left;font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--brown-mid);font-weight:500;border-bottom:1px solid var(--border)}
tbody tr{border-bottom:1px solid var(--border);transition:background .15s}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--wsoft)}
tbody td{padding:13px 18px;vertical-align:middle;font-size:13px;color:var(--text-lt)}
.tdm{color:var(--brown-dark)!important;font-weight:500}
.pill{display:inline-flex;align-items:center;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:500;letter-spacing:.5px}
.ps{background:rgba(56,120,56,.15);color:#2d6e2d}
.pw{background:rgba(160,120,30,.15);color:#7a5e10}
.pd{background:rgba(160,60,40,.15);color:#8b3020}
.pi{background:rgba(80,100,160,.15);color:#3a4e8b}
.act-c{display:flex;gap:6px}

/* QUICK GRID */
.q-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:28px}
.q-btn{background:var(--caramel);border:1px solid var(--border);border-radius:var(--r);padding:20px 16px;text-align:center;cursor:pointer;transition:all .2s;color:var(--brown-mid);font-size:12px;display:flex;flex-direction:column;align-items:center;gap:10px;font-family:'DM Sans',sans-serif;box-shadow:0 2px 8px rgba(56,35,1,.05)}
.q-btn:hover{background:var(--btn-bg);border-color:var(--caramel-dk);color:var(--brown-dark);transform:translateY(-2px);box-shadow:0 8px 20px rgba(56,35,1,.14)}
.q-lbl{font-family:'Playfair Display',serif;font-size:13px;color:var(--brown-dark)}

/* MODAL */
.mo{display:none;position:fixed;inset:0;background:rgba(56,35,1,.5);z-index:200;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
.mo.open{display:flex}
.modal{background:var(--bg);border:1px solid var(--border2);border-radius:var(--r);width:100%;max-width:520px;max-height:90vh;overflow-y:auto;animation:sUp .25s ease;box-shadow:0 24px 60px rgba(56,35,1,.25)}
@keyframes sUp{from{transform:translateY(16px);opacity:0}to{transform:translateY(0);opacity:1}}
.m-hd{display:flex;align-items:center;justify-content:space-between;padding:20px 22px 14px;border-bottom:1px solid var(--border);background:var(--caramel);border-radius:var(--r) var(--r) 0 0}
.m-title{font-family:'Playfair Display',serif;font-size:18px;font-weight:500;color:var(--brown-dark);font-style:italic}
.m-close{background:none;border:none;color:var(--brown-mid);cursor:pointer;font-size:22px;line-height:1;padding:4px;transition:color .2s}
.m-close:hover{color:var(--brown-dark)}
.m-body{padding:20px 22px}
.m-foot{padding:12px 22px 20px;display:flex;justify-content:flex-end;gap:10px;border-top:1px solid var(--border)}

/* FORM */
.fg{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.fg .full{grid-column:1/-1}
.field{display:flex;flex-direction:column;gap:5px}
.field label{font-size:10px;color:var(--brown-mid);letter-spacing:1.5px;text-transform:uppercase;font-weight:500}
.field input,.field select,.field textarea{background:var(--bg2);border:1.5px solid var(--border);border-radius:var(--rsm);padding:9px 12px;color:var(--brown-dark);font-size:13px;font-family:'DM Sans',sans-serif;outline:none;transition:border-color .2s;resize:vertical}
.field input:focus,.field select:focus,.field textarea:focus{border-color:var(--caramel-dk)}
.field select option{background:var(--bg)}

/* DB ALERT */
.db-alert{background:rgba(160,120,30,.1);border:1px solid rgba(160,120,30,.3);border-radius:var(--rsm);padding:13px 16px;color:var(--gold);font-size:13px;margin-bottom:20px;display:flex;align-items:flex-start;gap:10px}

/* EMPTY */
.empty{text-align:center;padding:48px 20px;color:var(--text-muted)}
.empty-ico{font-size:36px;margin-bottom:10px;opacity:.5}
.empty strong{color:var(--brown-mid)}
.empty p{font-size:12px;margin-top:4px}

/* NOTIF */
.notif{position:fixed;bottom:24px;right:24px;background:var(--caramel);border:1px solid var(--border2);border-radius:var(--rsm);padding:12px 18px;font-size:13px;z-index:500;display:flex;align-items:center;gap:10px;min-width:220px;animation:sRight .3s ease;box-shadow:0 8px 28px rgba(56,35,1,.2);color:var(--brown-dark)}
@keyframes sRight{from{transform:translateX(40px);opacity:0}to{transform:translateX(0);opacity:1}}
.ndot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:transparent}
::-webkit-scrollbar-thumb{background:var(--caramel-dk);border-radius:8px}
</style>
</head>
<body>

<aside class="sidebar">
  <div class="sb-logo">
    <span class="logo-big">AVELLA</span>
    <span class="logo-sub">Administration</span>
  </div>
  <nav class="sb-nav">
    <span class="nav-lbl">Général</span>
    <button class="nav-item active" onclick="nav('dashboard',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zm0 9.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zm9.75-9.75A2.25 2.25 0 0115.75 3.75H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zm0 9.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
      Dashboard
    </button>
    <span class="nav-lbl">Catalogue</span>
    <button class="nav-item" onclick="nav('produits',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
      Produits
    </button>
    <button class="nav-item" onclick="nav('categories',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
      Catégories
    </button>
    <button class="nav-item" onclick="nav('boutiques',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
      Boutiques
    </button>
    <span class="nav-lbl">Membres</span>
    <button class="nav-item" onclick="nav('users',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
      Utilisateurs
    </button>
    <button class="nav-item" onclick="nav('commandes',this)">
      <svg class="ni" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      Commandes
    </button>
  </nav>
  <div class="sb-foot">
    <div class="av-badge">
      <div class="av-ava">A</div>
      <div><div class="av-name">Admin Avella</div><div class="av-role">Super Admin</div></div>
    </div>
  </div>
</aside>

<main class="main">
  <header class="topbar">
    <span class="tb-title" id="page-title">Tableau de bord</span>
    <div class="tb-right">
      <div class="badge-live"><div class="dot-live"></div>Avella · Live</div>
    </div>
  </header>

  <div class="content">
    <?php if(isset($db_error)): ?>
    <div class="db-alert">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div><strong>BDD non connectée.</strong> Configure <code>$host/$db/$user/$pass</code> en haut du fichier. Erreur : <em><?=htmlspecialchars($db_error)?></em></div>
    </div>
    <?php endif; ?>

    <!-- DASHBOARD -->
    <div class="page active" id="page-dashboard">
      <div class="stats-grid">
        <?php
        $sc=[['produits','📦','Produits'],['boutiques','🏪','Boutiques'],['users','👥','Utilisateurs'],['commandes','📋','Commandes']];
        foreach($sc as[$k,$ico,$lbl]) echo "
        <div class='stat-card'>
          <div class='stat-ico' style='font-size:44px'>$ico</div>
          <div class='stat-num'>{$stats[$k]}</div>
          <div class='stat-lbl'>$lbl</div>
        </div>";
        ?>
      </div>
      <div class="sec-hd"><h2 class="sec-title">Actions rapides</h2></div>
      <div class="q-grid">
        <button class="q-btn" onclick="openM('modal-produit','Ajouter un produit')">
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          <span class="q-lbl">Nouveau produit</span>
        </button>
        <button class="q-btn" onclick="openM('modal-boutique','Ajouter une boutique')">
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/></svg>
          <span class="q-lbl">Nouvelle boutique</span>
        </button>
        <button class="q-btn" onclick="openM('modal-user','Ajouter un utilisateur')">
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          <span class="q-lbl">Nouvel utilisateur</span>
        </button>
        <button class="q-btn" onclick="nav('commandes',null)">
          <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          <span class="q-lbl">Voir commandes</span>
        </button>
      </div>
    </div>

    <!-- PRODUITS -->
    <div class="page" id="page-produits">
      <div class="sec-hd"><h2 class="sec-title">Produits</h2>
        <button class="btn btn-primary" onclick="openM('modal-produit','Ajouter un produit')">+ Ajouter un produit</button>
      </div>
      <div class="table-wrap">
        <div class="tb-toolbar"><input class="search-input" type="text" placeholder="Rechercher…" oninput="filterT(this,'tp')"></div>
        <table id="tp"><thead><tr><th>#</th><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody><?php
        if(isset($pdo)){try{$rows=$pdo->query("SELECT p.*,c.nom as cat FROM produits p LEFT JOIN categories c ON p.categorie_id=c.id ORDER BY p.created_at DESC LIMIT 50")->fetchAll();
        if($rows)foreach($rows as $r){$s=$r['stock']>0?"<span class='pill ps'>En stock</span>":"<span class='pill pd'>Épuisé</span>";
        echo "<tr><td class='tdm'>#{$r['id']}</td><td class='tdm'>".htmlspecialchars($r['nom'])."</td><td>".htmlspecialchars($r['cat']??'—')."</td><td>".number_format($r['prix'],2)." TND</td><td>{$r['stock']}</td><td>$s</td><td><div class='act-c'><button class='btn btn-ghost btn-sm' onclick=\"editP({$r['id']},'".addslashes($r['nom'])."',{$r['prix']},{$r['stock']})\">Modifier</button><button class='btn btn-danger btn-sm' onclick=\"delC('produit',{$r['id']})\">Supprimer</button></div></td></tr>";}
        else echo "<tr><td colspan='7'><div class='empty'><div class='empty-ico'>📦</div><strong>Aucun produit</strong><p>Ajoutez votre premier produit.</p></div></td></tr>";}
        catch(PDOException $e){echo "<tr><td colspan='7'><div class='empty'><div class='empty-ico'>⚠️</div><strong>Table non créée</strong><p>Importez avella_db.sql dans phpMyAdmin.</p></div></td></tr>";}}
        else echo "<tr><td colspan='7'><div class='empty'><div class='empty-ico'>🔌</div><strong>BDD non connectée</strong></div></td></tr>";
        ?></tbody></table>
      </div>
    </div>

    <!-- CATEGORIES -->
    <div class="page" id="page-categories">
      <div class="sec-hd"><h2 class="sec-title">Catégories</h2>
        <button class="btn btn-primary" onclick="openM('modal-categorie','Ajouter une catégorie')">+ Ajouter</button>
      </div>
      <div class="table-wrap"><table><thead><tr><th>#</th><th>Nom</th><th>Slug</th><th>Actions</th></tr></thead><tbody>
      <?php $cats=[['Vêtements','vetements'],['Chaussures','shoes'],['Accessoires','accesoires'],['Bijoux Pearls','pearls'],['Soins de peau','skin'],['Grace','grace'],['Home Decor','home-decor'],['Produits Alimentaires','alimentaires'],['Beauté & Soins','beaute-soins'],['Fitness','fitness'],['High Tech','high-tech'],['Livres & BD','livres'],['Artisanat','artisanat']];
      foreach($cats as $i=>[$n,$s]) echo "<tr><td class='tdm'>".($i+1)."</td><td class='tdm'>$n</td><td>$s</td><td><div class='act-c'><button class='btn btn-ghost btn-sm'>Modifier</button><button class='btn btn-danger btn-sm'>Supprimer</button></div></td></tr>";
      ?></tbody></table></div>
    </div>

    <!-- BOUTIQUES -->
    <div class="page" id="page-boutiques">
      <div class="sec-hd"><h2 class="sec-title">Boutiques</h2>
        <button class="btn btn-primary" onclick="openM('modal-boutique','Ajouter une boutique')">+ Ajouter</button>
      </div>
      <div class="table-wrap">
        <div class="tb-toolbar"><input class="search-input" type="text" placeholder="Rechercher…" oninput="filterT(this,'tb')"></div>
        <table id="tb"><thead><tr><th>#</th><th>Boutique</th><th>Vendeur</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody><?php
        if(isset($pdo)){try{$rows=$pdo->query("SELECT b.*,u.nom as v FROM boutiques b LEFT JOIN users u ON b.user_id=u.id ORDER BY b.created_at DESC LIMIT 50")->fetchAll();
        if($rows)foreach($rows as $r){$s=$r['statut']==='actif'?"<span class='pill ps'>Actif</span>":"<span class='pill pw'>Inactif</span>";
        echo "<tr><td class='tdm'>#{$r['id']}</td><td class='tdm'>".htmlspecialchars($r['nom'])."</td><td>".htmlspecialchars($r['v']??'—')."</td><td>$s</td><td><div class='act-c'><button class='btn btn-ghost btn-sm'>Modifier</button><button class='btn btn-danger btn-sm' onclick=\"delC('boutique',{$r['id']})\">Supprimer</button></div></td></tr>";}
        else echo "<tr><td colspan='5'><div class='empty'><div class='empty-ico'>🏪</div><strong>Aucune boutique</strong></div></td></tr>";}
        catch(PDOException $e){echo "<tr><td colspan='5'><div class='empty'><div class='empty-ico'>⚠️</div><strong>Table non créée</strong></div></td></tr>";}}
        else echo "<tr><td colspan='5'><div class='empty'><div class='empty-ico'>🔌</div><strong>BDD non connectée</strong></div></td></tr>";
        ?></tbody></table>
      </div>
    </div>

    <!-- USERS -->
    <div class="page" id="page-users">
      <div class="sec-hd"><h2 class="sec-title">Utilisateurs</h2>
        <button class="btn btn-primary" onclick="openM('modal-user','Ajouter un utilisateur')">+ Ajouter</button>
      </div>
      <div class="table-wrap">
        <div class="tb-toolbar"><input class="search-input" type="text" placeholder="Rechercher…" oninput="filterT(this,'tu')"></div>
        <table id="tu"><thead><tr><th>#</th><th>Nom</th><th>Email</th><th>Rôle</th><!--<th>Inscrit le</th>--><th>Actions</th></tr></thead>
        <tbody><?php
        if(isset($pdo)){try{$rows=$pdo->query("SELECT * FROM users ")->fetchAll();
        if($rows)foreach($rows as $r){$role=$r['role']==='vendeur'?"<span class='pill pi'>Vendeur</span>":($r['role']==='admin'?"<span class='pill pw'>Admin</span>":"<span class='pill ps'>Acheteur</span>");
        echo "<tr><td class='tdm'>#{$r['id']}</td><td class='tdm'>".htmlspecialchars($r['name'])."</td><td>".htmlspecialchars($r['email'])."</td><td>$role</td><td><div class='act-c'><button class='btn btn-ghost btn-sm'>Modifier</button><button class='btn btn-danger btn-sm' onclick=\"delC('user',{$r['id']})\">Supprimer</button></div></td></tr>";}
        else echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>👥</div><strong>Aucun utilisateur</strong></div></td></tr>";}
        catch(PDOException $e){echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>⚠️</div><strong>Table non créée</strong></div></td></tr>";}}
        else echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>🔌</div><strong>BDD non connectée</strong></div></td></tr>";
        ?></tbody></table>
      </div>
    </div>

    <!-- COMMANDES -->
    <div class="page" id="page-commandes">
      <div class="sec-hd"><h2 class="sec-title">Commandes</h2></div>
      <div class="table-wrap"><table><thead><tr><th>#</th><th>Client</th><th>Montant</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody><?php
      if(isset($pdo)){try{$rows=$pdo->query("SELECT c.*,u.nom as cl FROM commandes c LEFT JOIN users u ON c.user_id=u.id ORDER BY c.created_at DESC LIMIT 50")->fetchAll();
      $sm=['en_attente'=>['pw','En attente'],'confirmee'=>['pi','Confirmée'],'expediee'=>['pi','Expédiée'],'livree'=>['ps','Livrée'],'annulee'=>['pd','Annulée']];
      if($rows)foreach($rows as $r){[$pc,$lbl]=$sm[$r['statut']]??['pw',$r['statut']];$s="<span class='pill $pc'>$lbl</span>";
      echo "<tr><td class='tdm'>#{$r['id']}</td><td class='tdm'>".htmlspecialchars($r['cl']??'—')."</td><td>".number_format($r['total'],2)." TND</td><td>$s</td><td>".date('d/m/Y',strtotime($r['created_at']))."</td><td><div class='act-c'><button class='btn btn-ghost btn-sm'>Détails</button><button class='btn btn-danger btn-sm'>Annuler</button></div></td></tr>";}
      else echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>📋</div><strong>Aucune commande</strong></div></td></tr>";}
      catch(PDOException $e){echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>⚠️</div><strong>Table non créée</strong></div></td></tr>";}}
      else echo "<tr><td colspan='6'><div class='empty'><div class='empty-ico'>🔌</div><strong>BDD non connectée</strong></div></td></tr>";
      ?></tbody></table></div>
    </div>
  </div>
</main>

<!-- MODAL PRODUIT -->
<div class="mo" id="modal-produit">
  <div class="modal">
    <div class="m-hd"><span class="m-title" id="mp-title">Ajouter un produit</span><button class="m-close" onclick="closeM('modal-produit')">×</button></div>
    <form method="POST" action="actions/produit.php" enctype="multipart/form-data">
      <input type="hidden" name="action" id="p-action" value="ajouter"><input type="hidden" name="id" id="p-id">
      <div class="m-body"><div class="fg">
        <div class="field full"><label>Nom du produit</label><input type="text" name="nom" id="p-nom" placeholder="Ex : Sneakers Avella Blanc" required></div>
        <div class="field"><label>Prix (TND)</label><input type="number" name="prix" id="p-prix" step="0.01" placeholder="0.00" required></div>
        <div class="field"><label>Stock</label><input type="number" name="stock" id="p-stock" placeholder="0" required></div>
        <div class="field full"><label>Catégorie</label><select name="categorie_id"><option value="">— Choisir —</option><option value="1">Vêtements</option><option value="2">Chaussures</option><option value="3">Accessoires</option><option value="4">Bijoux Pearls</option><option value="5">Soins de peau</option><option value="6">Grace</option></select></div>
        <div class="field full"><label>Description</label><textarea name="description" rows="3" placeholder="Description…"></textarea></div>
        <div class="field full"><label>Image</label><input type="file" name="image" accept="image/*"></div>
      </div></div>
      <div class="m-foot"><button type="button" class="btn btn-ghost" onclick="closeM('modal-produit')">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
    </form>
  </div>
</div>

<!-- MODAL BOUTIQUE -->
<div class="mo" id="modal-boutique">
  <div class="modal">
    <div class="m-hd"><span class="m-title">Ajouter une boutique</span><button class="m-close" onclick="closeM('modal-boutique')">×</button></div>
    <form method="POST" action="actions/boutique.php">
      <input type="hidden" name="action" value="ajouter">
      <div class="m-body"><div class="fg">
        <div class="field full"><label>Nom de la boutique</label><input type="text" name="nom" placeholder="Ex : Sneakers by Sarah" required></div>
        <div class="field full"><label>Propriétaire (User ID)</label><input type="number" name="user_id" placeholder="ID utilisateur"></div>
        <div class="field full"><label>Description</label><textarea name="description" rows="3"></textarea></div>
        <div class="field full"><label>Statut</label><select name="statut"><option value="actif">Actif</option><option value="inactif">Inactif</option></select></div>
      </div></div>
      <div class="m-foot"><button type="button" class="btn btn-ghost" onclick="closeM('modal-boutique')">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
    </form>
  </div>
</div>

<!-- MODAL USER -->
<div class="mo" id="modal-user">
  <div class="modal">
    <div class="m-hd"><span class="m-title">Ajouter un utilisateur</span><button class="m-close" onclick="closeM('modal-user')">×</button></div>
    <form method="POST" action="actions/user.php">
      <input type="hidden" name="action" value="ajouter">
      <div class="m-body"><div class="fg">
        <div class="field full"><label>Nom complet</label><input type="text" name="nom" placeholder="Prénom Nom" required></div>
        <div class="field full"><label>Email</label><input type="email" name="email" placeholder="email@exemple.com" required></div>
        <div class="field"><label>Mot de passe</label><input type="password" name="password" placeholder="••••••••" required></div>
        <div class="field"><label>Rôle</label><select name="role"><option value="acheteur">Acheteur</option><option value="vendeur">Vendeur</option><option value="admin">Admin</option></select></div>
      </div></div>
      <div class="m-foot"><button type="button" class="btn btn-ghost" onclick="closeM('modal-user')">Annuler</button><button type="submit" class="btn btn-primary">Créer</button></div>
    </form>
  </div>
</div>

<!-- MODAL CATEGORIE -->
<div class="mo" id="modal-categorie">
  <div class="modal">
    <div class="m-hd"><span class="m-title">Ajouter une catégorie</span><button class="m-close" onclick="closeM('modal-categorie')">×</button></div>
    <form method="POST" action="actions/categorie.php">
      <input type="hidden" name="action" value="ajouter">
      <div class="m-body"><div class="fg">
        <div class="field full"><label>Nom</label><input type="text" name="nom" placeholder="Ex : Chaussures" required></div>
        <div class="field full"><label>Slug</label><input type="text" name="slug" placeholder="Ex : chaussures"></div>
        <div class="field full"><label>Description</label><textarea name="description" rows="2"></textarea></div>
      </div></div>
      <div class="m-foot"><button type="button" class="btn btn-ghost" onclick="closeM('modal-categorie')">Annuler</button><button type="submit" class="btn btn-primary">Enregistrer</button></div>
    </form>
  </div>
</div>

<script>
const T={dashboard:'Tableau de bord',produits:'Produits',categories:'Catégories',boutiques:'Boutiques',users:'Utilisateurs',commandes:'Commandes'};

function nav(p,btn){
  document.querySelectorAll('.page').forEach(x=>x.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(x=>x.classList.remove('active'));
  const el=document.getElementById('page-'+p);if(el)el.classList.add('active');
  if(btn)btn.classList.add('active');
  document.getElementById('page-title').textContent=T[p]||p;
}

function openM(id,title){
  const m=document.getElementById(id);
  if(title){const t=m.querySelector('.m-title');if(t)t.textContent=title;}
  m.classList.add('open');
}

function closeM(id){document.getElementById(id).classList.remove('open')}

document.querySelectorAll('.mo').forEach(o=>{
  o.addEventListener('click',e=>{if(e.target===o)o.classList.remove('open')});
});

function editP(id,nom,prix,stock){
  document.getElementById('p-action').value='modifier';
  document.getElementById('p-id').value=id;
  document.getElementById('p-nom').value=nom;
  document.getElementById('p-prix').value=prix;
  document.getElementById('p-stock').value=stock;
  openM('modal-produit','Modifier le produit');
}

function delC(type,id){
  if(!confirm('Supprimer cet élément ? Action irréversible.'))return;
  fetch('actions/delete.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:`type=${type}&id=${id}`})
  .then(r=>r.json()).then(d=>{
    notif(d.success?'✓ Supprimé avec succès':'✗ '+d.message,d.success?'s':'d');
    if(d.success)setTimeout(()=>location.reload(),800);
  }).catch(()=>notif('✗ Erreur réseau','d'));
}

function filterT(inp,tid){
  const q=inp.value.toLowerCase();
  document.getElementById(tid).querySelectorAll('tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'});
}

function notif(msg,type='s'){
  const c={s:'#3a7a3a',d:'#8b3020',w:'#7a5e10'};
  const d=document.createElement('div');d.className='notif';
  d.innerHTML=`<div class="ndot" style="background:${c[type]}"></div><span>${msg}</span>`;
  document.body.appendChild(d);setTimeout(()=>d.remove(),3000);
}

const p=new URLSearchParams(location.search);
if(p.get('success'))notif('✓ Opération réussie');
if(p.get('error'))notif('✗ '+p.get('error'),'d');
</script>
</body>
</html>
