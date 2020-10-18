$( "#datepicker" ).datepicker({
  // Formato de la fecha
  dateFormat: "dd/mm/yy",
  // Primer dia de la semana El lunes
  firstDay: 1,
  // Dias Largo en castellano
  dayNames: [ "Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado" ],
  // Dias cortos en castellano
  dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
  // Nombres largos de los meses en castellano
  monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ],
  // Nombres de los meses en formato corto 
  monthNamesShort: [ "Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic" ],
  // Cuando seleccionamos la fecha esta se pone en el campo Input 
  onSelect: function(dateText) { 
        $('#fecha').val(dateText);
    }
});