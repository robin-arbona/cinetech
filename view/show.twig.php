{% extends "template.twig.php" %}

{% block style %}
{{ parent() }}
{% endblock %}

{% block content %}
{{ parent() }}

<form id="prg-info">
    <input type="hidden" name="type" value={{type}}>
    <input type="hidden" name="id" value={{id}}>
</form>
<prg-overview :info="overview" :fav="fav" @toggle-fav="toggleFav()"></prg-overview>
<details>
    <summary>Reviews</summary>
    <div class="prg-reviews">
        <prg-review v-for="(review, index) in reviews.results"  :review="review" :key="index"></prg-review>
        <prg-review v-for="(review, index) in commentarySql" @add-reply="addReply($event)" :review="review" :key="index"></prg-review>
    </div>
        <section id='writeComment_section'>
            <label for='writeComment'>Ecrire un nouveau commentaire</label>
            <textarea id='writeComment' name='writeComment'></textarea>
            <!-- <input type='hidden' id='comment_id_hidden' name='comment_id_hidden' value='addReply'> -->
            <button type='button' id='submit_comment' @click="addComment()" name="submit_comment">Envoyer</button>
        </section>
</details>
<details>
    <summary>Casting</summary>
    <div class="prg-casting">
        <prg-casting v-for="(actor, index) in credits.cast" v-bind:actor="actor" :key="index"></prg-casting>
    </div>
</details>

<br />
<h2>You're gonna love that</h2>
<carrousel-custom @change-page="changePage($event)" size="small" request="/{{type}}/{{id}}/similar" filter="&sort_by=popularity.desc"></carrousel-custom>
{% endblock %}

{% block script %}
{{ parent() }}
{% endblock %}