(function() {

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
	  if(document.getElementById("scroll-btn")){
    		document.getElementById("scroll-btn").style.display = "block";
	  }
  } else {
	   if(document.getElementById("scroll-btn")){
    		document.getElementById("scroll-btn").style.display = "none";
	   }
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}

})();