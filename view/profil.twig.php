{% extends "template.twig.php" %}

{% block content %}
<section class='container-sm mt-5' style='max-width:300px'>

    <form method='POST' action="{{ BASE_PATH }}/profil">
        <div class="mb-3">
            <img src="{{ session.user.image }}" width='150' alt="user pic">
            <input type='text' name='profilImage' id='profilImage' value='{{ session.user.image }}'>
        </div>

        <div class="mb-3">
            <label class="form-label" for='profilLogin'>Login</label>
            <input class="form-control" type='text' name='profilLogin' id='profilLogin' value='{{ session.user.login }}'>
        </div>

        <div class="mb-3">
            <label class="form-label" for='profilEmail'>Email</label>
            <input class="form-control" type='text' name='profilEmail' id='profilEmail' value='{{ session.user.email }}'>
        </div>

        <div class="mb-3">
            <label class="form-label" for='profilPassword'>Mot de passe</label>
            <input class="form-control" type='password' name='profilPassword' id='profilPassword'>
        </div>

        <div class="mb-3">
            <label class="form-label" for='confirmProfilPassword'>Confirmer le mot de passe</label>
            <input class="form-control" type='password' name='confirmProfilPassword' id='profilPassword'>
        </div>

        <input type='submit' class="btn btn-warning" name='submitProfil' id='submitProfil' value="Update profil">

    </form>
    <br />
    <api-connect @api-connect="apiConnect()"></api-connect>
    <a href="{{ BASE_PATH }}/log-out" class="btn btn-danger">Log-out</a>
</section>
{% endblock %}