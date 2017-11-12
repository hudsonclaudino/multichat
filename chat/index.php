<?php
	header("location: {$_SERVER['REQUEST_URI']}mc");
echo('	<script>
// Create IE + others compatible event handler
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
eventer(messageEvent,function(e) {
  console.log("parent received message!:  ",e.data);
  document.getElementById("callback").height = e.data + "px";
},false);
</script>'
);
?>