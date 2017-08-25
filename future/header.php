  <!-- The drawer is always open in large screens. The header is always shown,
  even in small screens. -->

 <meta charset="UTF-8">

<header class="mdl-layout__header top-shadow inset-shadow">
    <div class="mdl-layout__header-row">
        <div class="mdl-layout-spacer"></div>
        <div class="content-grid mdl-grid">
            <div class="mdl-cell mdl-cell--6-col mdl-cell--middle no-margin">
                <div class="place-box">

                    <span><a href="#"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a></span>

                    <h3>Mapa social</h3>
                </div>

                <div>
                    <a id="mapInt" class="map-select active" href="#"><span>Interações sociais</span></a>
                    <a id="mapCat" class="map-select" href="#"><span>categorias de informação social</span></a>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--6-col mdl-textfield--align-right mdl-cell--middle">
                <p class="no-margin"><a href="#" id="config-drawer"><i class="fa fa-cog" aria-hidden="true"></i></a></p>
            </div>
        </div>
    </div>
</header>

<div class="mdl-layout__drawer drawer-space top-shadow inset-shadow">
    <!-- <span class="mdl-layout-title">Title</span> -->
    <div class="mdl-grid center-items">
        <div class="mdl-cell mdl-cell--middle  user-img">
            <img src="Imagens/temp/obama.jpg" alt="">
        </div>

        <div class="custom-select" id="profile-status">
            <div class="selected" data-from="profile-status">
                <p>
                    <span class="profile-status-color"></span><span>Ocupado</span>
                    <button id="demo-menu-lower-right"
                        class="mdl-button mdl-js-button mdl-button--icon">
                        <i class="material-icons">more_vert</i>
                    </button>
                </p>
            </div>

            <!-- Right aligned menu below button -->
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
                for="demo-menu-lower-right">
              <li class="mdl-menu__item">Some Action</li>
              <li class="mdl-menu__item">Another Action</li>
              <li disabled class="mdl-menu__item">Disabled Action</li>
              <li class="mdl-menu__item">Yet Another Action</li>
            </ul>
        </div>
    </div>

    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="">Link</a>
      <a class="mdl-navigation__link" href="">Link</a>
      <a class="mdl-navigation__link" href="">Link</a>
      <a class="mdl-navigation__link" href="">Link</a>
    </nav>
</div>

<div class="mdl-layout__drawer drawer-space right-drawer not-visible">
    <span class="mdl-layout-title">Title</span>

    <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="">Link</a>
        <a class="mdl-navigation__link" href="">Link</a>
        <a class="mdl-navigation__link" href="">Link</a>
        <a class="mdl-navigation__link" href="">Link</a>
    </nav>
</div>

<!-- <div class="mdl-layout__obfuscator"></div> -->
