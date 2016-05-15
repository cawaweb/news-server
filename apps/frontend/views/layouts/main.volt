{% extends "layouts/base.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-options" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        {{ link_to("/web/index", "News", "class": "navbar-brand") }}
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sources <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li>{{ link_to("/web/sources/add", "Add new") }}</li>
              <li>{{ link_to("/web/sources", "Active sources") }}</li>
              <li role="separator" class="divider"></li>
              <li>{{ link_to("/web/sources/disabled", "Disabled sources") }}</li>
            </ul>
          </li>
          <li>{{ link_to("/web/items/index", "Items") }}</li>
          <li>{{ link_to("/web/tasks", "Tasks") }}</li>
        </ul>
        <form action="/web/tasks/start" method="post" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <select class="form-control" name="task">
                <option value="Fetch">Update news</option>
                <option value="Review">Review news</option>
                <option value="Approve">Approve news</option>
            </select>
          </div>
          <button type="submit" class="btn btn-info">Run</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li>{{ link_to("/web/session/logout", "Logout") }}</li>
        </ul>
      </div>
    </div>
  </nav>
{% endblock %}
