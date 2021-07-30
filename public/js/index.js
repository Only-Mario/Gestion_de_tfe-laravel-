
// show search form
$( ".search-by-entity").click(function (e) {
  e.preventDefault();
  if ( $( ".search-by-entity-items" ).first().is( ":hidden" ) ) {
    $( ".search-by-entity-items" ).slideDown( "fast" );
  } else {
    $( ".search-by-entity-items" ).slideUp("fast");
  }
});

$( ".search-by-year").click(function (e) {
  e.preventDefault();
  if ( $( ".search-by-year-items" ).first().is( ":hidden" ) ) {
    $( ".search-by-year-items" ).slideDown( "fast" );
  } else {
    $( ".search-by-year-items" ).slideUp("fast");
  }
});

// caroussel
let home = $("#home"), i=1;
const count = 5;
function slideImg() {
  setTimeout(() => {
      if(i < count){
          i++;
      } else{
          i = 1;
      }
      home.css({
        backgroundImage: 'url(../images/img'+ i +'.jpg)'
      }) ;          
      slideImg();
  }, 5000);
}
slideImg();

// submit delete's form
var deleteLink = $('#delete');
deleteLink.click(function (e) {
  e.preventDefault();
  var confirmed = confirm('Voulez vous vraiment supprimer ce tfe ?');
  if(confirmed){
      document.getElementById('delete-form').submit();
  }
});


