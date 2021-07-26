{*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************}	
<div class="container containerMessage" level="{$LEVEL}">
   <div class="row">
      <div class="col-md-12 cardMessage">
        <div class="card viewMessage">
          <div class="card-body ">
          <input type="hidden" name="record" value="{$RECORD['record']}">
          <input type="hidden" name="parent_record" value="{$RECORD['parent_record']}">
            <div class="card-title">
              <p class="card-text">
                <b class="userName">{$RECORD['name_user']}</b> <span class="message-date">
                  {if $RECORD['changed']}
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>&nbsp;
                  {/if}
                  {$RECORD['datetime']|date_format:"%d-%m-%Y %H:%M:%S"}
                </span>
              </p>
              <p class="userMessage">{$RECORD['message_user']}</p>
            </div>
          </div>
          <div class="card-footer">
            <span class="float-left blockMessageAction">
              <a class="pr-1 editMessage">{$LBL_EDIT}</a>
              {if $LEVEL < $MAX_LEVEL}
                <a class="pr-1 answerMessage">{$LBL_ANSWER}</a>
              {/if}  
            </span>
            <span class="float-right">									
            </span>
          </div>
        </div>
      </div>
    </div>  
    {assign var=LEVEL value=$LEVEL+1}
    {foreach item=ITEM_RECORD from=$LIST_RECORDS_ALL}
      {if $ITEM_RECORD['parent_record'] eq $RECORD['record']}
        {include file="layouts/modules/GuestBook/ItemMessage.tpl" RECORD=$ITEM_RECORD LIST_RECORDS_ALL=$LIST_RECORDS LEVEL=$LEVEL}
      {/if}
    {/foreach}  
 </div> 
