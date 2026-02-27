<?php 
$uniqueId = 'editor_' . bin2hex(random_bytes(4)); 
?>

<div class="space-y-2 mb-6">
    <label class="text-sm font-semibold text-gray-600 block"><?= $label ?? 'Текст' ?></label>
    
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div id="<?= $uniqueId ?>" style="height: 300px;">
            <?= $value ?? '' ?>
        </div>
    </div>

    <input type="hidden" name="<?= $name ?>" id="input_<?= $uniqueId ?>" value="<?= htmlspecialchars($value ?? '') ?>">
</div>

<script>
(function() {
    const init = () => {
        const container = document.querySelector('#<?= $uniqueId ?>');
        const hiddenInput = document.querySelector('#input_<?= $uniqueId ?>');
        
        if (!container || !hiddenInput) return;

        const quill = new Quill(container, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['link', 'clean']
                ]
            }
        });

        const syncContent = () => {
            const html = quill.root.innerHTML;
            hiddenInput.value = (html === '<p><br></p>') ? '' : html;
        };

        quill.on('text-change', syncContent);

        const form = container.closest('form');
        if (form) {
            form.addEventListener('submit', syncContent);
        }
    };

    if (typeof Quill !== 'undefined') {
        init();
    } else {
        window.addEventListener('load', init);
    }
})();
</script>