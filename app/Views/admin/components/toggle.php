<?php
/**
 * @var string $name  - Името на input полето (напр. is_active)
 * @var string $label - Текстът до бутона
 * @var bool   $value - Текущото състояние (true/false)
 * @var string $id    - Уникално ID (ако имаш няколко на една страница)
 */
$id = $id ?? 'toggle-' . uniqid();
$name = $name ?? 'is_active';
$label = $label ?? 'Активен статус';
$checked = ($value ?? true) ? 'checked' : '';
?>

<div class="flex items-center gap-3 py-2">
    <label for="<?= $id ?>" class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" 
               name="<?= $name ?>" 
               id="<?= $id ?>" 
               value="1" 
               class="sr-only peer" 
               <?= $checked ?>>
        
        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                    peer-checked:after:translate-x-full peer-checked:after:border-white 
                    after:content-[''] after:absolute after:top-0.5 after:left-0.5 
                    after:bg-white after:border-gray-300 after:border after:rounded-full 
                    after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-dark">
        </div>
        
        <span class="ml-3 text-sm font-medium text-gray-600 select-none">
            <?= $label ?>
        </span>
    </label>
</div>