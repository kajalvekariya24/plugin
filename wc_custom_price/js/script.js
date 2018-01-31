jQuery(document).ready(function($){

/* CREATE DEFAULT DOORS */
var seleted_range = $('.ranges_box_radio').val();
createDoors(2,4);
getDefalutDoors(seleted_range);
getColor(seleted_range);
setPrice(seleted_range);
calculateActualHeight();

var select_door = $('.doors_select').val();
calculateDoorWidth(select_door);

/* DEFALUT TAB CLICK */
  $('ul.tabs li').click(function(){
    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');

    $(this).addClass('current');
    $("#"+tab_id).addClass('current');
  })


/* VALIDATION FOR WIDTH  */
$('#first_step').click(function(){
  checkFirstStepValidation();
});



function checkFirstStepValidation(){

    var width = $('#c_width').val();
    var min_width = $('#c_min_width').val();
    var max_width = $('#c_max_width').val();
    var fields = $(".line_required").find("select, textarea, input").serializeArray();
    
    $valid = true;
    $.each(fields, function(i, field) {
    if (!field.value){
      var v_obj = document.getElementsByName(field.name);
      $('[name="'+field.name+'"]').css('border','1px solid #F00');
      //alert(field.name + ' is required');
      $valid = false;
    } 
    });

    if($valid == true)
    {
        $('#c_width').removeClass('invalid');
        $('.tab-content').removeClass('current');
        $('#tab-2').addClass('current');
        $('.tab-link').removeClass('current');
        $('#tabli-2').addClass('current');
    }
};


/* NUMBERS OF AVAILABLE DOORS WITHIN WIDTH AND GET SAVED DOORS */

$('#continue_at_step2').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-3').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-3').addClass('current');

    var ranges_box_radio = $('.ranges_box_radio').val();

    var data = {
      'action': 'get_doors_and_style',
      'width': ranges_box_radio
    };
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
      //$('.loading').hide();
      var d = JSON.parse(response);
      //$('#canvas_details').html('<p>width '+d.width+'</p><p>default_doors '+d.default_doors+'</p><p>min_doors '+d.min_doors+'</p> <p>max_doors '+d.max_doors+'</p>');
      //$('#price_initiate').html('<h2> Price : '+d.initial_price+'</h2>');
      var can_width = d.width;
      respondCanvas(d.width,can_height,d.default_doors,1);
    });    
});  

$('#back_at_step2').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-1').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-1').addClass('current');
});  

$('#continue_at_step3').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-4').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-4').addClass('current');   
});

$('#back_at_step3').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-2').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-2').addClass('current');
}); 




$('#continue_at_step4').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-5').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-5').addClass('current');   
});

$('#back_at_step4').click(function()
{
    $('.tab-content').removeClass('current');
    $('#tab-3').addClass('current');
    $('.tab-link').removeClass('current');
    $('#tabli-3').addClass('current');
}); 



  /* SHOW CANVAS IN CHNAGE */
  $('#c_width').keyup(function(){
    var width = $(this).val();
    var data = {
      'action': 'get_canvas_attrs',
      'width': width
    };
    jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
      //console.log(response);
      var d = JSON.parse(response);
      //$('#canvas_details').html('<p>width '+d.width+'</p><p>default_doors '+d.default_doors+'</p><p>min_doors '+d.min_doors+'</p> <p>max_doors '+d.max_doors+'</p>');
      //console.log(d.min_doors);
      
      var can_width = d.width;
      createDoors(d.min_doors,d.max_doors);
      respondCanvas(d.width,can_height,d.default_doors,1);

      var select_door = $('.doors_select').val();
      calculateDoorWidth(select_door);
    });
  })

/* CALCULATE ACTUAL HEIGHT */
$('#c_floor_to_ceiling').keyup(function(){
  calculateActualHeight();
})

function calculateActualHeight(){
    var c_floor_to_ceiling = $('#c_floor_to_ceiling').val();
    var actual_height = c_floor_to_ceiling - 45;
    $('#actual_door_height').val(actual_height);
}

function createDoors(min_doors,max_doors){
  $('#no_of_doors').html('');
  var check;  
  for (var i = min_doors; i <= max_doors; i++) 
  {
      if(i == min_doors){check = 'checked'} else {check = ''}
      $('#no_of_doors').append('<label><input type="radio" class="doors_select" name="no_of_doors" value="'+i+'" '+check+'><div class="doors_available">Doors <span>'+i+'</span></div></label>');
  }
}


$(document).on('change', '.doors_select', function() { 
   var select_door = $(this).val();
  calculateDoorWidth(select_door);
  respondCanvas(2000,1200,select_door,1);
});

function calculateDoorWidth(select_door){
  var width = $('#c_width').val();
  var data = {
  'action': 'get_door_width',
  'selectedRange': { select_door : select_door, width : width }
  };
  jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
    //$('#price_initiate').html(response);
    $('#door_width').val(response);
    console.log(response);
  });  
  

}


/* CALL ON RANGE CHNAGE */
$(document).on('change', '.ranges_box_radio', function() { 
  var seleted_range = $(this).val();
  
  setPrice(seleted_range);
  getDefalutDoors(seleted_range);
  getColor(seleted_range);
});

function setPrice(seleted_range){
    var data = {
    'action': 'get_range_price',
    'selectedRange': seleted_range
    };
    jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
      $('#price_initiate').html(response);
    });
}

function getDefalutDoors(seleted_range){
    var data = {
      'action': 'get_range_doors',
      'selectedRange': seleted_range
    };
    jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
      $('#doors_design').html(response);
    });
}

function getColor(seleted_range){
    var data = {
    'action': 'get_range_color',
    'selectedRange': seleted_range
    };
    jQuery.post(configuratior_ajax.ajax_url, data, function(response) {
      $('#range_colors').html(response);
    });
}
/* ON BOX CLICK*/


/* ON CLICK RANGE RADIO BUTTON */


/* CANVAS CREATION */
var c = $('#respondCanvas');
    var ct = c.get(0).getContext('2d');   
    
    var can_width = 2695;
    var can_height = 1200;  
    var columns = 6;
      var rows = 4;
    
  function respondCanvas(can_width,can_height,columns,rows){
      c.attr('width',can_width *25.4/96); //max width
        c.attr('height',can_height *25.4/96); //max height      
        
        var w = c.width();
        var h = c.height();   
        ct.fillStyle = "#323042"; //black
        ct.strokeRect( 0, 0, w, h); //fill the canvas 
      ct.lineWidth = 2;
      ct.stroke();
      
      respondCanvas.onresize = calcSize;
      
      function calcSize() {
                
        tileWidth = w/columns;
        tileHeight = h/rows;
      
        ct.strokeStyle = '#000';
        ct.fillStyle = '#f70';
      
        render();
      }
      calcSize();
      function render() {     
        ct.clearRect(0, 0, w/2, h/2);     
        ct.beginPath();       
        for(var x = 0; x < columns; x++) {
          ct.moveTo(x * tileWidth, 0);
          ct.lineTo(x * tileWidth, h);
        }
        for(var y = 0; y < rows; y++) {
          ct.moveTo(0, y * tileHeight);
          ct.lineTo(w, y * tileHeight);
        }
        ct.stroke();
      }
      
    }
  respondCanvas(can_width,can_height,3,1);
});
