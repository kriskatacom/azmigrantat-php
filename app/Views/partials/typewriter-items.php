<?php

$speed = $speed ?? 100;
$delay = $delay ?? 2000;
?>

<span id="typewriter-output" class="border-r-4 border-blue-600 pr-1 animate-pulse"></span>

<script>
    (function() {
        const words = <?= json_encode($items) ?>;
        const output = document.getElementById('typewriter-output');
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentWord = words[wordIndex];
            
            if (isDeleting) {
                output.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                output.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            let typeSpeed = isDeleting ? <?= $speed ?> / 2 : <?= $speed ?>;

            if (!isDeleting && charIndex === currentWord.length) {
                typeSpeed = <?= $delay ?>;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                typeSpeed = 500;
            }

            setTimeout(type, typeSpeed);
        }

        if (words.length > 0) {
            type();
        }
    })();
</script>

<style>
    @keyframes blink {
        50% { border-color: transparent; }
    }
    #typewriter-output {
        animation: blink 0.7s step-end infinite;
    }
</style>
