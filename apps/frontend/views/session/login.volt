{% extends "layouts/base.volt" %}

{% block title %}Login{% endblock %}

{% block content %}
	<div class="container">
		<div class="page-header">
		    <h2>Login</h2>
		</div>

		{{ content() }}

		{{ form('session/login') }}
		    <fieldset>
		        <div class="input-group">
		        	<span class="input-group-addon" id="basic-addon1">@</span>
		            <div>
		                {{ text_field('username', 'class': 'form-control', 'placeholder': 'Username') }}
		            </div>
		        </div>
		        <br>
		        <div class="input-group">
		            <div>
		                {{ password_field('password', 'class': 'form-control', 'placeholder': 'Password') }}
		            </div>
		            <span class="input-group-addon" id="basic-addon1">Password</span>
		        </div>
		        <br>
		        <div>
		            {{ submit_button('Send', 'class': 'btn btn-default') }}
		        </div>
		        <input type="hidden" name="{{ this.security.getTokenKey() }}" value="{{ this.security.getToken() }}"/>
		    </fieldset>
		</form>
	</div>
{% endblock %}
