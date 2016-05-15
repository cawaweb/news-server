{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
	<br>
	<div class="container">
		{{ super() }}
		{{ content() }}
		{% if source != null %}
			<div class="page-header">
			    <h2>{{ source.getName() }}</h2>
			</div>
			<form method="post" action="/web/sources/edit/{{ source.getId() }}">
				{% for element in sourceForm.getElements() %}
					{{ sourceForm.renderDecorated(element.getName()) }}
				{% endfor %}
			</form>
		{% endif %}
	</div>
{% endblock %}