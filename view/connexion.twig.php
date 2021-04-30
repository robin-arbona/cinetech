{% extends "template.twig.php" %}

{% block content %}
<section class='container-sm mt-5' style='max-width:300px'>
    <form method='POST' action="{{ BASE_PATH }}/connexion">
        <div class="mb-3">
            <label for='logLogin' class="form-label">Login</label>
            <input type='text' class="form-control" name='logLogin' id='logLogin'>
        </div>
        <div class="mb-3">
            <label for='logPassword' class="form-label">Mot de passe</label>
            <input type='password' class="form-control" name='logPassword' id='logPassword'>
        </div>

        <input type='submit' class="btn btn-primary" name='submitConnection' id='submitConnection' value="Log in">

    </form>
</section>






</form>
{% endblock %}