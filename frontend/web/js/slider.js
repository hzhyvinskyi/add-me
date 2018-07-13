$(document).ready(function () {
	var nextPageF =  function () {
		var currentImage = $('.slider__current-slide');
		var currentImageIndex = currentImage.index();
		var nextImageIndex = currentImageIndex + 1;
		var nextImage = $('.slider__img').eq(nextImageIndex);

		currentImage.fadeOut(1000);
		currentImage.removeClass('slider__current-slide');

		if (nextImageIndex == ($('.slider__img:last').index() + 1)) {
			$('.slider__img:first').fadeIn(1000);
			$('.slider__img:first').addClass('slider__current-slide');
		} else {
			nextImage.fadeIn(1000);
			nextImage.addClass('slider__current-slide');
		}
	};

	setInterval(nextPageF, 5000);
});
