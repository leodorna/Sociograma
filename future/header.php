  <!-- The drawer is always open in large screens. The header is always shown,
  even in small screens. -->

 <meta charset="UTF-8">

<div class="content-grid mdl-grid fixed-header">
    <div class="mdl-cell mdl-cell--12-col">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>

            <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable mdl-textfield--floating-label mdl-textfield--align-right">
                <nav class="mdl-navigation grey-nav">
                    <a class="mdl-navigation__link" href="">Acessibilidade</a>
                    <a class="mdl-navigation__link" href="">Notificação</a>
                    <a class="mdl-navigation__link" href="">Sair</a>
                </nav>
            </div>
        </div>
    </div>
</div>

<header class="mdl-layout__header top-shadow inset-shadow">
    <div class="mdl-layout__header-row">
        <div class="mdl-layout-spacer"></div>
        <div class="content-grid mdl-grid">
            <div class="mdl-cell mdl-cell--6-col mdl-cell--middle no-margin">
                <div class="place-box">

                    <span><a href="#"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a></span>

                    <h3>THINGS</h3>
                </div>
                <div id="tabs">
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
    <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--middle mdl-cell--12-col center-items">
            <div class="user-img">
                <img src="Imagens/temp/obama.jpg" alt="">
            </div>

            <div class="custom-select" id="profile-status">
                <div class="selected" data-from="profile-status">
                    <p><span class="profile-status-color"></span><span>Ocupado</span></p>
                </div>
                <div class="options">
                    <ul class='mdl-list'>
                        <li class="mdl-list__item" data-status=""></li>
                        <li class="mdl-list__item" data-status=""></li>
                        <li class="mdl-list__item" data-status=""></li>
                    </ul>
                </div>
            </div>
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
