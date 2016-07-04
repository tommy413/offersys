var t;
function run(){
	runsite=window.open("js/run.php","Cal");
	t=setTimeout("run();",600000);
}
function stop(){
	clearTimeout(t);
}
