<div class="row">
    <div class="col-lg-12">
        <div class="link-container">
            <a href="/songs" class="btn btn_round btn_default <?= Request::is('songs') ? 'active': '' ?>">Music</a>            
            <a href="/sheet_musics" class="btn btn_round btn_default <?= Request::is('sheet_musics') ? 'active': '' ?>">Sheet Music</a>
            <a href="/instrumentals" class="btn btn_round btn_default <?= Request::is('instrumentals') ? 'active': '' ?>">Sing Along</a>
            <a href="/podcasts" class="btn btn_round btn_default <?= Request::is('podcasts') ? 'active': '' ?>">Podcasts</a>
            <a href="/videos" class="btn btn_round btn_default <?= Request::is('videos') ? 'active': '' ?>">Videos</a>
        </div>
    </div>
</div>
