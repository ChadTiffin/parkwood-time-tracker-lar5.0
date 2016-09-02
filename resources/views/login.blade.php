@include("head")
</head>
<body>
@include("header",$header_data)

<div class="main login" role="main">

<div class="container-fluid">

<form method="post" action="{{URL::to('/')}}/login/process" class="form-horizontal panel panel-default">

<div class="panel-body">
	<h1 style="text-align:center;margin-bottom:30px;">Login</h1>

	@if (Request::segment(1) == "logout")
		<div class="alert alert-danger" >You are logged out.</div>
	@else
		<div class="alert" style="display:none;"></div>
	@endif

	<div class="form-group">
		
		<label class="control-label col-sm-4">Email </label>
		<div class="col-sm-8">
			<input type='email' name="email" class="form-control" required />
		</div> 
	</div>

	<div class="form-group">
		<label class="control-label col-sm-4">Password </label>
		<div class="col-sm-8">
			<input type='password' name="password" class="form-control" required />
		</div>
		
	</div>

	<p><input type="submit" value="Sign In" class="btn btn-primary pull-right"></p>
</div>
</form>

@include('scripts')

<script type="text/javascript">
	$(document).ready(function(){
		$("form").submit(function(e){
			e.preventDefault();

			var form_data = $(this).serialize();
			var action = $(this).attr("action");

			$.post(action,form_data,function(response){

				if (response == "false") {
					$('.alert').addClass("alert-danger").show().html("Login failed.");
				}
				else {
					location.href = BASE_URL + "/";
				}
			});
		});
	});
</script>

</body>
</html>