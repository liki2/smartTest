$(document).ready(function(){

  $('#account-list').on('click',function(){
    window.location = 'list.php';
  });

  //ACTION TO SHOW OR DISPLAY PANELS
  $('#account-create').on('click',function(){
    $('.form_alta_a').hide();
    $('.form_alta').show();
  });

  //AJAX TO UPDATE DATA
  $('.update-account').on('click',function(){

         if($('#formName').val().length < 4){
           return false;
         }
         if($('#formAddress').val().length < 10){
           return false;
         }
         if($('#formPhone').val().length < 10){
           return false;
         }

         $('#modal-messages').html('<p>Ingresa el Codigo para realizar cambios a este registro</p><br><input type="text" maxlength="8" minlength="8" class="form-control" id="formCode" aria-describedby="codeHelp" placeholder="Ingresa codigo" required>');
         $('#ModalSystem').modal('toggle');
  });

  $('.validate-code').on('click',function(){
    var codeIn = $('#formCode').val();
    var codeOut = $('#code').val();
    if(codeIn == codeOut){
      $.ajax({
          // En data puedes utilizar un objeto JSON, un array o un query string
          data: {
            name : $('#formName').val(),
            address : $('#formAddress').val(),
            phone : $('#formPhone').val(),
            rowId : $('#rowId').val()
          },
          //Cambiar a type: POST si necesario
          type: "POST",
          // Formato de datos que se espera en la respuesta
          dataType: "json",
          // URL a la que se enviará la solicitud Ajax
          url: "saveaccount.php",
      })
       .done(function( data, textStatus, jqXHR ) {
           if ( console && console.log ) {
               console.log(data.status);
               console.log( "La solicitud se ha completado correctamente." );
               $('#modal-messages').html('<p>El registro se actualizo correctamente</p>');
               $('#ModalSystem').modal('toggle');

           }
       })
       .fail(function( jqXHR, textStatus, errorThrown ) {
           if ( console && console.log ) {
               console.log( "La solicitud a fallado: " +  textStatus);
           }
      });
    }else{
      $('#modal-messages').html('<p class="bg-danger text-warning">Error de código</p><p>Ingresa el Codigo para realizar cambios a este registro</p><br><input type="text" maxlength="8" minlength="8" class="form-control" id="formCode" aria-describedby="codeHelp" placeholder="Ingresa codigo" required>');
    }
  });


  //AJAX TO ADD INFO TO THE INTERNAL DATABASE
   $('.save-account').on('click',function(){

     if($('#formName').val().length < 4){
       return false;
     }
     if($('#formAddress').val().length < 10){
       return false;
     }
     if($('#formPhone').val().length < 10){
       return false;
     }
           $.ajax({
               // En data puedes utilizar un objeto JSON, un array o un query string
               data: {
                 name : $('#formName').val(),
                 address : $('#formAddress').val(),
                 phone : $('#formPhone').val(),
               },
               //Cambiar a type: POST si necesario
               type: "POST",
               // Formato de datos que se espera en la respuesta
               dataType: "json",
               // URL a la que se enviará la solicitud Ajax
               url: "saveaccount.php",
           })
            .done(function( data, textStatus, jqXHR ) {
                if ( console && console.log ) {
                    console.log(data.status);
                    console.log( "La solicitud se ha completado correctamente." );
                    $('#modal-messages').html('<p>El registro se genero correctamente</p><p>El Id de registro es '+data.id+'</p><p>El codigo de encriptación es '+data.code+'</p>');
                    $('#ModalSystem').modal('toggle');
                    $('#formRegister input').each(function(){
                      $(this).val('');
                    });
                    $('.form_alta_a').show();
                    $('.form_alta').hide();
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    console.log( "La solicitud a fallado: " +  textStatus);
                }
           });

   });

});
