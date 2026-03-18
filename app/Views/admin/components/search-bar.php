<?php
/**
 * @var string $action - Пътят, към който се праща формата
 * @var string $placeholder - Текст в търсачката
 * @var string $slot - Всички допълнителни филтри, селектори или бутони
 */
?>

<form action="<?= $action ?? '' ?>" method="GET" class="flex flex-col md:flex-row items-center gap-4">
    
    <div class="relative grow w-full">
        <input type="text"
               name="search"
               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
               placeholder="<?= $placeholder ?? 'Търсене...' ?>"
               class="form-control">
    </div>

    <?php if (isset($slot)) echo $slot; ?>

    <div class="w-full md:w-auto">
        <button type="submit" class="btn btn-primary w-full">
            Търси
        </button>
    </div>

</form>