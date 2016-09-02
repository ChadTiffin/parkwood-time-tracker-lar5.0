@extends("master")
@section("content")
<div class="main users-list" role="main">

<div class="container-fluid">

	<h1 class="pull-left">Users</h1>

	<p><button type="button" class="btn btn-primary pull-right new-user">+ New User</button></p>

	<table class="table users">
		<thead>
		<tr>
			<th>Id</th>
			<th>Name</th>
			<th>Email</th>
			<th>Status</th>
			<th style="width:120px;">Edit</th>
		</tr>
		</thead>
		<tbody>
	
	@foreach ($users as $user)

		<tr 
			data-id="{{$user->id}}"
			data-first-name="{{$user->first_name}}"
			data-last-name="{{$user->last_name}}"
			data-email="{{$user->email}}"
		>
			<td>{{ $user->id }}</td>
			<td class="name">{{ $user->first_name }} {{ $user->last_name }}</td>
			<td class="email">{{ $user->email }}</td>
			<td class="status">{!! $user->status !!}</td>
			<td class='edit-controls' data-id='{{$user->id}}'>
				<button class="btn btn-warning btn-xs edit"><img src="{{URL::to('/')}}/images/edit-icon.png"></button>
				<!--<button class='btn btn-danger btn-sm delete'>- Del</button>-->
			</td>
		</tr>
		
	@endforeach

		</tbody>

	</table>

</div>

<!-- Edit User Modal -->
<div id="edit-user" class="modal fade">
	<div class="modal-dialog">
		<form class="modal-content form-horizontal" method="post" action="{{action('UsersController@editUser')}}">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit User</h4>
			</div>
			<div class="modal-body">

				<div class="form-group">
					<label class="control-label col-sm-3">First Name </label>
					<div class="col-sm-9">
					<input type='text' class="form-control" name="edit-first-name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Last Name </label>
					<div class="col-sm-9">
					<input type='text' class="form-control" name="edit-last-name">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3">Email </label>
					<div class="col-sm-9">
					<input type='email' class="form-control" name="edit-email">
					</div>
				</div>

				<p class="new-user-msg" style="display:none;">New users will automatically be assigned 'password' as their password. They can change it after logging in.</p>

				<input type="hidden" name="id" value="">

				<div style="clear:both"></div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary" id="save-edit">Save</button>
			</div>
		</form>
	</div>
</div>

@stop

@section("js")
<script>
	$(document).ready(function(){

		$('.new-user').click(function(){
			$('#edit-user form').trigger('reset');
			$("#edit-user").modal("show");
			$('input[name=edit-first-name]').focus();
			$('#edit-user .new-user-msg').show();
			$('#edit-user .modal-title').html("New User");
		});

		$('#edit-user').on('shown.bs.modal', function () {
			$('input[name=edit-first-name]').focus();
		})

		$('.users .edit').click(function(){

			var user_id = $(this).parent().attr("data-id");
			var row = $(this).parent().parent();

			//Fill in details
		
			$("#edit-user input[name=edit-first-name]").val(row.attr("data-first-name"));
			$("#edit-user input[name=edit-last-name]").val(row.attr("data-last-name"));
			$("#edit-user input[name=edit-email]").val(row.attr("data-email"));
			$("#edit-user input[name=id]").val(row.attr("data-id"));

			$('#edit-user .new-user-msg').hide();
			$('#edit-user .modal-title').html("Edit " + row.attr("data-first-name"));

			$("#edit-user").modal("show");
		});

		$('#edit-user form').submit(function(e){
			e.preventDefault();

			if ($("#edit-user input[name=id]").val() != "") {
				var row = $('tr[data-id=' + $("#edit-user input[name=id]").val() + "]"); //select row based on 	value of id and data-id attribute match
			}

			var form_data = $(this).serialize();
			var action = $(this).attr("action");

			$.post(action,form_data,function(edit_type){
				$("#edit-user").modal("hide");

				if (edit_type == "new") {
					//refresh page because I'm too lazy to code in a return of the new users data dynamically

					location.reload();
				}
				else {
					row.find(".name").html($("#edit-user input[name=edit-first-name]").val() + " " + $("#edit-user input[name=edit-last-name]").val());
					row.find(".email").html($("#edit-user input[name=edit-email]").val());
				}

				$("#edit-user form").trigger("reset");
			});
		});
		
	});
</script>
@stop
