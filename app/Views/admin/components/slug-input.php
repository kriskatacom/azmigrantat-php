<?php
$name = $name ?? 'slug';
$value = $value ?? '';
$source = $source ?? 'name';
$label = $label ?? 'URL Slug (автоматично)';
?>

<div class="space-y-2 slug-container">
    <label class="text-sm font-semibold text-gray-600"><?= $label ?></label>
    <div class="relative group">
        <input type="text"
            name="<?= $name ?>"
            id="slug-<?= $name ?>"
            value="<?= htmlspecialchars($value) ?>"
            data-source="<?= $source ?>"
            required
            class="slug-field w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 outline-none italic text-gray-500 focus:bg-white focus:ring-2 focus:ring-primary-light transition-all">

        <div class="absolute right-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </svg>
        </div>
    </div>
    <p class="text-[10px] text-gray-400 italic">Генерира се автоматично от името на латиница.</p>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cyrillicToLatin = (text) => {
        const map = {
            'а':'a', 'б':'b', 'в':'v', 'г':'g', 'д':'d', 'е':'e', 'ж':'zh', 'з':'z', 'и':'i', 'й':'y', 
            'к':'k', 'л':'l', 'м':'m', 'н':'n', 'о':'o', 'п':'p', 'р':'r', 'с':'s', 'т':'t', 'у':'u', 
            'ф':'f', 'х':'h', 'ц':'ts', 'ч':'ch', 'ш':'sh', 'щ':'sht', 'ъ':'a', 'ь':'y', 'ю':'yu', 'я':'ya',
            ' ': '-', '.': '', ',': '', '"': '', "'": '', '!': '', '?': ''
        };
        return text.toLowerCase().split('').map(char => map[char] !== undefined ? map[char] : char).join('');
    };

    const slugFields = document.querySelectorAll('.slug-field');

    slugFields.forEach(field => {
        const sourceId = field.getAttribute('data-source');
        const sourceInput = document.getElementById(sourceId);

        if (sourceInput) {
            sourceInput.addEventListener('input', function() {
                const slug = cyrillicToLatin(this.value)
                    .replace(/[^\w\s-]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                field.value = slug;
            });
        }
    });
});
</script>
