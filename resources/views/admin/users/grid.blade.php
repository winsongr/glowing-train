@extends('layouts.parent')
@section('content')
{{-- @dd($users) --}}
      @if (session('status'))
          <div class="alert alert-success mt-5">
              {{ session('status') }}
          </div>
      @endif
      @if (session('msg'))
          <div class="alert alert-success mt-5">
              {{ session('msg') }}
          </div>
      @endif
<div class="mb-5">
      <div class="float-right pb-3">
            <a href="/admin/users/create" class="btn btn-gradient-info pull-right">Create User</a>
      </div>
      <h4>Users</h4>
</div>
<div class="container msg">
      {{ $users->appends(request()->query())->links('layouts.pagination') }}
</div>
<div class="row ">
    @foreach($users as $key => $value)
        <div class="col-lg-3 grid-margin stretch-card">
    <a href="/admin/{{Crypt::encrypt($value->id)}}/user" class="text-decoration-none">
              <div class="card ">
                  <div class="card-title text-center ">
                      <img src="{{asset('/storage/images/'.$value->image)}}" alt="profile" class="img-lg rounded-circle mt-4">
                      <h4 class="users text-center mt-3 text-white">{{$value->name}}</h4>
                        <div>
                          <span><i class="far fa-phone-alt "></i></span>
                          <span><a class="text-decoration-none  text-white" href="tel:{{$value->phone}}">{{$value->phone}}</a></span>
                        
                        </div>
						@if(\Carbon\Carbon::parse($value->user->created_at)->format('Y-m-d')==\Carbon\Carbon::now()->format('Y-m-d'))
							<div class="text-center mt-1">
								<span class="badge bg-danger">New</span>
							</div>
								@php $margin=0 @endphp
						@else
								@php $margin=3 @endphp
						@endif
                  </div>
                  <div class="card-body">
                      <div class="d-flex justify-content-center mt-{{$margin}}">
                      <ul class="pagination cardaction">
                        <li class="page-item">@if($value->user->status=='1')
                            <a href="javascript:void(0)" class="page-link" onclick="userAction('suspend','put','{{Crypt::encrypt($value->user->id)}}')"><i class="fas fa-ban bg-danger text-light p-2"></i></a>
                            @else
                            <a href="javascript:void(0)" class="page-link" onclick="userAction('activate','put','{{Crypt::encrypt($value->user->id)}}')"><i class="fa fa-check bg-danger text-light p-2"></i></a>
                            @endif</li>
                        <li class="page-item"><a href="/admin/{{Crypt::encrypt($value->id)}}/user" class="page-link"><i class="fas fa-eye bg-info text-light p-2"></i></a></li>
                        <li class="page-item"><a href="/admin/{{Crypt::encrypt($value->id)}}/users" class="page-link"><i class="fas fa-edit bg-success text-light p-2"></i></a></li>
					<li class="page-item"> <a href="javascript:void(0)" data-id="{{Crypt::encrypt($value->id)}}" class="account page-link" data-bs-toggle="modal"><i class="fa fa-history bg-primary text-light p-2"></i></a></li>
                      </ul>
                      </div>
                  </div>
              </div>
        </a>
        </div>
    @endforeach
    

<!-- Modal -->
<div class="modal fade"  tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Transactions</h5>
        <button type="button" class="closebtn btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<div class="current-position py-4"></div>
        <div class="table-responsive">
        <table class="acc table table-bordered border-light">
          <tr class="acchead">
            <td>S.no</td>
            <td>Accounts</td>
            <td>Created Date</td>
          </tr>
        </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function(){

      $(document).on('click','.account',function(){
        var a=$(this).data('id');
        var acc="";
      
        $('.accbody').remove();
          $.ajax({
              type: 'POST',
              url: window.location.origin+'/admin/generate',
              data: {"_token": "{{ csrf_token() }}",'id':a},
              success:function(data){
              position=data['position'];
              data=data['data'];
             	
              for(i=0;i<data.length;i++){
                acc+='<tr class="accbody"><td>'+(i+1)+'</td><td>'+data[i].details+'</td><td>'+data[i].created_at+'</td></tr>';
              }
              $('.acc').append(acc);
              
              $('.current-position').append("<b class='accbody'>Current Position : "+position+"</b>");
              $('.modal').modal('show');
              }
          });
      });
      $(document).on('click','.closebtn',function(){
          $('.modal').modal('hide');
      });
    });
</script>
@endsection
    

