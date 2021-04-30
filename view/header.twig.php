<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{BASE_PATH}}">Cinetech</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                {% if not session.connected %}
                <li class="nav-item">
                    <a class="nav-link active" href="{{BASE_PATH}}/connexion">Log-in</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{BASE_PATH}}/inscription">Sign-in</a>
                </li>
                {% endif %}

                <li class="nav-item">
                    <a class="nav-link" href="{{BASE_PATH}}/movies">Movies</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{BASE_PATH}}/tv">Tv</a>
                </li>
                {% if session.connected %}
                <li class="nav-item">
                    <a class="nav-link " href="{{BASE_PATH}}/profil">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="{{BASE_PATH}}/favorite" tabindex="-1" aria-disabled="true">Favorite</a>
                </li>
                {% endif %}

            </ul>
            <div id="searchBarPlaceHolder"></div>

        </div>
    </div>
</nav>