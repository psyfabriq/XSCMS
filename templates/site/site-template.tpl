{include '../templates/site/blocks/site-block-navbar.tpl'}

<!-- banner -->
{include '../templates/site/blocks/site-block-logobar.tpl'}
<!-- /banner -->

{include '../templates/site/blocks/site-block-bodyprefix.tpl'}
  <div  ng-view class="view-slide-in" ></div>
{include '../templates/site/blocks/site-block-bodypostfix.tpl'}

<!-- Footer -->
<footer class="footer-distributed">
  <div class="container">   
    <div class="row">
      {include '../templates/site/blocks/site-block-footerbar.tpl'}
    </div>  
    <hr>
    <div class="copyright">
      <p class="text-center">© 2016 PsyFabriQ DEV. All Rights Reserved | Design by <a href="http://vk.com/psyfabriq" target="_blank">PsyFabriQ DEV</a></p>
    </div>
    <a href="#myPage" title="To Top"><span class="glyphicon glyphicon-chevron-up"></span></a>
  </div>  
</footer>
