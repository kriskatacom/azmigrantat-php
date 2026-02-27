<div id="global-image-modal"
    class="fixed inset-0 z-100 flex items-center justify-center p-4 
            bg-black/90 backdrop-blur-sm cursor-zoom-out
            opacity-0 pointer-events-none transition-all duration-300 ease-in-out">

    <button type="button"
        class="absolute top-5 right-5 text-white/70 text-5xl font-light hover:text-white transition-colors z-110">&times;</button>

    <img id="global-modal-img"
        src=""
        class="max-w-full max-h-full rounded-xl shadow-2xl border-4 border-white/10
                transform scale-90 translate-y-4 transition-transform duration-300 ease-out">
</div>

<style>
    #global-image-modal.active {
        opacity: 1 !important;
        pointer-events: auto !important;
    }

    #global-image-modal.active #global-modal-img {
        transform: scale(1) translateY(0) !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('global-image-modal');
        const modalImg = document.getElementById('global-modal-img');
        const closeBtn = modal.querySelector('button');

        function openModal(src) {
            modalImg.src = src;
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
            setTimeout(() => {
                if (!modal.classList.contains('active')) modalImg.src = '';
            }, 300);
        }

        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.lightbox-trigger');
            if (trigger) {
                e.preventDefault();

                const src = trigger.src;

                if (src.includes('no-image.png')) {
                    return;
                }

                openModal(src);
            }
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target === closeBtn) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeModal();
            }
        });
    });
</script>
