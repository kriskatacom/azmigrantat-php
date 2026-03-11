<?php
use App\Services\HelperService; ?>

<div class="min-h-screen bg-[#030d16] flex items-center justify-center relative overflow-hidden font-sans">

    <div class="absolute inset-0 opacity-10"
        style="background-image: linear-gradient(#4fd1c5 1px, transparent 1px), linear-gradient(90deg, #4fd1c5 1px, transparent 1px); background-size: 40px 40px;">
    </div>

    <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-primary-light/10 rounded-full blur-[120px] z-0"></div>

    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between relative z-10">

        <div class="mb-12 md:mb-0 md:w-1/2 text-left">
            <h1 class="text-primary-light text-5xl md:text-6xl font-black mb-4 tracking-tight uppercase">
                <?= HelperService::trans('i_the_migrant') ?>
            </h1>
            <p class="text-gray-400 text-lg max-w-md leading-relaxed">
                <?= HelperService::trans('login_back_message') ?>
            </p>
        </div>

        <div class="w-full max-w-md">
            <div class="bg-transparent backdrop-blur-xs border border-white/10 p-10 rounded-2xl shadow-2xl">
                <h2 class="text-white text-2xl font-bold mb-8 text-center uppercase tracking-wide">
                    <?= HelperService::trans('login') ?>
                </h2>

                <form action="/auth/login" method="POST" class="space-y-6">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-xl flex items-center gap-3 animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-red-200 text-sm font-medium">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-2 pl-1"><?= HelperService::trans('email') ?></label>
                        <input type="email" name="email" required
                            placeholder="ivan@example.com"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-primary-light/50 transition-all placeholder:text-gray-600">
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm font-medium mb-2 pl-1"><?= HelperService::trans('password') ?></label>
                        <input type="password" name="password" required
                            placeholder="••••••"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-primary-light/50 transition-all placeholder:text-gray-600">
                    </div>

                    <div class="space-y-4">
                        <button type="submit"
                            class="w-full bg-linear-to-r from-[#143141] to-[#0d212b] hover:from-primary-light hover:to-primary-dark text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-black/20 group overflow-hidden relative">
                            <span class="relative z-10 group-hover:text-black transition-colors uppercase tracking-widest text-sm">Влизане в профила</span>
                        </button>

                        <div class="text-center">
                            <p class="text-gray-500 text-sm">
                                <?= HelperService::trans('dont_account') ?>?
                                <a href="/auth/register" class="text-primary-light hover:text-white transition-colors font-semibold underline underline-offset-4 decoration-primary-light/30">
                                    <?= HelperService::trans('register_here') ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>