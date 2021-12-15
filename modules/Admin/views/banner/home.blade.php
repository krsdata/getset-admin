
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
             <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEAD-->
                    
                    <!-- END PAGE HEAD-->
                    <!-- BEGIN PAGE BREADCRUMB -->
                   @include('packages::partials.breadcrumb')

                    <!-- END PAGE BREADCRUMB -->
                    <!-- BEGIN PAGE BASE CONTENT -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light portlet-fit bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-settings font-red"></i>
                                        <span class="caption-subject font-red sbold uppercase">{{ $heading }}</span>
                                    </div>
                                     <div class="col-md-2 pull-right">
                                            <div style="width: 150px;" class="input-group"> 
                                                <a href="{{ route('banner.create')}}">
                                                    <button class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Banner</button> 
                                                </a>
                                            </div>
                                        </div> 
                                     
                                </div>
                                  
                                    @if(Session::has('flash_alert_notice'))
                                         <div class="alert alert-success alert-dismissable" style="margin:10px">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                          <i class="icon fa fa-check"></i>  
                                         {{ Session::get('flash_alert_notice') }} 
                                         </div>
                                    @endif
                                <div class="portlet-body">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <form action="{{route('banner')}}" method="get" id="filter_data">
                                             
                                            <div class="col-md-3">
                                                <input value="{{ (isset($_REQUEST['search']))?$_REQUEST['search']:''}}" placeholder="Search by  name" type="text" name="search" id="search" class="form-control" >
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" value="Search" class="btn btn-primary form-control">
                                            </div>
                                           
                                        </form>
                                         <div class="col-md-2">
                                             <a href="{{ route('banner') }}">   <input type="submit" value="Reset" class="btn btn-default form-control"> </a>
                                        </div>
                                       
                                        </div>
                                    </div>
                                     
                                    <table class="table table-striped table-hover table-bordered" id="">
                                        <thead>
                                            <tr>
                                                <th> Banner Title </th>
                                                <th> Image </th> 
                                                <th>Type</th>
                                                <th>Created date</th> 
                                                <th>Action</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($banners as $key => $result)
                                            <tr>
                                                <td> {{$result->title}} </td>
                                                <td>
                                                <a href="{{ url::asset('storage/uploads/banner/'.$result->photo)  }}" target="_blank" >
                                                <img src="{{ url::asset('storage/uploads/banner/'.$result->photo)  }}" width="100px"> </a>  </td>
                                                <td>Header</td>
                                                     <td>
                                                        {!! Carbon\Carbon::parse($result->created_at)->format('d-m-Y'); !!}
                                                    </td>
                                                    
                                                    
                                                    <td> 
                                                        <a href="{{ route('banner.edit',$result->id)}}">
                                                            <i class="fa fa-edit" title="edit"></i> 
                                                        </a>

                                                        {!! Form::open(array('class' => 'form-inline pull-left deletion-form', 'method' => 'DELETE',  'id'=>'deleteForm_'.$result->id, 'route' => array('banner.destroy', $result->id))) !!}
                                                        <button class='delbtn btn btn-danger btn-xs' type="submit" name="remove_levels" value="delete" id="{{$result->id}}"><i class="fa fa-fw fa-trash" title="Delete"></i></button>
                                                        
                                                         {!! Form::close() !!}

                                                    </td>
                                               
                                            </tr>
                                           @endforeach

                                           <tbody>
                                            @if(!empty($quiz))
                                            @foreach($quiz as $k => $value)
                                            <tr>
                                                <td> {{$value->title}} </td>
                                                <td>
                                                <a href="{{ $value->url}}" target="_blank" >
                                                <img src="{{ $value->url}}" width="100px"> </a>  </td>
                                                <td>Quiz</td>
                                                     <td>
                                                        {!! Carbon\Carbon::parse($value->created_at)->format('d-m-Y'); !!}
                                                    </td>
                                                    
                                                    <td> 
                                                        <a href="{{ url('quiz/delete/'.$value->id)}}" class='delbtn btn btn-danger btn-xs'>
                                                            <i class="fa fa-fw fa-trash" title="Delete"></i>
                                                        </a>
                                                        <a href="{{ url('quiz/edit/'.$value->id)}}">
                                                            <i class="fa fa-edit" title="edit"></i> 
                                                        </a>
                                                        
                                                         

                                                    </td>
                                               
                                            </tr>
                                           @endforeach
                                           @endif
                                            
                                        </tbody>
                                    </table>
                                     <div class="center" align="center">  {!! $banners->appends(['search' => isset($_GET['search'])?$_GET['search']:''])->render() !!}</div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    </div>
                    <!-- END PAGE BASE CONTENT -->
                </div>
                <!-- END CONTENT BODY -->
            </div>
            
            
            <!-- END QUICK SIDEBAR -->
        </div>
        
