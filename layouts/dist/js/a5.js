/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/    
class A5_App { 
  
  request(params){
    return $.post('index.php?', params);
  }
  
  getInputElements(editForm){
    var elemets= editForm.find('.inputElement');
    var res = {};    
    $(elemets).each(function(index, Elem){
      res[$(Elem).attr('name')] = $(Elem).val();
    });
    return res;
  }

  checkMondatoryFields(editForm){
    var elemetsMondatory= editForm.find('.inputElement.required');
    var check = 1;
    $(elemetsMondatory).each(function(index, Elem){
      if(!$(Elem).val()){
        check = 0;
        $(Elem).addClass('noValid');
      }
    });
    return check;
  }
  
  resetMondatoryFields(editForm){
    var elemetsMondatory= editForm.find('.inputElement.required');
    $(elemetsMondatory).each(function(index, Elem){
      $(Elem).removeClass('noValid');
    });
  }
  
  registerEventValidationFields(){
    var elemetsMondatory= $('.inputElement.required');
    $(elemetsMondatory).each(function(index, Elem){      
      if($(Elem).hasClass('Datetimepicker')){
        $(Elem).on("dp.change", function (e) {
          if($(Elem).val()){
            $(Elem).removeClass('noValid');
          } else {
            $(Elem).addClass('noValid');
          }
        });
      } else {
        $(Elem).on('change', function () {  
          if($(Elem).val()){
            $(Elem).removeClass('noValid');
          } else {
            $(Elem).addClass('noValid');
          }
        });      
      }
    });
  }
  
  loaderShow(){
    $(".loader").removeClass('hide');
    $(".loading").css('z-index', '100');
    $(".loading").addClass('loading_background');
  }
  
  loaderHide(){
    $(".loader").addClass('hide');
    $(".loading").css('z-index', '-1');
    $(".loading").removeClass('loading_background');
  }
    
}

let app = new A5_App();

 
