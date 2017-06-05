<!-- Boost Post -->
<div class="modal fade" id="boost_post_popup" role="dialog">
    <div class="modal-dialog clearfix popupHeight">
      <!-- Modal content-->
      <div class="modal-content clearfix">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Boost post in Groups</h5>
        </div>
        {{ Form::text('boost_category_search', '', array('class' => 'form-control','id'=>'boost_category_search')) }}
        {{ Form::button('Search', array('class' => 'btn btn-primary btn-sm boost_search_button')) }}
        

        {{ Form::open(array('route' => 'frontend.boost.store','id'=>'boost_post_category_form')) }}
          {{ Form::hidden('boost_post_id',isset($post_id)?$post_id:'', array('id' => 'boost_post_id')) }}
          {{ Form::hidden('user_id',access()->user()->id, array('id' => 'boost_post_id')) }}
          {{ Form::hidden('boost_unchecked_categories','', array('id' => 'boost_unchecked_categories')) }}
          <div class="modal-body clearfix load-user-groups">
            

            <div style="clear:both"> </div>
          </div>
          <div class="modal-footer clearfix">
            <span class="error empty_boost_form_error">
            </span>
            {{ Form::button('Boost Post Now', array('class' => 'btn btn-success boost_submit')) }}
          </div>
          {{ Form::close() }}
      </div>    
    </div>
</div>
<!-- Report Post -->