@extends("master")
@section("content")
<div class="main summary" role="main">

<div class="container-fluid">

	<h1>Summary</h1>
	<div class="row">
		<div class='col-sm-6'>

			<!--<h2>Hours Banked</h2>
			<table class="table">
				<tr>
					<th>Total Banked</th>
					<td>12.1</td>
				</tr>
			</table>-->

			<h2>This Week</h2>
			<table class="table">
				<tr>
					<th>Sunday</th>
					<td>{{{$daily_totals[7]}}}</td>
				</tr>
				<tr>
					<th>Monday</th>
					<td>{{{$daily_totals[8]}}}</td>
				</tr>
				<tr>
					<th>Tuesday</th>
					<td>{{{$daily_totals[9]}}}</td>
				</tr>
				<tr>
					<th>Wednesday</th>
					<td>{{{$daily_totals[10]}}}</td>
				</tr>
				<tr>
					<th>Thursday</th>
					<td>{{{$daily_totals[11]}}}</td>
				</tr>
				<tr>
					<th>Friday</th>
					<td>{{{$daily_totals[12]}}}</td>
				</tr>
				<tr>
					<th>Saturday</th>
					<td>{{{$daily_totals[13]}}}</td>
				</tr>
				<tr>
					<th>Total This Week</th>
					<td><strong>{{{$this_week_total}}} hrs</strong></td>
				</tr>
			</table>

			<h2>Last Week</h2>
			<table class="table">
				<tr>
					<th>Sunday</th>
					<td>{{{$daily_totals[0]}}}</td>
				</tr>
				<tr>
					<th>Monday</th>
					<td>{{{$daily_totals[1]}}}</td>
				</tr>
				<tr>
					<th>Tuesday</th>
					<td>{{{$daily_totals[2]}}}</td>
				</tr>
				<tr>
					<th>Wednesday</th>
					<td>{{{$daily_totals[3]}}}</td>
				</tr>
				<tr>
					<th>Thursday</th>
					<td>{{{$daily_totals[4]}}}</td>
				</tr>
				<tr>
					<th>Friday</th>
					<td>{{{$daily_totals[5]}}}</td>
				</tr>
				<tr>
					<th>Saturday</th>
					<td>{{{$daily_totals[6]}}}</td>
				</tr>
				<tr>
					<th>Total Last Week</th>
					<td><strong>{{{$last_week_total}}} hrs</strong></td>
				</tr>
			</table>
		</div>

		<div class='col-sm-6'>

			<h2>Today's Logs</h2>
			<table class="table">
				<tr>
					<th>Clock IN</th>
					<th>Clock OUT</th>
					<th style="text-align:right;">Time (hrs)</th>
				</tr>

				@foreach($today_logs as $log)
						
					<tr>
						<td style="text-align:left;">{{{format_datetime($log->clocked_in,"time")}}}</td>
						<td style="text-align:left;">
							<?php
							if ($log->clocked_out == null) {
								echo "[in progress]";
							}
							else {
								echo format_datetime($log->clocked_out,"time");
							} ?>
						</td>
						<td>
							<?php
							if ($log->clocked_out == null) {
								echo "...";
							}
							else {
								echo abs(round($log->shift_total/60,1));
							} ?>
						</td>
					</tr>

				@endforeach
				
			</table>

			<h2>This Year</h2>
			<table class="table">
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-01-01/{{$yr}}-01-31/">January</a></th>
					<td>{{{$monthly_totals[0]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-02-01/{{$yr}}-02-29/">February</a></th>
					<td>{{{$monthly_totals[1]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-03-01/{{$yr}}-03-31/">March</a></th>
					<td>{{{$monthly_totals[2]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-04-01/{{$yr}}-04-30/">April</a></th>
					<td>{{{$monthly_totals[3]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-05-01/{{$yr}}-05-31/">May</a></th>
					<td>{{{$monthly_totals[4]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-06-01/{{$yr}}-06-30/">June</a></th>
					<td>{{{$monthly_totals[5]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-07-01/{{$yr}}-07-31/">July</a></th>
					<td>{{{$monthly_totals[6]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-08-01/{{$yr}}-08-31/">August</a></th>
					<td>{{{$monthly_totals[7]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-09-01/{{$yr}}-09-30/">September</a></th>
					<td>{{{$monthly_totals[8]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-10-01/{{$yr}}-10-31/">October</a></th>
					<td>{{{$monthly_totals[9]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-11-01/{{$yr}}-11-30/">November</a></th>
					<td>{{{$monthly_totals[10]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-12-01/{{$yr}}-12-31/">December</a></th>
					<td>{{{$monthly_totals[11]}}}</td>
				</tr>
				<tr>
					<th><a href="{{URL::to('/')}}/logs/{{$yr}}-01-01/{{$yr}}-12-31/">2016</a></th>
					<td><strong>{{{$year_total}}}</strong></td>
				</tr>
			</table>
		</div>
	</div>
</div>
@stop