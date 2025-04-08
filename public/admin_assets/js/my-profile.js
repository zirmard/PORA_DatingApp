$(function () {
    $(".upload-button").on('click', function () { // On click of camera icon, open images dialog
       $(".file-upload").click();
   });

   function readURL(input) { // Image Preview before upload in edit profile module
       if (input.files && input.files[0]) {
       var reader = new FileReader();
       reader.onload = function(e) {
           $('.profile-pic').attr('src', e.target.result);
       }
       reader.readAsDataURL(input.files[0]);
       }
   }

   $(".file-upload").change(function() {
       readURL(this);
   });
});
