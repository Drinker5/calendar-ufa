jQuery(function($) {
	var wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
	var fileInput = $(':file').wrap(wrapper);

	fileInput.change(function(){
	    $this = $(this);
	    $('#photo').text($this.val());
	})

	$('#photo').click(function(){
	    fileInput.click();

	    return false;
	}).show();
});