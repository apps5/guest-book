/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/    
class GuestBook_List { 
  
  getModuleName(){  
    return $('input[name="module"]').val();
  }
  
  getMaxLevel(){  
    return $('input[name="maxLevel"]').val();
  }
  
  getContainer(){
    var nameClassContainer = 'container'+$('input[name="module"]').val()+$('input[name="view"]').val();   
    return $('.'+nameClassContainer);
  }
  
  registerEventViewCreateMessage(cont){
    var self = this;
    $(cont).find('.createMessage').on('click', function(e){
      var el = e.currentTarget;
      $(el).addClass('hide');
      self.resetElEditMessage($(cont).find('.cardEditMessage'));
      $(cont).find('.cardEditMessage').removeClass('hide');
    });
  }
  
  registerEventCancelCreateMessage(cont){
    var self = this;
    $(cont).find('.cancelCreateMessage').on('click', function(e){
      var el = e.currentTarget;
      if($(el).closest('.cardMessage').length > 0){   
        if($(el).closest('.newMessage').length > 0){
          var currentLevel = $(el).closest('.newMessage').attr('level');
          var prevLevel = Number(currentLevel)-1;         
          $(el).closest('.containerMessage[level="'+prevLevel+'"]').find('.row').find('.answerMessage').removeClass('hide');
          $(el).closest('.newMessage').remove();
        } else {
          var elCardMessage = $(el).closest('.cardMessage');
          $(el).closest('.cardEditMessage').remove();
          app.resetMondatoryFields($('.cardEditMessage'));
          if($(elCardMessage).find('.viewMessage').length > 0){
            $(elCardMessage).find('.viewMessage').removeClass('hide');  
          }
        }   
      } else {       
        $(el).closest('.cardEditMessage').addClass('hide');
        $(cont).find('.createMessage').removeClass('hide'); 
        app.resetMondatoryFields($(el).closest('.cardEditMessage'));
      }
    });
  }
  
  registerEventViewEditMessage(cont){
    var self = this;
    $(cont).find('.editMessage').on('click', function(e){
      var el = e.currentTarget;
      var elCardMessage = $(el).closest('.cardMessage');
      var userName = $(elCardMessage).find('.userName').html();
      var userMessage = $(elCardMessage).find('.userMessage').html(); 
      var record = $(elCardMessage).find('input[name="record"]').val(); 
      var parent_record = $(elCardMessage).find('input[name="parent_record"]').val(); 
      $(elCardMessage).find('.viewMessage').addClass('hide');      
      var elEditMessage = $(cont).find('.blockCreateMessage .cardEditMessage').clone(true);
      elEditMessage = self.resetElEditMessage(elEditMessage);
      $(elEditMessage).find('[name="name_user"]').val(userName);
      $(elEditMessage).find('[name="message_user"]').val(userMessage);    
      $(elEditMessage).find('input[name="record"]').val(record);
      $(elEditMessage).find('input[name="parent_record"]').val(parent_record);
      $(elEditMessage).removeClass('hide');      
      $(elEditMessage).appendTo(elCardMessage);
    });
  }
  
  registerEventViewAnswerMessage(cont){
    var self = this;
    $(cont).find('.answerMessage').on('click', function(e){
      var el = e.currentTarget;
      $(el).addClass('hide');
      var elContainerMessage = $(el).closest('.containerMessage');  
      var currentRecord = $(elContainerMessage).find('input[name="record"]').val();
      var currentLevel = $(elContainerMessage).attr('level');
      var containerMessageTemlate = $(cont).find('.emptyTemplateForJs').find('.containerMessage').clone(true); 
      $(containerMessageTemlate).attr('level', Number(currentLevel)+1);
      $(containerMessageTemlate).appendTo($(elContainerMessage));     
      var elEditMessage = $(cont).find('.blockCreateMessage .cardEditMessage').clone(true); 
      elEditMessage = self.resetElEditMessage(elEditMessage);
      $(elEditMessage).find('input[name="parent_record"]').val(currentRecord); 
      $(elEditMessage).removeClass('hide');  
      $(elEditMessage).appendTo($(containerMessageTemlate).find('.cardMessage'));   
    });
  }
    
  registerEventSaveMessage(cont){
    var self = this;
    $(cont).find('.saveCreateMessage').on('click', function(e){     
      var el = e.currentTarget;
      var elCardMessage = $(el).closest('.cardEditMessage');
      var checkValid = app.checkMondatoryFields(elCardMessage);
      if(checkValid){
        var params = app.getInputElements(elCardMessage);
        params.module = self.getModuleName();
        params.action = 'Save';     
        app.loaderShow();
        $.when(app.request(params)).done(function(data){     
          app.loaderHide();
          if(data.success){            
            self.renderMessage(elCardMessage, el, data.result);
          } else {
            alert(data.error.message);
          }
        }); 
       
      }
    });
  }
  
  renderMessage(elEditMessage, el, dataRecord){
    var self = this;  
    var cont = self.getContainer();
    if($(el).closest('.blockCreateMessage').length > 0){
      
      var elContainer = $(el).closest('.blockCreateMessage');
      var containerMessageTemlate = self.getTemplateContainerMessage(cont);
      $(containerMessageTemlate).attr('level', 1);
      var messageTemlate = self.getTemplateViewMessage(cont);
      messageTemlate = self.setDataInMessageTemplate(messageTemlate, dataRecord);
      $(messageTemlate).appendTo($(containerMessageTemlate).find('.cardMessage'));
      $(containerMessageTemlate).insertAfter($(elContainer));    
      $(elEditMessage).addClass('hide');
      $(cont).find('.createMessage').removeClass('hide');  
                 
    } else if($(el).closest('.newMessage').length > 0){  
      
      var elContainer = $(el).closest('.newMessage');
      var currentLevel = $(elContainer).attr('level');
      var prevLevel = Number(currentLevel)-1;  
      var messageTemlate = self.getTemplateViewMessage(cont);  
      if(currentLevel == self.getMaxLevel()){
        $(messageTemlate).find('.answerMessage').remove();
      }
      messageTemlate = self.setDataInMessageTemplate(messageTemlate, dataRecord);
      $(messageTemlate).appendTo($(elContainer).find('.cardMessage'));
         
      $(el).closest('.containerMessage[level="'+prevLevel+'"]').find('.row').find('.answerMessage').removeClass('hide');
      $(elContainer).removeClass('newMessage');    
      $(elEditMessage).remove();
      
    } else if($(el).closest('.containerMessage').length > 0){
      
      var elContainer = $(el).closest('.containerMessage');
      var messageExistTemlate = $(elEditMessage).prev();  
      messageExistTemlate = self.setDataInMessageTemplate(messageExistTemlate, dataRecord);
      messageExistTemlate.removeClass('hide');
      $(elEditMessage).remove();
    }
    
  }
  
  getTemplateContainerMessage(cont){
    var containerMessageTemlate = $(cont).find('.emptyTemplateForJs').find('.containerMessage').clone(true);
    $(containerMessageTemlate).removeClass('newMessage');
    return containerMessageTemlate;
  }
  
  getTemplateViewMessage(cont){
    var messageTemlate = $(cont).find('.emptyTemplateForJs').find('.viewMessage').clone(true);
    $(messageTemlate).removeClass('newViewMessage');
    return messageTemlate;
  }
  
  setDataInMessageTemplate(messageTemlate, dataRecord){   
    $(messageTemlate).find('input[name="record"]').val(dataRecord.record); 
    $(messageTemlate).find('input[name="parent_record"]').val(dataRecord.parent_record); 
    $(messageTemlate).find('.userName').html(dataRecord.name_user);
    $(messageTemlate).find('.userMessage').html(dataRecord.message_user);
    var datetimeHtml = dataRecord.datetime;
    if(dataRecord.changed == 1){
      datetimeHtml = '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;&nbsp;' + datetimeHtml;
    }
    $(messageTemlate).find('.message-date').html(datetimeHtml); 
    return messageTemlate;
  }
  
  resetElEditMessage(elEditMessage){
    $(elEditMessage).find('input[name="record"]').val(''); 
    $(elEditMessage).find('input[name="parent_record"]').val(''); 
    $(elEditMessage).find('[name="name_user"]').val('');
    $(elEditMessage).find('[name="message_user"]').val('');
    app.resetMondatoryFields(elEditMessage);
    return elEditMessage;
  }
  
  registerEvents() {  
    var self = this;    
    var cont = self.getContainer();
    app.registerEventValidationFields();
    self.registerEventViewCreateMessage(cont);
    self.registerEventCancelCreateMessage(cont);
    self.registerEventViewEditMessage(cont);
    self.registerEventSaveMessage(cont);
    self.registerEventViewAnswerMessage(cont);
  }
    
}

$(window).bind("load", function() {
  let guestBookList = new GuestBook_List();
  guestBookList.registerEvents();
});
