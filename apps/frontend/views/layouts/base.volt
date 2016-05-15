<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{% block title %}{% endblock %}</title>
        {% block stylesheets %}
            {{ stylesheet_link("css/bootstrap.min.css") }}
            {{ stylesheet_link("css/bootstrap-theme.min.css") }}
        {% endblock %}
    </head>
    <body>  
        {% block content %}{% endblock %}
        {% block javascripts %}
            {{ javascript_include("js/jquery-2.2.1.min.js") }}
            {{ javascript_include("js/bootstrap.min.js") }}
        {% endblock %}
    </body>
</html>