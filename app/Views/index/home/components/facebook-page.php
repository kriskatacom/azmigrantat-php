<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/bg_BG/sdk.js#xfbml=1&version=v19.0"></script>

<?php $facebook_page_url = 'https://www.facebook.com/profile.php?id=61551560430198' ?>

<div class="my-5 md:my-10 md:text-center">
    <div class="fb-page w-full"
        data-href="<?php echo htmlspecialchars($facebook_page_url); ?>"
        data-tabs="timeline"
        data-width="500"
        data-height="700"
        data-small-header="false"
        data-adapt-container-width="true"
        data-hide-cover="false"
        data-show-facepile="true">
        <blockquote cite="<?php echo htmlspecialchars($facebook_page_url); ?>" class="fb-xfbml-parse-ignore">
            <a href="<?php echo htmlspecialchars($facebook_page_url); ?>">Facebook</a>
        </blockquote>
    </div>
</div>