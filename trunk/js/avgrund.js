$('.show').avgrund({			
	width: 380, // max is 640px
	height: 280, // max is 350px
	showClose: false, // switch to 'true' for enabling close button 
	showCloseText: '', // type your text for close button
	closeByEscape: true, // enables closing popup by 'Esc'..
	closeByDocument: true, // ..and by clicking document itself
	holderClass: '', // lets you name custom class for popin holder..
	overlayClass: '', // ..and overlay block
	enableStackAnimation: false, // another animation type
	onBlurContainer: '', // enables blur filter for specified block 
	template: 'Авторизация через Facebook'
});