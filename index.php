<?php
require_once('getID3/getid3/getid3.php');


if (isset($_POST["submit"]))
{
	$file_name = $_FILES["video"]["tmp_name"];
	$vid_duration = $_POST["duration"];
	$videoname = $_POST["videoname"];
	$output_file_path = "split/".$videoname;
	$getID3 = new getID3();
	$fileinfo = $getID3->analyze($file_name);
	$str_time=$fileinfo['playtime_string'];

	sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

	$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

    $loop = ceil(floatval($time_seconds)/$vid_duration);
     $cut_from_time = 0;

	  for ($i=0 ; $i < $loop ; $i++ ) { 
        $command = "C:/ffmpeg/bin/ffmpeg -i " . $file_name . " -vcodec copy -ss " . $cut_from_time . " -t " . $vid_duration. " " . $output_file_path.$i.".mp4";

        system($command);
        $cut_from_time = $cut_from_time + $vid_duration;
    }
}

?>

<link rel="stylesheet" href="bootstrap-darkly.min.css">

<div class="container" style="margin-top: 100px;">
	<div class="row">
		<div class="offset-md-4 col-md-4">
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label>Video</label>
					<input type="file" name="video" class="form-control" >
				</div>


				<div class="form-group">
					<label>Duration</label>
					<input type="text" name="duration" class="form-control" placeholder="300.0">
				</div>

				<div class="form-group">
					<label>Name Video</label>
					<input type="text" name="videoname" class="form-control" placeholder="Video name Enter">
				</div>

				<input type="submit" name="submit" class="btn btn-primary" value="Split">

			</form>
		</div>
	</div>
</div>

