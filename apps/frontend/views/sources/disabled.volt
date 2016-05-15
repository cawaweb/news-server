{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
	<br>
	<div class="container">
		{{ super() }}
		{{ content() }}
		<div class="page-header">
		    <h2>Disabled Sources</h2>
		</div>
		{% if sources|length %}
			{% for source in sources %}
				<div class="panel panel-danger">
                    <div class="panel-heading">
                        {{ source.getName() }}
                    </div>
                    <div class="panel-body">
    					<div class="pull-left">
    						<p>Last sync: {{ source.getLastModified() }}</p>
    					</div>
    					<span class="btn-group pull-right">
    						<button data-id="{{ source.getId() }}" data-name="{{ source.getName() }}" class="btn btn-success">Enable</button>
    					</span>
                    </div>
				</div>
			{% endfor %}
		{% else %}
			<p>No disabled sources found.</p>
		{% endif %}
	</div>
{% endblock %}

{% block javascripts %}
	{{ super() }}
    {{ javascript_include("js/bootbox.min.js") }}
	<script type="text/javascript">
		$(document).ready(function () {
		$('.btn-success').click(function (event) {
			var button = $(this);
			var location = '/web/sources/enable/' + button.data('id');
			bootbox.confirm("<h4>Are you sure you want to enable " + button.data('name') + "?</h4>"
				, function (confirmation) {
				confirmation && document.location.assign(location);
			});
		});
	});
	</script>
{% endblock %}
