{% extends "template.twig.php" %}

{% block style %}
{{ parent() }}
{% endblock %}

{% block content %}
{{ parent() }}

<h1>Hello {{ session.user.login }} ! </h1>
<carrousel-custom @change-page="changePage($event)" size="small" request="/discover/movie" filter="&sort_by=popularity.desc"></carrousel-custom>
<carrousel-custom @change-page="changePage($event)" size="medium" request="/discover/movie" filter="&sort_by=release_date.asc"></carrousel-custom>
<carrousel-custom @change-page="changePage($event)" size="big" request="/discover/tv" filter="&sort_by=popularity.desc"></carrousel-custom>
{% endblock %}

{% block script %}
{{ parent() }}
{% endblock %}