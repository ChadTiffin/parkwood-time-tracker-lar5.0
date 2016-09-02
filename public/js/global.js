
$(document).ready(function(){

	$("#punch-clock").submit(function(e){
		e.preventDefault();

		var action = $(this).attr("action");
		$.post(action,function(data){
			
			var direction = "";
			if (data == "clocked out") {
				$('.clock-status').html("Clocked out.");
				$('#punch-clock input').val("Clock IN").removeClass('btn-warning').addClass("btn-success");

				direction = "out";
			}
			else {
				$('.clock-status').html("Clocked in at " + data);
				$('#punch-clock input').val("Clock OUT").removeClass('btn-success').addClass("btn-warning");
			
				direction = "in";
			}

			$.post(BASE_URL + "/hit-slack","direction=" + direction);
		});
	})

	$('.date').datetimepicker({
		showClear : true,
		showClose : true,
		showTodayButton : true,
		format : "YYYY-MM-DD"
	});

	$('.mobile-menu').click(function(){
		$('.navbar-nav').toggle();
	});
});