{% extends "layouts/main.volt" %}

{% block title %}News Server{% endblock %}

{% block content %}
	<br>
	<div class="container">
		{{ super() }}
		{{ content() }}
		<div class="page-header">
			<h2>Add source</h2>
		</div>
		{{ form("/web/sources/add", "method": "post") }}
			{% for element in addForm.getElements() %}
				{{ addForm.renderDecorated(element.getName()) }}
			{% endfor %}
		{{ endForm() }}
	</div>
{% endblock %}

{% block javascripts %}
	{{ super() }}
	<script type="text/javascript">
		$(document).ready(function () {
			$('.btn-info').click(function (event) {
				var id = $('#urls').children().length+1;
				$('#urls').append('<div id="input' + id + '" class="input-group" style="margin-top:10px;"> ' +
                    '<input type="text" placeholder="URL" class="form-control" name="url[]" required>' +
                        '<span class="input-group-btn">' +
                            '<button class="btn btn-danger" type="button" data-id="' + id + '">' +
                                '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>' +
                            '</button>' +
                        '</span>' +
                    '</div>');
				updateRemove();
			});
		});

		function updateRemove() {
			$('.btn-danger').click(function (event) {
				var button = $(this);
				var id = button.data('id');
				$("#input" + id).remove();
				button.remove();
			});
		}
	</script>
{% endblock %}
