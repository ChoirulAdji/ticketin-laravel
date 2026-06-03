<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $__env->yieldContent('title','TicketIn'); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: { extend: {
        colors: { navy: { deep:'#001840', mid:'#102A71' }, gold: { DEFAULT:'#F5C400', light:'#FFDC5F' } },
        fontFamily: { poppins: ['Poppins','sans-serif'] }
      }}
    }
  </script>
  <style>
    * { font-family:'Poppins',sans-serif; }
    body { background:#001840; min-height:100vh; }
    .bg-blob { position:fixed; border-radius:50%; filter:blur(80px); opacity:.18; pointer-events:none; animation:blobFloat 8s ease-in-out infinite; }
    .bg-blob-1 { width:500px;height:500px;background:#F5C400;top:-120px;left:-100px;animation-delay:0s; }
    .bg-blob-2 { width:400px;height:400px;background:#102A71;bottom:-80px;right:-80px;animation-delay:-3s; }
    .bg-blob-3 { width:250px;height:250px;background:#F5C400;bottom:100px;left:40%;animation-delay:-5s;opacity:.08; }
    @keyframes blobFloat { 0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-30px) scale(1.05);} }
    .glass-card { background:rgba(16,42,113,.45);backdrop-filter:blur(24px);border:1px solid rgba(245,196,0,.15);box-shadow:0 32px 80px rgba(0,0,0,.4),inset 0 1px 0 rgba(255,255,255,.06); }
    .input-field { background:rgba(255,255,255,.06);border:1.5px solid rgba(255,255,255,.12);color:white;transition:all .3s ease;outline:none; }
    .input-field::placeholder { color:rgba(255,255,255,.35); }
    .input-field:focus { border-color:#F5C400;background:rgba(245,196,0,.06);box-shadow:0 0 0 4px rgba(245,196,0,.1); }
    .input-group { position:relative; }
    .input-icon { position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.35);transition:color .3s;pointer-events:none; }
    .toggle-pass { position:absolute;right:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.35);cursor:pointer;transition:color .3s;background:none;border:none; }
    .toggle-pass:hover { color:#F5C400; }
    .btn-login { background:linear-gradient(135deg,#F5C400 0%,#FFDC5F 100%);color:#001840;font-weight:700;transition:all .3s ease; }
    .btn-login:hover { transform:translateY(-2px);box-shadow:0 12px 30px rgba(245,196,0,.4); }
    .card-enter { animation:cardEnter .6s cubic-bezier(.34,1.56,.64,1) forwards; }
    @keyframes cardEnter { from{opacity:0;transform:translateY(30px) scale(.97);}to{opacity:1;transform:translateY(0) scale(1);} }
    .nav-link::after { content:'';position:absolute;bottom:-4px;left:0;width:0;height:2px;background:#F5C400;transition:width .3s; }
    .nav-link:hover::after { width:100%; }
    .nav-link { position:relative; }
    .ticket-deco { position:absolute;opacity:.04;pointer-events:none; }
    .success-overlay { position:fixed;inset:0;z-index:100;background:rgba(0,24,64,.96);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;opacity:0;pointer-events:none;transition:opacity .4s; }
    .success-overlay.show { opacity:1;pointer-events:all; }
    .success-circle { width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#F5C400,#FFDC5F);display:flex;align-items:center;justify-content:center;animation:successPulse 1s ease infinite; }
    @keyframes successPulse { 0%{box-shadow:0 0 0 0 rgba(245,196,0,.4);}70%{box-shadow:0 0 0 20px rgba(245,196,0,0);}100%{box-shadow:0 0 0 0 rgba(245,196,0,0);} }
    #mobile-menu { max-height:0;overflow:hidden;transition:max-height .4s; }
    #mobile-menu.open { max-height:300px; }
    <?php echo $__env->yieldPushContent('styles'); ?>
  </style>
  <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="min-h-screen flex flex-col">
  <div class="bg-blob bg-blob-1"></div>
  <div class="bg-blob bg-blob-2"></div>
  <div class="bg-blob bg-blob-3"></div>

  <header class="fixed top-0 left-0 w-full bg-navy-mid text-white shadow-lg z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2 group">
        <div class="w-8 h-8 bg-gold rounded-lg flex items-center justify-center group-hover:bg-gold-light transition-all duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-navy-deep" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        </div>
        <span class="text-xl font-bold tracking-tight">TicketIn</span>
      </a>
      <nav class="hidden md:flex gap-8">
        <a href="<?php echo e(route('dashboard')); ?>" class="nav-link text-white/70 hover:text-gold font-medium text-sm">Beranda</a>
        <a href="<?php echo e(route('hubungi')); ?>" class="nav-link text-white/70 hover:text-gold font-medium text-sm">Hubungi Kami</a>
      </nav>
      <div class="hidden md:block text-white/70 text-sm">
        <?php echo $__env->yieldContent('auth-switch'); ?>
      </div>
      <button id="menu-btn2" class="md:hidden text-white p-1">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
    <div id="mobile-menu" class="bg-navy-mid border-t border-white/10">
      <nav class="flex flex-col px-6 py-4 gap-3">
        <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-gold transition text-sm font-medium">Beranda</a>
        <a href="<?php echo e(route('hubungi')); ?>" class="hover:text-gold transition text-sm font-medium">Hubungi Kami</a>
        <?php echo $__env->yieldContent('auth-switch-mobile'); ?>
      </nav>
    </div>
  </header>

  <main class="flex-1 flex items-center justify-center px-4 pt-28 pb-12 relative">
    <!-- Decorative tickets -->
    <svg class="ticket-deco" style="top:10%;right:5%;width:140px;" viewBox="0 0 24 24" fill="white"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
    <svg class="ticket-deco" style="bottom:15%;left:3%;width:90px;transform:rotate(-20deg);" viewBox="0 0 24 24" fill="white"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>

    <?php echo $__env->yieldContent('content'); ?>
  </main>

  <footer class="text-center py-5 text-white/30 text-xs border-t border-white/5">
    © <?php echo e(date('Y')); ?> TicketIn. All rights reserved.
  </footer>

  <script>
    document.getElementById('menu-btn2')?.addEventListener('click',()=>document.getElementById('mobile-menu').classList.toggle('open'));
  </script>
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Application\Software\XAMPP\htdocs\ticketin-laravel\resources\views/layouts/auth.blade.php ENDPATH**/ ?>