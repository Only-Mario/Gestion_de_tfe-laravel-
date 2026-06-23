
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



// submit delete's form
var deleteLink = $('#delete');
deleteLink.click(function (e) {
  e.preventDefault();
  var confirmed = confirm('Voulez vous vraiment supprimer ce tfe ?');
  if(confirmed){
      document.getElementById('delete-form').submit();
  }
});


