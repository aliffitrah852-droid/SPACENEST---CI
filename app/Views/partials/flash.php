<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-5 rounded-2xl bg-emerald-500/90 text-white text-sm font-semibold px-5 py-3" role="status">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold px-5 py-3" role="alert">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>
