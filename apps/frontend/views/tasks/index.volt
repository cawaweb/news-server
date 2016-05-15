{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
    <br>
    <div class="container">
        {{ super() }}
        {{ content() }}
        <div class="page-header">
            <h2>Tasks Running</h2>
        </div>
        <div id="running">
            <div class="progress">
              <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">Loading</span>
              </div>
            </div>
        </div>
        <div class="page-header">
            <h2>Tasks Finished</h2>
        </div>
        <div id="finished">
            {% if tasks|length %}
                {% for task in tasks %}
                    {% if task.getStatus() == constant("NewsServer\Common\Collections\ServerTask::FINISHED") %}
                        <div class="panel panel-warning">
                    {% elseif task.getStatus() == constant("NewsServer\Common\Collections\ServerTask::FAILED") %}
                        <div class="panel panel-danger">
                    {% else %}
                        <div class="panel panel-success">
                    {% endif %}
                        <div class="panel-heading">
                            {{ task.getName() }} ({{ task.getTaskId() }})
                            <div class="pull-right">
                                {% if task.getStatus() == constant("NewsServer\Common\Collections\ServerTask::FINISHED") %}
                                    <a href="#" onclick="toogleDetails('{{ task.getId() }}_details')" class="text-warning">
                                {% elseif task.getStatus() == constant("NewsServer\Common\Collections\ServerTask::FAILED") %}
                                    <a href="#" onclick="toogleDetails('{{ task.getId() }}_details')" class="text-danger">
                                {% else %}
                                    <a href="#" onclick="toogleDetails('{{ task.getId() }}_details')" class="text-success">
                                {% endif %}
                                    <span class="glyphicon glyphicon-collapse-down" aria-hidden="true"></span>
                                </a>
                            </div>
                        </div>
                        <div id="{{ task.getId() }}_details" class="panel-body" style="display:none;">
                            <p>
                                <b>Run at:</b> {{ task.getStartDate().format('d/m/Y H:i') }}
                            </p>
                            <p>
                                <b>Time spent:</b> {{ task.getTimeSpent() }} segs.
                            </p>
                            <p>
                                <b>Status:</b> {{ task.getStatus() }}
                            </p>
                            <p>
                                <b>Errors:</b> <span class="badge">{{ task.getErrors() }}</span>
                            </p>
                            <p>
                                <b>Details:</b><br>
                                <samp>{{ task.getHtmlOutput() }}</samp>
                            </p>
                        </div>
                    </div>
                {% endfor %}
            </div>
            <nav>
              <ul class="pagination">
                {% if page.before < page.current %}
                    <li>
                      <a href="/tasks/index/{{ page.before }}" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                {% endif %}
                <li class="active"><a href="/tasks/index/{{ page.current }}">{{ page.current }}</a></li>
                {% if page.next < page.last %}
                    <li><a href="/tasks/index/{{ page.next }}">{{ page.next }}</a></li>
                {% endif %}
                {% if page.next + 1 < page.last %}
                    <li><a href="/tasks/index/{{ page.next + 1 }}">{{ page.next + 1 }}</a></li>
                    {% if page.next + 1 < page.last - 1 %}
                        <li><a href="#">...</a></li>
                    {% endif %}
                {% endif %}
                {% if page.current < page.last %}
                    <li><a href="/tasks/index/{{ page.last }}">{{ page.last }}</a></li>
                    <li>
                      <a href="/tasks/index/{{ page.next }}" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                {% endif %}
              </ul>
            </nav>
        {% else %}
            <p>No tasks found.</p>
        {% endif %}
    </div>
{% endblock %}

{% block javascripts %}
    {{ super() }}
    <script type="text/javascript">
        var toogleFlag = false;

        function toogleDetails(id) {
            var obj = $('#'+id);
            obj.toggle();
            if (obj.is(':visible')) {
                toogleFlag = true;
            } else {
                toogleFlag = false;
            }
        }

        function getRunningTasks() {
            $.get( "/web/tasks/running", function(data) {
                var response = $.parseJSON(data);
                var runningTasks = response.RunningTasks;

                if (runningTasks.length > 0) {
                    var html = '';
                    $.each(runningTasks, function(key, task) {
                        var progress = Math.floor(task.progress);
                        html += '<div class="panel panel-info">' +
                                    '<div class="panel-heading">' + task.name + ' (' + task.taskId + ')' + '</div>' +
                                    '<div class="panel-body">' +
                                        '<div class="progress">' +
                                            '<div class="progress-bar" role="progressbar" aria-valuenow="' + progress + '" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: ' + progress + '%">' +
                                                progress + '%' +
                                            '</div>' +
                                        '</div>' +
                                        '<p>' +
                                            '<b>Started:</b> ' + task.startDate +
                                        '</p>' +
                                        '<p>' +
                                            '<b>Details:</b><br>' +
                                            '<samp>' + task.output + '</samp>' +
                                        '</p>' +
                                    '</div>' +
                                '</div>';
                    });
                    $('#running').html(html);
                } else {
                    $('#running').html("<p>No running task found.</p>");
                }
            });
            setTimeout(getRunningTasks, 500);
        }

        function getFinishedTasks() {
            if (!toogleFlag) {
                var length = window.location.href.length - 1;
                var pageNumber = parseInt(window.location.href.charAt(length));
                var page = 1;
                if (!isNaN(pageNumber)) {
                  page = pageNumber;
                }
                $.get( "/web/tasks/finished/" + page, function(data) {
                    var response = $.parseJSON(data);
                    var finishedTasks = response.FinishedTasks;

                    if (finishedTasks.length > 0) {
                        var html = '';
                        $.each(finishedTasks, function(key, task) {
                            var status = "success";
                            if (task.status == "{{ constant("NewsServer\Common\Collections\ServerTask::FAILED") }}") {
                                status = "danger";
                            } else if (task.status == "{{ constant("NewsServer\Common\Collections\ServerTask::FINISHED") }}") {
                                status = "warning";
                            }
                            html += '<div class="panel panel-' + status + '">' +
                                    '<div class="panel-heading">' + task.name + ' (' + task.taskId + ')' +
                                        '<div class="pull-right">' +
                                            '<a class="text-' + status + '" onclick="toogleDetails(\'' + task.id +'_details\')" href="#">' +
                                                '<span aria-hidden="true" class="glyphicon glyphicon-collapse-down"></span>' +
                                            '</a>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div style="display:none;" class="panel-body" id="' + task.id +'_details">' +
                                        '<p><b>Run at:</b>' + task.startDate + '</p>' +
                                        '<p><b>Time spent:</b> ' + task.timeSpent + ' segs. </p>'+
                                        '<p><b>Status:</b> ' + task.status + '</p>'+
                                        '<p><b>Errors:</b> <span class="badge">' + task.errors + '</span></p>' +
                                        '<p><b>Details:</b><br>' +
                                            '<samp>' + task.output + '</samp>' +
                                        '</p>' +
                                    '</div>' +
                                '</div>';
                        });
                        $('#finished').html(html);
                    } else {
                        $('#finished').html("<p>No finished task found.</p>");
                    }
                });
            }
            setTimeout(getFinishedTasks, 3000);
        }

        getRunningTasks();
        getFinishedTasks();
    </script>
{% endblock %}
