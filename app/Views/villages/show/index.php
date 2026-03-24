<?php

use App\Core\View;

$village_name = "Село Златовец";
$mayor = [
    "name" => "Иван Петров",
    "phone" => "0888 123 456",
    "email" => "ivan.petrov@zlatovets.bg",
    "address" => "ул. „Главна“ 1, с. Златовец",
    "bio" => "Иван Петров е кмет на село Златовец от 2019 г. Той е отдаден на развитието на селото и работи за подобряване на живота на местните жители. Има богат опит в местното управление и обича природата и традициите."
];

$gallery = [
    ["title" => "Природата", "img" => "nature.jpg"],
    ["title" => "Църквата", "img" => "church.jpg"],
    ["title" => "Стара къща", "img" => "old_house.jpg"],
    ["title" => "Язовирът", "img" => "lake.jpg"],
];
?>

<div class="max-w-5xl mx-auto bg-white shadow-lg overflow-hidden">

    <div class="w-full h-120 md:h-140 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=80" alt="Village Landscape" class="w-full h-full object-cover">
    </div>

    <section id="village-section">
        <h2 class="text-xl font-bold text-blue-900 p-5 border-b border-gray-200 bg-white">
            Добре дошли в <?php echo $village_name; ?>!
        </h2>

        <div class="px-5 pt-5">
            <div class="border border-gray-200 rounded shadow-sm flex flex-col h-full overflow-hidden bg-white">
                <div class="bg-blue-50 p-2 border-b border-gray-200 font-bold text-blue-900">Описание</div>
                <div class="p-5 border-b border-b-gray-200">
                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Consectetur molestiae non vitae qui sed inventore quos eveniet quae ea est quod fugit cumque, repudiandae assumenda culpa nobis reiciendis laborum eos?</p>
                </div>
                <div id="expandable-content" class="expandable-container">
                    <div class="px-5 mt-5 opacity-0 transition-opacity duration-500" id="inner-content">

                        <div class="flex flex-wrap gap-2 p-1 bg-gray-50 rounded-xl border border-gray-100 mb-4">
                            <?php
                            $tabs = [
                                'geography'  => 'География',
                                'history'    => 'История',
                                'landmarks'  => 'Забележителности',
                                'industry'   => 'Промишленост',
                                'events'     => 'Събития',
                                'investment' => 'Инвестиции',
                                'realestate' => 'Недвижими имоти',
                            ];
                            foreach ($tabs as $key => $label): ?>
                                <button class="tab-btn px-4 py-2 rounded-lg text-xs md:text-sm font-semibold transition-all duration-300 border uppercase tracking-wider"
                                    data-tab="<?= $key ?>">
                                    <?= $label ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div class="relative min-h-35 bg-blue-50/20 rounded-xl p-6 border border-blue-100/50 text-gray-700 shadow-inner overflow-hidden">
                            <div class="tab-pane active fade-in" id="geography">Златовец се намира в планински район с надморска височина 450м. Характеризира се с чист въздух и мек климат.</div>
                            <div class="tab-pane hidden fade-in" id="history">Селото има вековна история, датираща от Второто българско царство. Запазени са множество предания.</div>
                            <div class="tab-pane hidden fade-in" id="landmarks">Културни забележителности включват старата църква от 18-ти век и екопътеката до водопада.</div>
                            <div class="tab-pane hidden fade-in" id="industry">Регионът е известен с производството на био млечни продукти и традиционно месопреработване.</div>
                            <div class="tab-pane hidden fade-in" id="events">Ежегодно се провежда събор на Петровден, както и фолклорен фестивал през месец август.</div>
                            <div class="tab-pane hidden fade-in" id="investment">Работи се по проекти за подобряване на инфраструктурата и привличане на млади семейства.</div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-start p-5">
                    <button id="toggle-btn" class="group flex items-center gap-2 text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors duration-300 focus:outline-none">
                        <span id="btn-text">ПРОЧЕТИ ПОВЕЧЕ ЗА СЕЛОТО...</span>
                        <div class="p-1 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors">
                            <svg id="arrow-icon" class="w-4 h-4 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Плавна анимация на контейнера */
        .expandable-container {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .expandable-container.open {
            max-height: 1000px;
            /* Достатъчно голямо, за да побере съдържанието */
        }

        /* Стилизиране на активен таб */
        .tab-btn.active {
            background-color: white;
            color: #1d4ed8;
            /* blue-700 */
            border-color: #bfdbfe;
            /* blue-200 */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .tab-btn:not(.active) {
            color: #6b7280;
            /* gray-500 */
            border-color: transparent;
        }

        /* Плавна смяна на текста (Fade In) */
        .tab-pane.fade-in {
            animation: fadeIn 0.4s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hidden {
            display: none;
        }
    </style>

    <?php
    // Статични данни за снимките (само URL-и за компонента)
    $static_images = [
        "https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1773332585687-85beb4da71ab?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
        "https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1200&q=80"
    ];
    ?>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-5" x-data="{ openCustomGallery: false }">

        <div class="border border-gray-200 rounded shadow-sm flex flex-col h-full overflow-hidden bg-white">
            <div class="bg-blue-50 p-2 border-b border-gray-200 font-bold text-blue-900">Галерия</div>

            <div class="grid grid-cols-3 grow cursor-pointer group">
                <?php foreach ($static_images as $index => $img):
                    $isLast = ($index === 3);
                    $wrapperClass = $isLast ? 'col-span-3 h-full' : 'col-span-1 h-full';
                ?>
                    <div class="relative overflow-hidden border-[0.5px] border-white/30 <?= $wrapperClass ?>"
                        @click="$dispatch('trigger-gallery')">

                        <img src="<?= $img ?>"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="hidden" @trigger-gallery.window="$el.querySelector('button').click()">
                <?php View::component('gallery', 'partials', [
                    'images'  => $static_images,
                    'variant' => 'button',
                    'title'   => 'Галерия'
                ]); ?>
            </div>
        </div>

        <div class="border border-gray-200 rounded shadow-sm flex flex-col h-full overflow-hidden bg-white">
            <div class="bg-blue-50 p-2 border-b border-gray-200 font-bold text-blue-900">Локация</div>
            <div class="flex flex-col items-center grow">
                <img src="https://upload.wikimedia.org/wikipedia/commons/e/e0/Placeholder_LC_Map.png"
                    class="w-full h-full object-cover opacity-70">
                <div class="p-5 w-full text-center mt-auto">
                    <button class="bg-blue-700 text-white px-4 py-2 rounded text-sm hover:bg-blue-800 transition w-full md:w-auto">
                        Виж в Google Карти
                    </button>
                </div>
            </div>
        </div>

    </div>

    <section class="px-5">
        <div class="border border-gray-200 rounded shadow-sm overflow-hidden">
            <div class="bg-gray-100 p-2 border-b border-gray-200 font-bold text-blue-900 uppercase text-sm italic">Кмет на <?php echo $village_name; ?></div>
            <div class="flex flex-col md:flex-row">
                <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80" alt="Mayor" class="object-cover border border-gray-200">
                <div class="flex-1 p-5">
                    <h3 class="text-xl font-bold text-blue-900"><?php echo $mayor['name']; ?></h3>
                    <p class="text-sm text-gray-600 mb-4 italic leading-tight">Кмет на село Златовец</p>

                    <div class="space-y-2 mb-4 text-sm">
                        <p class="flex items-center gap-2"><span class="font-bold text-green-800">📞 Телефон:</span> <?php echo $mayor['phone']; ?></p>
                        <p class="flex items-center gap-2"><span class="font-bold text-green-800">✉️ Email:</span> <a href="mailto:<?php echo $mayor['email']; ?>" class="text-blue-600"><?php echo $mayor['email']; ?></a></p>
                        <p class="flex items-center gap-2"><span class="font-bold text-green-800">📍 Адрес:</span> <?php echo $mayor['address']; ?></p>
                    </div>

                    <p class="text-sm text-gray-700 leading-relaxed pt-4">
                        <?php echo $mayor['bio']; ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('toggle-btn');
        const container = document.getElementById('expandable-content');
        const innerContent = document.getElementById('inner-content');
        const btnText = document.getElementById('btn-text');
        const arrowIcon = document.getElementById('arrow-icon');
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');

        let isExpanded = false;

        toggleBtn.addEventListener('click', () => {
            isExpanded = !isExpanded;

            if (isExpanded) {
                container.classList.add('open');
                innerContent.classList.remove('opacity-0');
                innerContent.classList.add('opacity-100');
                btnText.textContent = 'ЗАТВОРИ';
                arrowIcon.style.transform = 'rotate(180deg)';
            } else {
                container.classList.remove('open');
                innerContent.classList.remove('opacity-100');
                innerContent.classList.add('opacity-0');
                btnText.textContent = 'ПРОЧЕТИ ПОВЕЧЕ ЗА СЕЛОТО...';
                arrowIcon.style.transform = 'rotate(0deg)';
            }
        });

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-tab');

                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                tabPanes.forEach(pane => {
                    pane.classList.add('hidden');
                    if (pane.id === targetId) {
                        pane.classList.remove('hidden');
                    }
                });
            });
        });

        if (tabBtns.length > 0) tabBtns[0].classList.add('active');
    });
</script>