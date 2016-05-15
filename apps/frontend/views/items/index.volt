{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
    <br>
    <div class="container">
        {{ super() }}
        {{ content() }}
        <div class="page-header">
            <h2>Items</h2>
        </div>
        {% if items|length %}
            {% for item in items %}
                {% if item.getEdited() and item.getApproved() %}
                    <div class="panel panel-success">
                {% elseif item.getEdited() %}
                    <div class="panel panel-warning">
                {% else %}
                    <div class="panel panel-danger">
                {% endif %}
                    <div class="panel-heading">
                        {{ item.getTitle() }}
                    </div>
                    <div class="panel-body">
                        <div class="pull-left">
                            <p>By: {{ sources[item.getSourceId().__toString()] }}</p>
                            <p>Published: {{ item.getDatetime().format('d/m/Y H:i') }}</p>
                            <p><span class="badge">{{ item.getVideos()|length }}</span> Videos <span class="badge">{{ item.getImages()|length }}</span> Images</p>
                        </div>
                        <span class="btn-group pull-right">
                            <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></button>
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="caret"></span>
                              <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                              <li><a href="/items/edit/{{ item.getId() }}">Edit</a></li>
                              <li><a href="#" class="bg-danger" data-id="{{ item.getId() }}" data-name="{{ item.getTitle() }}">Delete</a></li>
                            </ul>
                        </span>
                    </div>
                </div>
            {% endfor %}
            <nav>
              <ul class="pagination">
                {% if page.before < page.current %}
                    <li>
                      <a href="/items/index/{{ page.before }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                {% endif %}
                <li class="active"><a href="/items/index/{{ page.current }}">{{ page.current }}</a></li>
                {% if page.next < page.last %}
                    <li><a href="/items/index/{{ page.next }}">{{ page.next }}</a></li>
                {% endif %}
                {% if page.next + 1 < page.last %}
                    <li><a href="/items/index/{{ page.next + 1 }}">{{ page.next + 1 }}</a></li>
                    {% if page.next + 1 < page.last - 1 %}
                        <li><a href="#">...</a></li>
                    {% endif %}
                {% endif %}
                {% if page.current < page.last %}
                    <li><a href="/items/index/{{ page.last }}">{{ page.last }}</a></li>
                    <li>
                      <a href="/items/index/{{ page.next }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                {% endif %}
              </ul>
            </nav>
        {% else %}
            <p>No items found.</p>
        {% endif %}
    </div>
{% endblock %}

{% block javascripts %}
    {{ super() }}
    {{ javascript_include("js/bootbox.min.js") }}
    <script type="text/javascript">
        $(document).ready(function () {
        $('.bg-danger').click(function (event) {
            var button = $(this);
            var location = '/web/items/delete/' + button.data('id');
            bootbox.confirm('<h4>Are you sure you want to delete "' + button.data('name') + '"?</h4>'
                , function (confirmation) {
                confirmation && document.location.assign(location);
            });
        });
    });
    </script>
{% endblock %}
