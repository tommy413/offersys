var t;
function run(){
	runsite=window.open("js/run.php","Cal");
	t=setTimeout("run();",60000);
}
function stop(){
	clearTimeout(t);
}
