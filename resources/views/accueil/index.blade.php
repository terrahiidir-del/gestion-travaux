@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<style>
  /* ===== Palette & tokens ===== */
  :root{
    --y:#f8c304;           /* Jaune ETETI */
    --y-2:#ffd84a;         /* Jaune clair */
    --black:#0e0e0e;
    --white:#ffffff;
    --ink:#222;            /* texte secondaire */
    --muted:#6b6b6b;
    --card:#ffffff;
    --card-border:#ececec;
  }

  /* ===== HERO amélioré ===== */
  .home-hero{
    position:relative; min-height:66vh; display:grid; place-items:center; text-align:center; color:var(--white);
    background:
      linear-gradient(180deg, rgba(0,0,0,.55), rgba(0,0,0,.70)),
      url("{{ asset('images/hero-bg.jpg') }}") center/cover no-repeat;
    overflow:hidden;
    border-bottom:4px solid var(--y);
  }
  /* Légère trame diagonale */
  .home-hero::before{
    content:""; position:absolute; inset:0;
    background-image: repeating-linear-gradient(135deg, rgba(255,255,255,.06) 0, rgba(255,255,255,.06) 2px, transparent 2px, transparent 10px);
    pointer-events:none;
  }
  /* Vague décorative */
  .shape-divider{
    position:absolute; left:0; right:0; bottom:-1px; line-height:0; transform:translateY(1px);
  }
  .brand{ color:var(--y); }
  .chip{
    display:inline-flex; gap:.6rem; align-items:center;
    padding:.55rem .9rem; border-radius:999px; font-weight:600;
    background:rgba(248,195,4,.15); border:1px solid rgba(248,195,4,.5); color:#fff;
  }

  /* ===== Titres de section + soulignement jaune ===== */
  .section-pad{ padding:3.5rem 0; }
  .section-title{
    font-weight:800; color:var(--black); letter-spacing:.2px; position:relative; display:inline-block;
  }
  .section-title::after{
    content:""; position:absolute; left:50%; transform:translateX(-50%);
    bottom:-10px; width:72px; height:4px; border-radius:999px; background:linear-gradient(90deg, var(--y), var(--y-2));
  }
  .section-sub{ max-width:860px; color:var(--muted); }

  /* ===== Cartes services (hover subtil) ===== */
  .card-service{
    background:var(--card); border:1px solid var(--card-border); border-radius:16px; padding:1.15rem;
    transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
    position:relative; overflow:hidden;
  }
  .card-service::before{
    content:""; position:absolute; inset:0; opacity:0; transition:opacity .18s;
    background: radial-gradient(80% 60% at 50% 0%, rgba(248,195,4,.12), transparent 70%);
  }
  .card-service:hover{
    transform:translateY(-4px); box-shadow:0 14px 28px rgba(0,0,0,.08); border-color:#e9e9e9;
  }
  .card-service:hover::before{ opacity:1; }
  .icon-badge{
    width:58px; height:58px; display:grid; place-items:center; border-radius:14px; margin-bottom:.8rem;
    background:rgba(248,195,4,.22); border:1px solid rgba(248,195,4,.45); color:var(--black); font-size:1.25rem;
  }

  /* ===== Liste “À propos” ===== */
  .about-item{
    background:var(--card); border:1px solid var(--card-border); border-radius:14px; padding:1rem;
  }

  /* ===== Partenaires (hover élégant) ===== */
  .partner-card{
    background:var(--card); border:1px solid var(--card-border); border-radius:14px; padding:1.1rem; height:100%;
    transition:transform .18s, box-shadow .18s, border-color .18s;
  }
  .partner-card:hover{ transform:translateY(-3px); box-shadow:0 12px 24px rgba(0,0,0,.07); border-color:#eadb9b; }
  .partner-logo{
    height:76px; display:flex; align-items:center; justify-content:center; margin-bottom:.75rem;
    filter:grayscale(100%); transition:filter .18s, transform .18s;
  }
  .partner-card:hover .partner-logo{ filter:none; transform:scale(1.02); }
  .partner-title{ color:var(--black); }

  /* ===== Bande CTA contrastée ===== */
  .cta-band{
    background:var(--black); color:#fff; border-radius:16px; padding:2rem 1.5rem; border:1px solid rgba(255,255,255,.06);
  }
  .btn-brand{
    --bs-btn-bg: var(--y); --bs-btn-border-color: var(--y); --bs-btn-color: var(--black);
    --bs-btn-hover-bg:#eab600; --bs-btn-hover-border-color:#eab600;
    --bs-btn-active-bg:#d9a900; --bs-btn-active-border-color:#d9a900;
  }
  .btn-outline-light{
    --bs-btn-color:#fff; --bs-btn-border-color:#fff;
    --bs-btn-hover-color:var(--black); --bs-btn-hover-bg:#fff; --bs-btn-hover-border-color:#fff;
  }
</style>

{{-- HERO --}}
<section class="home-hero">
  <div class="container position-relative">
    <span class="chip mb-3">
      <i class="fa-solid fa-bolt"></i> Expertise • Réactivité • Qualité
    </span>
    <h1 class="display-5 fw-bold mb-2">
      Entreprise Travaux Études des <span class="brand">Technologies</span> de l'Information
    </h1>
    <p class="lead mb-4 mx-auto" style="max-width: 820px;">
      Des infrastructures performantes, sécurisées et durables — au service de vos équipes et de vos clients.
    </p>
    <div class="d-flex gap-3 justify-content-center">
      <a href="{{ route('contact') }}" class="btn btn-brand btn-lg fw-semibold px-4">Contactez-nous</a>
      <a href="#services" class="btn btn-outline-light btn-lg px-4">Découvrir nos services</a>
    </div>
  </div>

  {{-- shape divider --}}
  <div class="shape-divider">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 96" preserveAspectRatio="none" style="display:block;width:100%;height:56px">
      <path fill="#fff" d="M0,64L60,74.7C120,85,240,107,360,106.7C480,107,600,85,720,69.3C840,53,960,43,1080,48C1200,53,1320,75,1380,85.3L1440,96L1440,0L1380,0C1320,0,1200,0,1080,0C960,0,840,0,720,0C600,0,480,0,360,0C240,0,120,0,60,0L0,0Z"/>
    </svg>
  </div>
</section>

{{-- À PROPOS --}}
<section class="section-pad">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title h2">À propos de nous</h2>
      <p class="section-sub mx-auto mt-3">
        Basés à Paris, nous accompagnons entreprises et collectivités dans leur transformation numérique :
        réseaux télécom, systèmes d’information, électricité et solutions IoT.
      </p>
    </div>

    <div class="row g-3 justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">
        <div class="vstack gap-3">
          <div class="about-item"><span class="me-2">🔹</span><strong>Réseaux & Télécom</strong> — Conception, déploiement et maintenance d’infrastructures performantes et sécurisées.</div>
          <div class="about-item"><span class="me-2">🔹</span><strong>Informatique & SI</strong> — Cloud, cybersécurité, hébergement et développement logiciel.</div>
          <div class="about-item"><span class="me-2">🔹</span><strong>Électricité</strong> — Installation & maintenance conformes aux normes en vigueur.</div>
          <div class="about-item"><span class="me-2">🔹</span><strong>IoT & Connecté</strong> — Supervision, automatisation et optimisation via objets intelligents.</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SERVICES --}}
<section id="services" class="section-pad bg-light">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title h2">Nos Services</h2>
      <p class="section-sub mx-auto mt-3">Des offres modulaires et évolutives, adaptées à votre contexte.</p>
    </div>

    <div class="row g-3">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-service h-100">
          <div class="icon-badge"><i class="fa-solid fa-network-wired"></i></div>
          <h3 class="h5">Réseau Télécom</h3>
          <p class="text-muted mb-0">Fibre/cuivre, LAN/WAN, Wi-Fi, routage & sécurité.</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-service h-100">
          <div class="icon-badge"><i class="fa-solid fa-desktop"></i></div>
          <h3 class="h5">Informatique (IT)</h3>
          <p class="text-muted mb-0">Cloud & on-prem, M365, supervision, cybersécurité.</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-service h-100">
          <div class="icon-badge"><i class="fa-solid fa-bolt"></i></div>
          <h3 class="h5">Électricité</h3>
          <p class="text-muted mb-0">Courants forts/faibles, tableaux, conformité.</p>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card-service h-100">
          <div class="icon-badge"><i class="fa-solid fa-microchip"></i></div>
          <h3 class="h5">IoT</h3>
          <p class="text-muted mb-0">Capteurs, collecte de données, automatisation.</p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- PARTENAIRES --}}
<section class="section-pad">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="section-title h2">Nos Partenaires</h2>
      <p class="section-sub mx-auto mt-3">Ils nous font confiance pour bâtir et opérer des réseaux robustes.</p>
    </div>

    <div class="row g-3 align-items-stretch">
      <div class="col-12 col-md-4">
        <a class="text-decoration-none" href="https://solutions30.com/?lang=fr" target="_blank" rel="noopener">
          <div class="partner-card text-center">
            <div class="partner-logo"><img src="{{ asset('images/s30.png') }}" alt="Solutions30" style="max-width:180px;max-height:70px"></div>
            <h3 class="h6 fw-bold partner-title mb-1">Solutions30</h3>
            <p class="text-muted small mb-0">Assistance technique & services numériques.</p>
          </div>
        </a>
      </div>

      <div class="col-12 col-md-4">
        <a class="text-decoration-none" href="https://www.circet.fr/clients" target="_blank" rel="noopener">
          <div class="partner-card text-center">
            <div class="partner-logo"><img src="{{ asset('images/circet.webp') }}" alt="Circet" style="max-width:180px;max-height:70px"></div>
            <h3 class="h6 fw-bold partner-title mb-1">CIRCET</h3>
            <p class="text-muted small mb-0">Infrastructures télécom fixes et mobiles.</p>
          </div>
        </a>
      </div>

      <div class="col-12 col-md-4">
        <a class="text-decoration-none" href="https://www.axione.com/" target="_blank" rel="noopener">
          <div class="partner-card text-center">
            <div class="partner-logo"><img src="{{ asset('images/axione.png') }}" alt="Axione" style="max-width:180px;max-height:70px"></div>
            <h3 class="h6 fw-bold partner-title mb-1">AXIONE</h3>
            <p class="text-muted small mb-0">Déploiement & exploitation FTTH/FTTO.</p>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- CTA BAND --}}
<section class="section-pad pt-0">
  <div class="container">
    <div class="cta-band d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
      <div>
        <h3 class="h4 fw-bold mb-1">Un projet à démarrer ou besoin d’un audit ?</h3>
        <p class="mb-0">Parlons de vos enjeux réseaux, IT, électricité ou IoT — on s’occupe du reste.</p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg px-4">Nous contacter</a>
        <a href="{{ route('demandes.create') }}" class="btn btn-brand btn-lg px-4">Demander un devis</a>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')
{{-- aucun JS additionnel requis --}}
@endsection
