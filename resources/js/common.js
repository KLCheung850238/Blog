/**
 * popup message box
 */
function show_msg(title, content) {
	$("#msg-title").html(title);
	$("#msg-content").html(content);
	$('#msg-modal').modal('show');
}
/**
 * close message box
 */
function disMsg() {
	$('#msg-modal').modal('hide');

}
/**
 * Delay closing the message prompt box
 */
function disMsgDelay(time) {
	setTimeout(disMsg, time);
}

/**
 * Use a progress bar while loading data
 */
document.onreadystatechange = function() {
	var state = document.readyState;
	if (state == "complete") {
		$(".loading").fadeOut("slow");
	}
}