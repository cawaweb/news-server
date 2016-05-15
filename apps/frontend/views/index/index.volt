{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
	<br>
	<div class="container">
		{{ super() }}
		{{ content() }}
		<div class="page-header">
		    <h1>Congratulations!</h1>
		</div>

		<p>You're now flying with Phalcon. Great things are about to happen!</p>

		<p>This page is located at <code>views/index/index.phtml</code></p>
	</div>
{% endblock %}
