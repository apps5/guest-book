{*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************}	
 <div class="text-center Title">
 	  <h4>{$MODULE_HEADER_TITLE}</h4>
 </div>
 <div class="container{$MODULE}{$VIEW}">
	 <div class="container blockCreateMessage">
 		<div class="row">
			 <input type="hidden" name="maxLevel" value="{$MAX_LEVEL}">
 			 <div class="col-md-12">
 				 <div class="headActions">
 					 <button type="button" class="btn btn-info btn-sm createMessage">{$LBL_SEND_MESSAGE}</button>
 				 </div>
 				 <div class="containerEditMessage">
					 <div class="card cardEditMessage hide">
   			 		<div class="card-body">
  						<input type="hidden" class="inputElement" name="record" value="">
  						<input type="hidden" class="inputElement" name="parent_record" value="">
  						<div class="card-text fieldValue">
  							<input name="name_user" type="text" class="inputElement required" placeholder="Как Вас зовут?" style="max-width:480px;">
  						</div>
  						<div class="fieldValue">
  							<textarea rows="3" name="message_user" class="inputElement required" placeholder="Сообщение" ></textarea>
  						</div>
   			 		</div>
   			 		<div class="">
   			 			<span class="blockMessageAction">
   			 				<button type="button" class="btn btn-success btn-sm saveCreateMessage">{$LBL_SAVE}</button>
   			 				<a class="pl-1 cancelCreateMessage">{$LBL_CANCEL}</a>
   			 			</span>
   			 		</div>
   			 	</div>
 				 </div>			 
 			 </div>	 
 		</div>
 	</div>
	{foreach item=HEAD_RECORD from=$LIST_HEAD_RECORDS}
	  {assign var=LEVEL value=1}
	  {include file="layouts/modules/GuestBook/ItemMessage.tpl" RECORD=$HEAD_RECORD LIST_RECORDS_ALL=$LIST_RECORDS LEVEL=$LEVEL}
  {/foreach}	
	{include file="layouts/modules/GuestBook/EmptyTemplateJs.tpl"}
 </div>

  
	
	
	

			 
				 

	
	
	

			 
				 
