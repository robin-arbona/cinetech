<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="{{BASE_PATH}}/js/conf.js" type="text/javascript"></script>
    <script src="https://unpkg.com/vue@next"></script>
    {% block style %}
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" href="{{BASE_PATH}}/style/main.css">
    {% endblock %}
    <title>Cinetech</title>
</head>

<body>

    <form id='conf'>
        <input type="hidden" name="base_path" value={{BASE_PATH}}>
        <input type="hidden" name="http_host" value={{HTTP_HOST}}>
    </form>
    {% include 'header.twig.php' %}

    <div id="app">
        {% block content %}
        <search-modul keywords="{{keywords}}" @change-page="changePage($event)"></search-modul>
        {% endblock %}
    </div>

    {% block script %}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="{{BASE_PATH}}/js/app.js" type="text/javascript" async></script>
    {% endblock %}

</body>

</html>