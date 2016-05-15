{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
	<br>
	<div class="container">
		{{ super() }}
		{{ content() }}
		<div class="page-header">
		    <h2>Active Sources</h2>
		</div>
		{% if sources|length %}
			{% for source in sources %}
				<div class="panel panel-success">
                    <div class="panel-heading">
                        {{ source.getName() }}
                    </div>
                    <div class="panel-body">
    					<div class="pull-left">
    						<p>Last modified: {{ source.getLastModified() }}</p>
    					</div>
    					<span class="btn-group pull-right">
    					  <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span></button>
    					  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    					    <span class="caret"></span>
    					    <span class="sr-only">Toggle Dropdown</span>
    					  </button>
    					  <ul class="dropdown-menu">
    					    <li><a href="sources/edit/{{ source.getId() }}">Edit</a></li>
                            <li><a href="#" class="bg-danger" data-action="reset" data-id="{{ source.getId() }}" data-name="{{ source.getName() }}">Reset</a></li>
    					    <li><a href="#" class="bg-danger" data-action="disable" data-id="{{ source.getId() }}" data-name="{{ source.getName() }}">Disable</a></li>
    					  </ul>
    					</span>
                    </div>
				</div>
			{% endfor %}
		{% else %}
			<p>No active sources found.</p>
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
    			var location = '/web/sources/' + button.data('action') + '/' + button.data('id');
    			bootbox.confirm('<h4>Are you sure you want to ' + button.data('action') + ' ' + button.data('name') + '?</h4>'
    				, function (confirmation) {
    				confirmation && document.location.assign(location);
    			});
    		});
    	});
	</script>
{% endblock %}
