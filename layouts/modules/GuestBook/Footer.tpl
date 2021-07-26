{*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************}
 
		<div class="loading">		
				<div class="loader hide" id="loader-1">
				</div>
		</div>	
		<script src="layouts/dist/js/jquery-3.2.1.min.js"></script>	
		<script src="layouts/dist/js/bootstrap.min.js"></script>
    <script src="layouts/dist/js/a5.js"></script>
		{foreach key=index item=js from=$SCRIPTS}
			<script src="{$js}"></script>
		{/foreach}
  </body>
</html>  
