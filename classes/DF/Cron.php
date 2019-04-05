<?php
// Depends on CronExpressions by mtdowling/cron-expression

namespace DF;

class Cron{
	protected $jobs = [];
	protected $options = [];

	function __construct($options=[]){
		//Set default options
		if(gettype($options)=="array"){
			$defaults = [
				"mailer" => "dekyfinCron@" . gethostname(),
			];
			$this->options = array_merge($defaults, $options);
		}
	}

	function add($id, $r){
		$this->jobs[$id] = $r;
	}

	function run(){
		$jobs = $this->jobs;

		foreach($jobs as $id => $job){
			if( ! isset($job["enabled"]) || ! $job["enabled"])
				continue;

			$xpr = \Cron\CronExpression::factory($job["schedule"]);

			if($xpr->isDue()){	//if its due
				//append default options
				$job = array_merge($this->options, $job);

				//Exit if function is not set
				if(!isset($job["function"]))
					return;

				//Output buffering
				ob_start();
				@$job["function"]();	//Execute function
				$output = ob_get_clean();

				//Save log to file
				if(isset($job["output"]))
					file_put_contents($job["output"], $output, FILE_APPEND );

				//Email Log
				if(isset($job["email"]) )
					mail($email, "Cron Job '$id' completed", $output, "From:" . $job["mailer"] . "\r\n" );
			}
		}
	}
}