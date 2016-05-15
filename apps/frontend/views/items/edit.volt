{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
    <br>
    <div class="container">
        {{ super() }}
        {{ content() }}
        {% if item != null %}
            <div class="page-header">
                <h2>{{ item.getTitle() }}</h2>
            </div>
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#content" data-toggle="tab">Content</a></li>
              <li role="presentation"><a href="#images" data-toggle="tab">Images <span class="badge">{{ item.getImages()|length }}</span></a></li>
              <li role="presentation"><a href="#videos" data-toggle="tab">Videos <span class="badge">{{ item.getVideos()|length }}</span></a></li>
            </ul>
            <br>
            <div class="tab-content">
                <div class="tab-pane fade in active" id="content">
                    <form method="post" action="/web/items/edit/{{ item.getId() }}">
                        {% for element in itemForm.getElements() %}
                            {{ itemForm.renderDecorated(element.getName()) }}
                        {% endfor %}
                    </form>
                    <br/>
                </div>
                <div class="tab-pane fade" id="images">
                    {% if item.getImages()|length > 0 %}
                        {% for key, img in item.getImages() %}
                            <div class="col-md-4">
                                <a href="#" class="thumbnail" data-item="{{ item.getId() }}" data-id="{{ img|hash }}">
                                  <img src="{{ img }}" title="Click to remove">
                                </a>
                            </div>
                        {% endfor %}
                    {% else %}
                        <h3>Post without images.</h3>
                    {% endif %}
                </div>
                <div class="tab-pane fade" id="videos">
                    {% if item.getVideos()|length > 0 %}
                        {% for video in item.getVideos() %}
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" src="{{ video }}" frameborder="0" allowfullscreen></iframe>
                        </div>
                        <br/>
                        {% endfor %}
                    {% else %}
                        <h3>Post without videos.</h3>
                    {% endif %}
                </div>
            </div>
        {% endif %}
    </div>
{% endblock %}

{% block javascripts %}
    {{ super() }}
    {{ javascript_include("js/tinymce/tinymce.min.js") }}
    {{ javascript_include("js/bootbox.min.js") }}
    <script type="text/javascript">
        tinymce.init({
            selector: '.wysiwyg',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link removeformat',
            schema: 'html5',
            plugins: 'link'
        });

        $(document).ready(function () {
            $('.thumbnail').click(function (event) {
                var link = $(this);
                var location = '/web/items/removeImg/' + link.data('item') + '/' + link.data('id');
                bootbox.confirm('<h4>Are you sure you want to delete this image?</h4>'
                    , function (confirmation) {
                    confirmation && document.location.assign(location);
                });
            });
        });
    </script>

{% endblock %}
