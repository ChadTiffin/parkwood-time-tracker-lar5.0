@extends("master")
@section("content")
<div class="main summary" role="main">

<div class="container-fluid">

	<h1>Settings</h1>

	<h2>System Variables</h2>

	<p>Click the "Save Changes" button after you're done making changes.</p>

	<form class="form-horizontal" id="settings-form" action="{{action('SettingsController@saveSettings')}}" method="post">
		
		@foreach ($settings as $setting)
		
		<div class="form-group">
			<label class="col-sm-3 control-label">{{$setting->label}}</label>
			<div class="col-sm-9">
				<?php 
				if ($setting->type == "select") { ?>
					<select name="{{ $setting->id }}" class="form-control">
						<?php
							$options = explode(",", $setting->options);

							foreach ($options as $option) { ?>

								<option <?php if ($option == $setting->value) {echo "selected";} ?> >{{$option}}</option>

							<?php
							} 
							?>
					</select>
				<?php 
				}
				elseif ($setting->type == "textarea") { ?>

					<textarea name="{{$setting->setting_name}}" class="form-control">{{$setting->value}}</textarea>

				<?php }
				else { ?>
					<input type="{{$setting->type}}" value="{{$setting->value}}" name="{{$setting->id}}" class="form-control">
				<?php } ?>
			</div>
			<p class="help-block pull-right">{{$setting->description}}</p>
		</div>

		@endforeach

		<div class="alert alert-dismissible settings-alert" role="alert" style="display:none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<div class="alert-body"></div>
		</div>

		<input type="submit" value="Save Changes" class="btn btn-primary pull-right">
	</form>

	<div class="clearfix"></div>

	<h2>Change Password</h2>

	<form class="form-horizontal" id="password-form" action="{{action('SettingsController@changePassword')}}" method="post">

		<div class="form-group">
			<label class="col-sm-3 control-label">New Password</label>
			<div class="col-sm-9">
				<input type="password" value="" name="new-psw" class="form-control">
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Confirm Password</label>
			<div class="col-sm-9">
				<input type="password" value="" name="confirm-psw" class="form-control">
			</div>
		</div>

		<div class="alert alert-dismissible password-alert" role="alert" style="display:none;">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<div class="alert-body"></div>
		</div>

		<input type="submit" value="Change Password" class="btn btn-primary pull-right">

	</form>
</div>
@stop

@section("js")
<script>
	$(document).ready(function(){

		$("#settings-form").submit(function(e){
			e.preventDefault();

			var form_data = $(this).serialize();
			var action = $(this).attr("action");

			var alert_el = $(this).find('.alert');

			alert_el.show().addClass("alert-info").find(".alert-body").html("Saving...");

			$.post(action,form_data,function(){
				alert_el.show().removeClass("alert-info").addClass("alert-success").find(".alert-body").html("Changes saved.");
			});	
		});

		$("#password-form").submit(function(e){
			e.preventDefault();

			var form_data = $(this).serialize();
			var action = $(this).attr("action");

			var alert_el = $(this).find('.alert');

			if ($(this).find("input[name=new-psw]").val() != $(this).find("input[name=confirm-psw]").val()) {
				alert_el.show().addClass("alert-danger").removeClass("alert-success alert-info").find(".alert-body").html("Your password confirmation doesn't match.");
			}
			else {
				alert_el.show().addClass("alert-info").find(".alert-body").html("Saving...");

				$.post(action,form_data,function(){
					alert_el.show().removeClass("alert-info alert-danger").addClass("alert-success").find(".alert-body").html("Password changed.");
				});	
			}
		});
	});
</script>
@stop