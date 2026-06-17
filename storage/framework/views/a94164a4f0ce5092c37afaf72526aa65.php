<?php $__env->startSection('title','Hubungi Kami — TicketIn'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .contact-card { transition:all .25s ease; }
  .contact-card:hover { background:#f0f4ff; border-color:#102A71; transform:translateX(4px); }
  .form-input { border:1.5px solid #e5e7eb; border-radius:12px; outline:none; transition:all .2s; color:#1e293b; background:white; width:100%; }
  .form-input:focus { border-color:#F5C400; box-shadow:0 0 0 3px rgba(245,196,0,.15); }
  .form-input::placeholder { color:#9ca3af; }
  .fade-in { opacity:0; transform:translateY(24px); transition:opacity .6s ease, transform .6s ease; }
  .fade-in.visible { opacity:1; transform:none; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero -->
<section class="pt-[72px]">
  <div class="bg-navy-deep relative overflow-hidden">
    <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full bg-navy-mid/40 blur-3xl"></div>
    <div class="absolute bottom-0 left-10 w-48 h-48 rounded-full bg-gold/10 blur-2xl"></div>
    <div class="max-w-7xl mx-auto px-6 py-14 relative">
      <div class="flex items-center gap-2 text-white/50 text-sm mb-3">
        <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-gold transition">Beranda</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gold font-medium">Hubungi Kami</span>
      </div>
      <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2">Hubungi Kami</h1>
      <p class="text-white/60 max-w-xl">Tim TicketIn siap membantu Anda dari pemesanan tiket hingga kerjasama event.</p>
    </div>
  </div>
</section>

<main class="max-w-7xl mx-auto px-6 py-14">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

    <!-- LEFT: Kontak Info -->
    <div class="fade-in visible">
      <h2 class="text-2xl md:text-3xl font-extrabold text-navy-deep mb-3">Kami Senang Mendengar<br>dari <span class="text-gold">Anda</span></h2>
      <p class="text-gray-500 leading-relaxed mb-8">Tim kami siap membantu Anda. Hubungi kami melalui kontak berikut atau kirim pesan langsung melalui form. Kami akan merespons dalam <strong class="text-navy-mid">1×24 jam</strong> di hari kerja.</p>

      <p class="text-xs font-semibold text-navy-mid uppercase tracking-widest mb-3">Email</p>
      <div class="space-y-3 mb-8">
        <?php $__currentLoopData = [['cs@ticketin.com','Customer Service'],['marketing@ticketin.com','Marketing'],['partnership@ticketin.com','Kerjasama & Partnership']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$email,$label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="mailto:<?php echo e($email); ?>" class="contact-card flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 group">
          <div class="w-10 h-10 bg-navy-mid/10 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-navy-mid group-hover:text-white transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-navy-mid group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <p class="text-xs text-gray-400 font-medium"><?php echo e($label); ?></p>
            <p class="text-navy-deep font-semibold text-sm"><?php echo e($email); ?></p>
          </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <p class="text-xs font-semibold text-navy-mid uppercase tracking-widest mb-3">Media Sosial</p>
      <div class="space-y-3">
        <a href="https://instagram.com/ticketin.id" target="_blank" class="contact-card flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 group">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background:linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888)">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
          </div>
          <div><p class="text-xs text-gray-400 font-medium">Instagram</p><p class="text-navy-deep font-semibold text-sm">@ticketin.id</p></div>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
        <a href="https://wa.me/6281234567890" target="_blank" class="contact-card flex items-center gap-4 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 group">
          <div class="w-10 h-10 bg-[#25D366] rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
          </div>
          <div><p class="text-xs text-gray-400 font-medium">WhatsApp</p><p class="text-navy-deep font-semibold text-sm">+62 812 3456 7890</p></div>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300 ml-auto group-hover:text-gold transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>
      </div>

      <div class="mt-8 flex items-start gap-3 bg-gold/10 border border-gold/30 rounded-xl p-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gold mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
          <p class="font-semibold text-navy-deep text-sm">Jam Operasional</p>
          <p class="text-gray-500 text-sm mt-0.5">Senin – Jumat: <strong class="text-navy-mid">08.00 – 17.00 WIB</strong><br>Sabtu: <strong class="text-navy-mid">09.00 – 14.00 WIB</strong> · Minggu: Libur</p>
        </div>
      </div>
    </div>

    <!-- RIGHT: Form -->
    <div class="fade-in visible">
      <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        <div class="mb-7">
          <h3 class="text-xl font-bold text-navy-deep">Kirim Pesan</h3>
          <p class="text-gray-400 text-sm mt-1">Isi form di bawah dan pesan Anda akan dikirim langsung ke WhatsApp kami.</p>
        </div>

        <?php if(session('sent')): ?>
          <div class="bg-green-50 border border-green-300 text-green-700 p-4 rounded-xl mb-5 text-sm"> Pesan berhasil dikirim! Kami akan segera menghubungi Anda.</div>
        <?php endif; ?>

        <div class="space-y-5" id="contact-form">
          <div>
            <label class="block text-sm font-semibold text-navy-deep mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </span>
              <input type="text" id="f-nama" placeholder="Nama lengkap Anda" class="form-input pl-10 pr-4 py-3 text-sm">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-navy-deep mb-1.5">Email <span class="text-red-400">*</span></label>
            <div class="relative">
              <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
              </span>
              <input type="email" id="f-email" placeholder="email@kamu.com" class="form-input pl-10 pr-4 py-3 text-sm">
            </div>
          </div>
          <div>
            <label class="block text-sm font-semibold text-navy-deep mb-1.5">Subjek <span class="text-red-400">*</span></label>
            <select id="f-subjek" class="form-input px-4 py-3 text-sm">
              <option value="">Pilih subjek...</option>
              <option>Pertanyaan Pembelian Tiket</option>
              <option>Masalah Teknis / Akun</option>
              <option>Refund & Pembatalan</option>
              <option>Kerjasama Event (EO)</option>
              <option>Sponsorship & Partnership</option>
              <option>Lainnya</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold text-navy-deep mb-1.5">Pesan <span class="text-red-400">*</span></label>
            <textarea id="f-pesan" rows="5" placeholder="Tulis pesan Anda di sini..." class="form-input px-4 py-3 text-sm resize-none"></textarea>
          </div>
          <button onclick="kirimPesan()" class="w-full bg-gold text-navy-deep font-bold py-3.5 rounded-xl hover:bg-gold-light transition-all hover:shadow-lg hover:shadow-gold/30 text-sm">
            Kirim Pesan via WhatsApp →
          </button>
        </div>
      </div>
    </div>

  </div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  function kirimPesan() {
    const nama   = document.getElementById('f-nama').value.trim();
    const email  = document.getElementById('f-email').value.trim();
    const subjek = document.getElementById('f-subjek').value;
    const pesan  = document.getElementById('f-pesan').value.trim();
    if (!nama||!email||!subjek||!pesan) { alert('Semua field wajib diisi.'); return; }
    const teks = `Halo TicketIn!%0A%0ANama: ${nama}%0AEmail: ${email}%0ASubjek: ${subjek}%0A%0APesan:%0A${encodeURIComponent(pesan)}`;
    window.open(`https://wa.me/6281234567890?text=${teks}`, '_blank');
  }
  const fadeEls = document.querySelectorAll('.fade-in');
  const obs = new IntersectionObserver(entries=>entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('visible'); }));
  fadeEls.forEach(el=>obs.observe(el));
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\ticketin-laravel\resources\views/pages/hubungi.blade.php ENDPATH**/ ?>