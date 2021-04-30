{% extends "template.twig.php" %}

{% block content %}

<section class='container-sm mt-5' style='max-width:300px'>
    <form method='POST' action="{{ BASE_PATH }}/inscription">
        <div class="mb-3">
            <label for='createLogin' class="form-label">Login</label>
            <input type='text' class="form-control" name='createLogin' id='createLogin'>
        </div>
        <div class="mb-3">
            <label for='createEmail' class="form-label">Email</label>
            <input type='email' class="form-control" name='createEmail' id='createEmail'>
        </div>
        <div class="mb-3">
            <label for='createPassword' class="form-label">Password</label>
            <input type='password' class="form-control" name='createPassword' id='createPassword'>
        </div>
        <div class="mb-3">
            <label for='confirmCreatePassword' class="form-label">Password confirmation</label>
            <input type='password' class="form-control" name='confirmCreatePassword' id='confirmCreatePassword'>
        </div>
        <input type='submit' class="btn btn-primary" name='submitInscription' id='submitInscription' value="Sign in">
    </form>
</section>








{% endblock %}