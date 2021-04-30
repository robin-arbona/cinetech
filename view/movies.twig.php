{% extends "template.twig.php" %}

{% block style %}
{{ parent() }}
{% endblock %}

{% block content %}
{{ parent() }}
<h1>Hello {{ session.user.login }} ! </h1>
<h2>Good movies!</h2>
<carrousel-custom @change-page="changePage($event)" size="medium" request="/discover/movie" filter="&sort_by=popularity.desc"></carrousel-custom>
<h2>News movies!</h2>
<carrousel-custom @change-page="changePage($event)" size="medium" request="/discover/movie" filter="&sort_by=release_date.desc"></carrousel-custom>
<h2>Old movies!</h2>
<carrousel-custom @change-page="changePage($event)" size="medium" request="/discover/movie" filter="&sort_by=release_date.asc"></carrousel-custom>
{% endblock %}

{% block script %}
{{ parent() }}
{% endblock %}