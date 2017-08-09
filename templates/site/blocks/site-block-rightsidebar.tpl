<aside class="control-sidebar control-sidebar-light">
   <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab" showtab=""><i class="fa fa-home"></i></a></li>
      <li ><a href="#control-sidebar-theme-xscmsstyle-options-tab" data-toggle="tab" showtab=""><i class="fa fa-wrench"></i></a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane active" id="control-sidebar-home-tab">
         <div ng-controller="BlockController as ctrl">
            <div ng-repeat="blocks in blocksList.right_sidebar_home"> <block-constructor block="blocks"></block-constructor> </div>
        </div> 
      </div>
      <div id="control-sidebar-theme-xscmsstyle-options-tab" class="tab-pane">
         <div>
          
           <div ng-controller="BlockController as ctrl">
                <div ng-repeat="blocks in blocksList.right_sidebar_settings"> <block-constructor block="blocks"></block-constructor> </div>
           </div> 
            
         </div>
      </div>
   </div>
</aside>
<div class='control-sidebar-bg'></div>