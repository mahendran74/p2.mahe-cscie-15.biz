<div class="wrapper">
   <!-- page-content -->
   <div class="page-content">
     <h2 class="heading">KEYWORD CLOUD</h2>
      <!-- entry-content 	-->
      <div class="entry-content cf">
         <div id="wordcloud1" class="wordcloud">
            <?php foreach($trends as $trend): ?>
               <span data-weight="<?=$trend['weight']*10?>"><?=$trend['keyword']?></span>
            <?php endforeach; ?>
         </div>
      </div>
      <div class="c-1"></div>
      <div class="c-2"></div>
      <div class="c-3"></div>
      <div class="c-4"></div>
   </div>
</div>