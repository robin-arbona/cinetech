{% extends "template.twig.php" %}

{% block style %}
{{ parent() }}
{% endblock %}

{% block content %}
{{ parent() }}
<h1>Favorite </h1>
<p v-if="emptyFavorite">
    <bold>Tips:</bold> In order to use this functionnality, you have to be connected to your user account and to the API (Profil->Connect Api).<br /> Select tvshow or movie and click on the black heart on the right bottom side of the picture. The heart will turn red and the program will be added to your favorite list.
</p>
<h2 v-if="!emptyFavorite">Your favorite movies:</h2>
<favorite-list @change-page="changePage($event)" :fav-list="favMovies"></favorite-list>
<h2 v-if="!emptyFavorite">Your favorite tv show</h2>
<favorite-list @change-page="changePage($event)" :fav-list="favTv"></favorite-list>


{% endblock %}

{% block script %}
{{ parent() }}
{% endblock %}