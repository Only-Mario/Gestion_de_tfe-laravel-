
// submit delete's form
var deleteLink = $('#delete');
deleteLink.click(function (e) {
  e.preventDefault();
  var confirmed = confirm('Voulez vous vraiment supprimer ce tfe ?');
  if(confirmed){
      document.getElementById('delete-form').submit();
  }
});


